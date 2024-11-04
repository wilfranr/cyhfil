document.addEventListener("DOMContentLoaded", function () {
  const loadChatMessages = () => {
    fetch('/chat/messages')
      .then(response => {
        if (!response.ok) {
          if (response.status === 401) {
            // Redirigir al login si no está autenticado
            window.location.href = '/login';
            return;
          }
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(messages => {
        if (!messages) return;
        
        let chatContent = document.getElementById('chatContent');
        if (!chatContent) return;

        // Define los colores según los roles
        const roleColors = {
          'super_admin': '#FFBF00',  // Ámbar
          'panel_user': '#00AEEF',   // Azul claro
          'Vendedor': '#9f86c0',     // Violeta
          'Analista': '#A3E635',     // Lima
          'Administrador': '#FFBF00', // Ámbar
          'Logistica': '#14B8A6',    // Teal
        };

        // Limpiar el contenido existente
        chatContent.innerHTML = '';

        // Mostrar los mensajes en el chat
        messages.forEach(message => {
          const roleColor = roleColors[message.role] || '#000000';
          const messageElement = document.createElement('p');
          messageElement.style.color = roleColor;
          messageElement.innerHTML = `
            <strong>${message.sender}:</strong> ${message.message} 
            <span style="color: gray; font-size: 0.85em;">(${message.created_at})</span>
          `;
          chatContent.appendChild(messageElement);
        });

        // Desplazarse al final del chat
        chatContent.scrollTop = chatContent.scrollHeight;
      })
      .catch(error => {
        console.error('Error al cargar los mensajes:', error);
        // Mostrar mensaje de error al usuario
        const errorMessage = document.createElement('p');
        errorMessage.style.color = 'red';
        errorMessage.textContent = 'Error al cargar los mensajes. Por favor, intente nuevamente.';
        document.getElementById('chatContent').appendChild(errorMessage);
      });
  };

  // Verificar autenticación antes de inicializar el chat
  fetch('/auth-status')
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(data => {
      if (!data.isAuthenticated) {
        window.location.href = '/login';
        return;
      }
      
      // Inicializar el chat solo si el usuario está autenticado
      initializeChat();
      loadChatMessages();
    })
    .catch(error => {
      console.error('Error al verificar la autenticación:', error);
      window.location.href = '/login';
    });

  function initializeChat() {
    if (typeof Pusher === 'undefined' || typeof Echo === 'undefined') {
      console.error('Pusher o Echo no están definidos.');
      return;
    }

    // Configurar Echo y Pusher
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '54e2d06004a1969229bf',
      cluster: 'us2',
      forceTLS: true
    });

    // Crear interfaz del chat
    createChatInterface();

    // Configurar websockets
    setupWebsockets();
  }

  function createChatInterface() {
    // Crear el botón flotante
    let chatToggleButton = document.createElement('div');
    chatToggleButton.id = 'chatToggleButton';
    chatToggleButton.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd" d="M4.5 5.25A2.25 2.25 0 016.75 3h10.5A2.25 2.25 0 0119.5 5.25v8.5a2.25 2.25 0 01-2.25 2.25H9l-4.72 3.54a.75.75 0 01-1.28-.58V5.25zm3.75 4a.75.75 0 000 1.5h7.5a.75.75 0 000-1.5H8.25zm0 3.5a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5z" clip-rule="evenodd"/>
      </svg>
    `;
    document.body.appendChild(chatToggleButton);

    // Crear el sidebar
    let chatSidebar = document.createElement('div');
    chatSidebar.id = 'chatSidebar';
    chatSidebar.innerHTML = `
      <h3>Chat empresa</h3>
      <div id="chatContent" style="flex-grow: 1; overflow-y: auto;"></div>
      <div id="chatInputContainer" style="display: flex; padding-top: 10px;">
        <input type="text" id="messageInput" placeholder="Escribe un mensaje..." 
               style="flex-grow: 1; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        <button onclick="sendMessage()">Enviar</button>
      </div>
    `;
    document.body.appendChild(chatSidebar);

    // Configurar eventos
    setupChatEvents(chatToggleButton, chatSidebar);
  }

  function setupChatEvents(chatToggleButton, chatSidebar) {
    let mainContent = document.querySelector('.filament-main-content');
    
    chatToggleButton.addEventListener('click', function() {
      chatSidebar.classList.toggle('open');
      if (mainContent) {
        mainContent.classList.toggle('shifted');
      }
      chatToggleButton.classList.toggle('move-left');
    });

    let messageInput = document.getElementById('messageInput');
    messageInput.addEventListener("keydown", function(event) {
      if (event.key === "Enter") {
        event.preventDefault();
        sendMessage();
      }
    });
  }

  function setupWebsockets() {
    window.Echo.channel('new-public-chat')
      .listen('.message.sent', (e) => {
        console.log('Nuevo mensaje recibido:', e);
        
        const roleColors = {
          'super_admin': '#FFBF00',
          'panel_user': '#00AEEF',
          'Vendedor': '#9f86c0',
          'Analista': '#A3E635',
          'Administrador': '#FFBF00',
          'Logistica': '#14B8A6',
        };

        let chatContent = document.getElementById('chatContent');
        let roleColor = roleColors[e.role] || '#000000';

        const messageElement = document.createElement('p');
        messageElement.style.color = roleColor;
        messageElement.innerHTML = `
          <strong>${e.sender || 'Usuario'}:</strong> ${e.message} 
          <span style="color: gray; font-size: 0.85em;">(${e.created_at})</span>
        `;
        chatContent.appendChild(messageElement);
        chatContent.scrollTop = chatContent.scrollHeight;
      });
  }
});

// Función global para enviar mensajes
window.sendMessage = function() {
  let input = document.getElementById('messageInput');
  let message = input.value.trim();
  
  if (message === '') return;
  
  input.value = '';

  fetch('/chat/send', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ message: message })
  })
    .then(response => {
      if (!response.ok) {
        if (response.status === 401) {
          window.location.href = '/login';
          return;
        }
        throw new Error('Error al enviar el mensaje');
      }
      return response.json();
    })
    .then(data => {
      console.log('Mensaje enviado exitosamente:', data);
    })
    .catch(error => {
      console.error('Error:', error);
      // Mostrar mensaje de error al usuario
      const errorMessage = document.createElement('p');
      errorMessage.style.color = 'red';
      errorMessage.textContent = 'Error al enviar el mensaje. Por favor, intente nuevamente.';
      document.getElementById('chatContent').appendChild(errorMessage);
    });
};