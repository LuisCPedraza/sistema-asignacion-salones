<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chat Hosted — Sistema de Asignación de Salones</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, sans-serif; background:#0f172a; color:#e2e8f0; margin:0; }
    .container { max-width: 1100px; margin: 0 auto; padding: 1rem; }
    .header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
    .title { font-weight: 700; font-size: 1.25rem; }
    .frameWrap { background:#111827; border:1px solid #1f2937; border-radius:12px; overflow:hidden; }
    iframe { width: 100%; height: 80vh; border: 0; background: #0b1220; }
    .warn { background:#1f2937; border:1px solid #374151; border-radius:8px; padding:1rem; }
    a.link { color:#93c5fd; text-decoration:none; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title" style="display:flex;align-items:center;gap:.6rem">🤖 <span>Asistente del Sistema</span></div>
      <a href="{{ route('home') }}" class="link" style="background:#0ea5e9;color:#0b1220;padding:.5rem .9rem;border-radius:10px;box-shadow:0 8px 20px rgba(14,165,233,.25);font-weight:700">← Volver</a>
    </div>

    @php($chatUrl = env('N8N_WEBHOOK_CHATBOT'))
    @if($chatUrl)
      <div class="frameWrap">
        <iframe src="{{ $chatUrl }}" title="Chat n8n Hosted"></iframe>
      </div>
    @else
      <div class="warn">
        No se encontró la variable de entorno <strong>N8N_WEBHOOK_CHATBOT</strong>.
        Configúrala en el archivo .env con la URL del chat hosted de n8n.
      </div>
    @endif
  </div>
</body>
</html>
