<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chatbot — Sistema de Asignación de Salones</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, sans-serif; background:#0f172a; color:#e2e8f0; margin:0; }
    .container { max-width: 900px; margin: 0 auto; padding: 1.5rem; }
    .header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
    .title { font-weight: 700; font-size: 1.25rem; }
    .chat { background:#111827; border:1px solid #1f2937; border-radius:12px; padding:1rem; min-height:60vh; display:flex; flex-direction:column; }
    .messages { flex:1; overflow-y:auto; padding-right: .5rem; }
    .msg { margin:.5rem 0; }
    .msg.user { text-align: right; }
    .bubble { display:inline-block; padding:.6rem .8rem; border-radius:10px; max-width:70%; }
    .bubble.user { background:#2563eb; color:white; }
    .bubble.bot { background:#1f2937; color:#e5e7eb; }
    .composer { display:flex; gap:.5rem; margin-top: .75rem; }
    .input { flex:1; padding:.6rem .8rem; border-radius:10px; border:1px solid #374151; background:#0b1220; color:#e5e7eb; }
    .send { background:#10b981; color:white; border:none; padding:.6rem 1rem; border-radius:10px; font-weight:600; cursor:pointer; }
    .send:disabled { opacity:.6; cursor:default; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title">🤖 Chatbot del Sistema</div>
      <a href="{{ route('home') }}" style="color:#93c5fd; text-decoration:none;">← Volver</a>
    </div>

    <div class="chat">
      <div id="messages" class="messages"></div>
      <form id="composer" class="composer">
        <input id="text" class="input" type="text" placeholder="Escribe tu pregunta…" autocomplete="off" required />
        <button id="send" class="send" type="submit">Enviar</button>
      </form>
    </div>
  </div>

  <script>
    const messagesEl = document.getElementById('messages');
    const formEl = document.getElementById('composer');
    const inputEl = document.getElementById('text');
    const sendEl = document.getElementById('send');

    function appendMessage(text, who = 'bot') {
      const wrap = document.createElement('div');
      wrap.className = `msg ${who}`;
      const bubble = document.createElement('div');
      bubble.className = `bubble ${who}`;
      bubble.textContent = text;
      wrap.appendChild(bubble);
      messagesEl.appendChild(wrap);
      messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    // Mensaje de bienvenida
    appendMessage('Hola 👋 Soy el chatbot del Sistema. Puedo responder preguntas como: “¿Cuántos profesores hay?”, “¿Qué grupos existen?”, “¿Cuántos estudiantes tiene cada carrera?”.');

    formEl.addEventListener('submit', async (e) => {
      e.preventDefault();
      const text = inputEl.value.trim();
      if (!text) return;

      // Mostrar mensaje del usuario
      appendMessage(text, 'user');
      inputEl.value = '';
      inputEl.focus();
      sendEl.disabled = true;

      // Preparar payload para API interna que reenvía a n8n
      const payload = {
        message: text,
        // Opcionalmente se puede manejar un session_id persistente
        session_id: window.sessionId || (window.sessionId = Math.random().toString(36).slice(2))
      };

      try {
        const resp = await fetch('/api/chatbot/message', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-API-Token': '{{ config('app.n8n_api_token') }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        if (!resp.ok) {
          appendMessage('Lo siento, hubo un error consultando el chatbot.');
        } else {
          const data = await resp.json();
          // Intentamos varias posibles claves que suelen devolver los workflows de n8n
          let botText = '';
          if (typeof data === 'string') {
            botText = data;
          } else {
            botText = (
              data?.answer ||
              data?.output ||
              data?.result ||
              data?.message ||
              data?.response ||
              data?.data?.answer ||
              data?.data?.output ||
              data?.data?.message ||
              JSON.stringify(data)
            );
          }
          appendMessage(botText, 'bot');
        }
      } catch (err) {
        console.error(err);
        appendMessage('Error de red al contactar el chatbot.');
      } finally {
        sendEl.disabled = false;
      }
    });
  </script>
</body>
</html>
