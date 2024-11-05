document.addEventListener("DOMContentLoaded", function () {
  
  // Verificar si el usuario está autenticado antes de cargar el chat
  fetch('/auth-status')
    .then(response => response.json())
    .then(data => {
      if (!data.isAuthenticated) {
        console.log('El usuario no está autenticado');
        return; // No seguir si no está autenticado
      }

      console.log('Usuario autenticado, cargando chat...');
      
      // Ahora que el usuario está autenticado, cargamos los mensajes del chat
      loadChatMessages();
      setupEchoAndPusher();
      initializeChatSidebar();
    })
    .catch(error => {
      console.error('Error al verificar la autenticación:', error);
    });
  
  // Función para cargar los mensajes del chat
  function loadChatMessages() {
    fetch('/chat/messages')
      .then(response => response.json())
      .then(messages => {
        let chatContent = document.getElementById('chatContent');
        
        const roleColors = {
          'super_admin': '#FFBF00',  // Ámbar
          'panel_user': '#00AEEF',   // Azul claro
          'Vendedor': '#9f86c0',     // Violeta
          'Analista': '#A3E635',     // Lima
          'Administrador': '#FFBF00',// Ámbar
          'Logistica': '#14B8A6',    // Teal
        };

        // Mostrar los mensajes en el chat
        messages.forEach(message => {
          const roleColor = roleColors[message.role] || '#000000'; // Por defecto color negro si el rol no está definido
          chatContent.innerHTML += `
            <p style="color: ${roleColor};">
              <strong>${message.sender}:</strong> ${message.message} 
              <span style="color: gray; font-size: 0.85em;">(${message.created_at})</span>
            </p>`;
        });

        chatContent.scrollTop = chatContent.scrollHeight; // Desplazarse al final
      })
      .catch(error => {
        console.error('Error al cargar los mensajes:', error);
      });
  }

  // Función para configurar Echo y Pusher
  function setupEchoAndPusher() {
    if (typeof Pusher === 'undefined' || typeof Echo === 'undefined') {
      console.error('Pusher o Echo no están definidos. Asegúrate de que los scripts se cargan correctamente desde resources/js/custom-chat.js');
      return;
    }

    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '54e2d06004a1969229bf',
      cluster: 'us2',
      forceTLS: true
    });

    window.Echo.channel('new-public-chat')
      .listen('.message.sent', (e) => {
        const roleColors = {
          'super_admin': '#FFBF00',
          'panel_user': '#00AEEF',
          'Vendedor': '#9f86c0',
          'Analista': '#A3E635',
          'Administrador': '#FFBF00',
          'Logistica': '#14B8A6',
        };

        let chatContent = document.getElementById('chatContent');
        const roleColor = roleColors[e.role] || '#000000';

        chatContent.innerHTML += `
          <p style="color: ${roleColor};">
            <strong>${e.sender || 'Usuario'}:</strong> ${e.message} 
            <span style="color: gray; font-size: 0.85em;">(${e.created_at})</span>
          </p>`;
          
        chatContent.scrollTop = chatContent.scrollHeight;
      });
  }

  // Configuración de la interfaz de chat (botón flotante y sidebar)
  function initializeChatSidebar() {
    let chatToggleButton = document.createElement('div');
    chatToggleButton.id = 'chatToggleButton';
    chatToggleButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd" d="M4.5 5.25A2.25 2.25 0 016.75 3h10.5A2.25 2.25 0 0119.5 5.25v8.5a2.25 2.25 0 01-2.25 2.25H9l-4.72 3.54a.75.75 0 01-1.28-.58V5.25zm3.75 4a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5H8.25zm0 3.5a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5z" clip-rule="evenodd"/>
      </svg>
    `;
    document.body.appendChild(chatToggleButton);

    let chatSidebar = document.createElement('div');
    chatSidebar.id = 'chatSidebar';
    chatSidebar.innerHTML = `
      <h3>Chat empresa</h3>
      <div id="chatContent" style="flex-grow: 1; overflow-y: auto;"></div>
      <div id="chatInputContainer" style="display: flex; padding-top: 10px;">
        <input type="text" id="messageInput" placeholder="Escribe un mensaje..." style="flex-grow: 1; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        <button onclick="sendMessage()">Enviar</button>
      </div>
    `;
    document.body.appendChild(chatSidebar);

    let mainContent = document.querySelector('.filament-main-content');
    chatToggleButton.addEventListener('click', function () {
      chatSidebar.classList.toggle('open');
      if (mainContent) {
        mainContent.classList.toggle('shifted');
      }
      chatToggleButton.classList.toggle('move-left');
    });

    let messageInput = document.getElementById('messageInput');
    messageInput.addEventListener("keydown", function (event) {
      if (event.key === "Enter") {
        event.preventDefault();
        sendMessage();
      }
    });
  }

  // Función para enviar un mensaje
  function sendMessage() {
    let input = document.getElementById('messageInput');
    if (input.value.trim() !== '') {
      let message = input.value.trim();
      input.value = '';
      fetch('/chat/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
      })
      .then(response => response.json())
      .then(data => {
        console.log('Mensaje enviado exitosamente:', data);
      })
      .catch(error => {
        console.error('Error al enviar el mensaje:', error);
      });
    }
  }
});
