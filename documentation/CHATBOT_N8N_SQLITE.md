# Chatbot Local con n8n + SQLite

Esta guía describe cómo crear un chatbot local usando n8n y SQLite, integrándolo con tu proyecto Laravel. Evita dependencias externas (como Supabase) y funciona enteramente en tu máquina.

## Índice
- Prerrequisitos
- Migraciones SQLite para el chatbot
- Configuración de n8n (Webhook + SQLite)
- Configuración de `.env`
- Endpoint Laravel para interactuar con el chatbot
- Seeder para Base de Conocimiento
- Comandos rápidos de prueba
- Comparativa SQLite vs Supabase
- Troubleshooting

---

## Prerrequisitos
- n8n instalado localmente (Docker o npm)
- Laravel corriendo en `http://localhost:8000`
- SQLite configurado en `.env` con `DB_CONNECTION=sqlite` y `DB_DATABASE=database/database.sqlite`
- **PHP con extensión pdo_sqlite** habilitada

### ⚠️ Verificar y habilitar pdo_sqlite

Si obtienes error `could not find driver`, necesitas habilitar la extensión SQLite:

**En Windows con XAMPP/WAMP:**
1. Abre `php.ini` (busca la ruta con `php --ini`)
2. Busca la línea `;extension=pdo_sqlite` y quita el `;` para descomentarla
3. Busca la línea `;extension=sqlite3` y quita el `;`
4. Reinicia tu servidor web

**En Linux (WSL/Ubuntu):**
```bash
sudo apt-get update
sudo apt-get install php-sqlite3 php-pdo-sqlite
sudo service apache2 restart  # o el servidor que uses
```

**Verificar instalación:**
```bash
php -m | grep -i pdo
# Deberías ver: PDO, pdo_mysql, pdo_sqlite
```

Opciones de instalación de n8n:

### Docker (recomendado)
```bash
# Crear carpeta de datos persistentes
mkdir C:\\n8n-data

# Ejecutar n8n con Docker
docker run -d ^
  --name n8n ^
  -p 5678:5678 ^
  -v C:\\n8n-data:/home/node/.n8n ^
  n8nio/n8n

# Verificar contenedor activo
docker ps | findstr n8n
```

### npm (alternativa)
```bash
npm install -g n8n
n8n start
```

Accede a n8n en `http://localhost:5678`.

---

## Migraciones SQLite para el chatbot
Crea las tablas para conversaciones, mensajes y base de conocimiento.

```bash
php artisan make:migration create_chatbot_tables
```

Contenido sugerido de la migración:
```php
Schema::create('chat_conversations', function (Blueprint $table) {
    $table->id();
    $table->string('session_id')->unique();
    $table->text('context')->nullable();
    $table->timestamp('last_activity')->nullable();
    $table->timestamps();
});

Schema::create('chat_messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('conversation_id')
          ->constrained('chat_conversations')
          ->onDelete('cascade');
    $table->enum('sender', ['user', 'bot']);
    $table->text('message');
    $table->json('metadata')->nullable();
    $table->timestamps();
});

Schema::create('chat_knowledge_base', function (Blueprint $table) {
    $table->id();
    $table->string('category');
    $table->text('question');
    $table->text('answer');
    $table->integer('usage_count')->default(0);
    $table->timestamps();
});
```

Aplica migraciones:
```bash
php artisan migrate
```

---

## Configuración de n8n (Webhook + SQLite)
Crear un workflow llamado "Chatbot Handler".

Flujo:
```
[Webhook POST chatbot-handler] -> [Parser JSON] -> [SQLite: buscar en knowledge_base] ->
[Condicional: encontró?] -> [Generar respuesta] -> [SQLite: guardar mensaje] -> [HTTP Response]
```

### Paso 1: Webhook
- Node: Webhook
- Method: POST
- Path: `chatbot-handler`
- Guarda el workflow para obtener URL: `http://localhost:5678/webhook/chatbot-handler`

### Paso 2: HTTP Request - Buscar en Base de Conocimiento
**Nota**: n8n no tiene conexión directa a SQLite. Usaremos HTTP Request hacia endpoints de Laravel.

- Node: **HTTP Request**
- Method: POST
- URL: `http://localhost:8000/api/chatbot/search-knowledge`
- Body (JSON):
```json
{
  "query": "{{ $json.body.message }}"
}
```
- Authentication: None (por ahora)

Este endpoint buscará en tu base SQLite y devolverá coincidencias.

### Paso 3: Lógica condicional
- Si hay coincidencias: usar la mejor respuesta de `chat_knowledge_base`.
- Si no hay: responder con fallback, por ejemplo:
  "No tengo una respuesta para eso aún. ¿Puedes reformular o ser más específico?"

### Paso 4: HTTP Request - Guardar conversación
- Node: **HTTP Request**
- Method: POST
- URL: `http://localhost:8000/api/chatbot/save-conversation`
- Body (JSON):
```json
{
  "session_id": "{{ $json.body.session_id }}",
  "user_message": "{{ $json.body.message }}",
  "bot_message": "{{ $json.bot_response }}"
}
```

Este endpoint guardará los mensajes en SQLite y retornará confirmación.

### Paso 5: HTTP Response
Devuelve JSON con `success`, `message`, y `session_id`.

---

## Configuración de `.env`
Añade variables para el chatbot:

```env
N8N_WEBHOOK_CHATBOT=http://localhost:5678/webhook/chatbot-handler
CHATBOT_ENABLED=true
CHATBOT_DATABASE_PATH=database/database.sqlite
```

Ya usas SQLite local:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

---

## Endpoints Laravel para el chatbot
Crea estos 3 endpoints en `routes/api.php`:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

// 1. Endpoint principal: enviar mensaje al chatbot (n8n)
Route::post('/chatbot/message', function (Request $request) {
    $message = $request->input('message');
    $sessionId = $request->input('session_id', uniqid());

    $resp = Http::post(env('N8N_WEBHOOK_CHATBOT'), [
        'message' => $message,
        'session_id' => $sessionId,
    ]);

    return response()->json($resp->json());
});

// 2. Endpoint interno: buscar en base de conocimiento
Route::post('/chatbot/search-knowledge', function (Request $request) {
    $query = $request->input('query');
    
    $results = DB::table('chat_knowledge_base')
        ->whereRaw('LOWER(question) LIKE ?', ['%' . strtolower($query) . '%'])
        ->orderBy('usage_count', 'desc')
        ->limit(5)
        ->get();

    return response()->json([
        'found' => $results->isNotEmpty(),
        'results' => $results,
        'best_match' => $results->first()
    ]);
});

// 3. Endpoint interno: guardar conversación
Route::post('/chatbot/save-conversation', function (Request $request) {
    $sessionId = $request->input('session_id');
    $userMessage = $request->input('user_message');
    $botMessage = $request->input('bot_message');

    // Crear o encontrar conversación
    $conversation = DB::table('chat_conversations')
        ->where('session_id', $sessionId)
        ->first();

    if (!$conversation) {
        DB::table('chat_conversations')->insert([
            'session_id' => $sessionId,
            'last_activity' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $conversation = DB::table('chat_conversations')
            ->where('session_id', $sessionId)
            ->first();
    }

    // Guardar mensajes
    DB::table('chat_messages')->insert([
        ['conversation_id' => $conversation->id, 'sender' => 'user', 'message' => $userMessage, 'created_at' => now(), 'updated_at' => now()],
        ['conversation_id' => $conversation->id, 'sender' => 'bot', 'message' => $botMessage, 'created_at' => now(), 'updated_at' => now()],
    ]);

    return response()->json(['success' => true, 'conversation_id' => $conversation->id]);
});
```

---

## Seeder para Base de Conocimiento
Crea datos iniciales para el chatbot.

```bash
php artisan make:seeder ChatKnowledgeBaseSeeder
```

Contenido sugerido del seeder:
```php
use Illuminate\Support\Facades\DB;

DB::table('chat_knowledge_base')->insert([
    [
        'category' => 'general',
        'question' => '¿Qué es este sistema?',
        'answer'   => 'Es un sistema de asignación de salones para organizar horarios y grupos.',
        'usage_count' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'category' => 'general',
        'question' => '¿Cómo creo una asignación?',
        'answer'   => 'Ve a la sección Asignaciones y haz clic en "Nueva asignación".',
        'usage_count' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
```

Ejecuta:
```bash
php artisan db:seed --class=ChatKnowledgeBaseSeeder
```

---

## Comandos rápidos de prueba

### Probar workflow n8n directamente
```bash
curl -X POST \
  http://localhost:5678/webhook/chatbot-handler \
  -H "Content-Type: application/json" \
  -d "{\"message\":\"Hola, ¿qué es el sistema?\", \"session_id\":\"test-123\"}"
```

### Probar desde Laravel (API)
```bash
curl -X POST \
  http://localhost:8000/api/chatbot/message \
  -H "Content-Type: application/json" \
  -d "{\"message\":\"Hola, ¿cómo creo una asignación?\", \"session_id\":\"test-456\"}"
```

---

## Comparativa: SQLite vs Supabase
- **Costo**: SQLite gratis vs Supabase con costo según uso.
- **Latencia**: SQLite ~5ms local vs Supabase ~100ms + red.
- **Escalabilidad**: SQLite limitada, ideal para desarrollo y uso local.
- **Recursos**: SQLite usa muy pocos recursos.

---

## Troubleshooting
- Asegúrate de que el path del archivo SQLite en n8n sea absoluto y válido.
- Si el Webhook devuelve 404, revisa que el workflow esté **activado** en n8n.
- En Windows, cuidado con rutas con espacios: usa comillas.
- Si `Http::post(...)` falla, verifica `APP_URL`, CORS y que n8n esté corriendo.
- Revisa logs en `storage/logs/laravel.log` y en n8n (panel de ejecuciones).

---

## Siguientes pasos
- Añadir autenticación al Webhook (API Key / JWT).
- Implementar búsqueda semántica (embeddings locales) si necesitas respuestas más inteligentes.
- Integrar UI de chat en tu frontend y persistir el estado de conversación.
