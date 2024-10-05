document.addEventListener("DOMContentLoaded", function () {
  // Realiza una solicitud AJAX para verificar si el usuario está autenticado
  fetch('/auth-status')
    .then(response => response.json())
    .then(data => {
      if (!data.isAuthenticated) {
        console.log('El usuario no está autenticado');
        return;
      }

      // Crear el botón flotante para abrir/cerrar el chat en la esquina inferior derecha
      let chatToggleButton = document.createElement('div');
      chatToggleButton.id = 'chatToggleButton';
      chatToggleButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
          <path fill-rule="evenodd" d="M4.5 5.25A2.25 2.25 0 016.75 3h10.5A2.25 2.25 0 0119.5 5.25v8.5a2.25 2.25 0 01-2.25 2.25H9l-4.72 3.54a.75.75 0 01-1.28-.58V5.25zm3.75 4a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5H8.25zm0 3.5a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5z" clip-rule="evenodd"/>
        </svg>
      `;
      document.body.appendChild(chatToggleButton);

      // Crear el sidebar de chat
      let chatSidebar = document.createElement('div');
      chatSidebar.id = 'chatSidebar';
      chatSidebar.innerHTML = `
        <h3>Chat empresa</h3>
        <div id="chatContent" style="flex-grow: 1; overflow-y: auto;">
            <!-- Mensajes del chat y logs -->
            <p class="logistica">Logística: XXXX</p>
            <p class="contabilidad">Contabilidad: XXXX</p>
            <p class="gerencia">Gerencia: XXXX</p>
            <p class="logistica">Logística: Ha cargado Guía de despacho #XXXXX de OT000XXX</p>
        </div>
        <div id="chatInputContainer" style="display: flex; padding-top: 10px;">
            <input type="text" id="messageInput" placeholder="Escribe un mensaje..." style="flex-grow: 1; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
            <button onclick="sendMessage()">Enviar</button>
        </div>
      `;
      document.body.appendChild(chatSidebar);

      // Obtener el contenedor principal correctamente
      let mainContent = document.querySelector('.filament-main-content');

      // Función para abrir/cerrar el sidebar y mover el botón flotante
      chatToggleButton.addEventListener('click', function () {
        chatSidebar.classList.toggle('open'); // Alternar la clase 'open'
        if (mainContent) { // Verificar si mainContent no es null
          mainContent.classList.toggle('shifted'); // Mover el contenido principal si existe
        }
        chatToggleButton.classList.toggle('move-left'); // Mover el botón hacia la izquierda cuando el sidebar esté abierto
      });

      // Evento para enviar el mensaje al presionar Enter
      let messageInput = document.getElementById('messageInput');
      messageInput.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
          event.preventDefault(); // Evita un salto de línea
          sendMessage(); // Llama a la función para enviar el mensaje
        }
      });
    })
    .catch(error => {
      console.error('Error al verificar la autenticación:', error);
    });
});

// Función para enviar un mensaje
function sendMessage() {
  let input = document.getElementById('messageInput');
  if (input.value.trim() !== '') {
    let chatContent = document.getElementById('chatContent');
    chatContent.innerHTML += `<p class="logistica"><strong>Logística:</strong> ${input.value}</p>`;
    input.value = ''; // Limpiar el campo de entrada
    chatContent.scrollTop = chatContent.scrollHeight; // Scroll automático hacia abajo
  }
}
