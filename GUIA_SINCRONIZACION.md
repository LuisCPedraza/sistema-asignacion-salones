# Guía para Sincronizar Datos con Supabase

## Opción 1: Desde WSL (RECOMENDADO)

Abre una terminal de WSL Ubuntu y ejecuta:

```bash
cd /home/suario/proyectos/sistema-asignacion-salones
php artisan sync:supabase
```

## Opción 2: Exportar e Importar Manualmente

### Paso 1: Exportar desde SQLite

Desde WSL, ejecuta:

```bash
cd /home/suario/proyectos/sistema-asignacion-salones
chmod +x export-to-sql.sh
./export-to-sql.sh
```

### Paso 2: Importar a Supabase

Ve al Dashboard de Supabase:
1. Abre https://supabase.com/dashboard
2. Ve a tu proyecto
3. Click en "SQL Editor"
4. Copia el contenido de `storage/backups/sqlite_dump.sql`
5. Pega y ejecuta

## Opción 3: Ejecutar las Migraciones + Seeders en Supabase

Esto creará las tablas vacías en Supabase y luego puedes usar el sync:supabase

```bash
# Desde WSL
cd /home/suario/proyectos/sistema-asignacion-salones

# Cambiar temporalmente a PostgreSQL
export DB_CONNECTION=pgsql
export DB_DATABASE=postgres
export DB_USERNAME=postgres.vzgdbeycqebftjnsmcpj
export DB_PASSWORD=0hIdb3JDvTdwHoF5

# Ejecutar migraciones
php artisan migrate:fresh

# Ejecutar seeders si los tienes
php artisan db:seed

# Sincronizar datos existentes
php artisan sync:supabase
```

## Verificar qué conexión está activa

```bash
php artisan tinker
>>> DB::connection()->getDatabaseName()
```

## Troubleshooting

### Si dice "could not find driver"

Tu PHP no tiene PDO SQLite habilitado. Instálalo:

```bash
sudo apt-get update
sudo apt-get install php-sqlite3
```

### Si falla la conexión a Supabase

Verifica las credenciales en `.env`:

```env
DB_HOST=aws-1-us-east-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE_PROD=postgres
DB_USERNAME_PROD=postgres.vzgdbeycqebftjnsmcpj
DB_PASSWORD_PROD=0hIdb3JDvTdwHoF5
```

## Verificar que la sincronización funcionó

Accede a Supabase Dashboard -> Table Editor y verifica que las tablas tengan datos.
