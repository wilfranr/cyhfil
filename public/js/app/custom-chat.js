document.addEventListener("DOMContentLoaded", function () {
  // Primero verificamos la autenticación
  checkAuthAndInitialize();

  function checkAuthAndInitialize() {
    fetch('/auth-status')
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la verificación de autenticación');
        }
        return response.json();
      })
      .then(data => {
        console.log('Estado de autenticación:', data);
        
        if (data.isAuthenticated === true) {
          // Si está autenticado, iniciamos el chat
          console.log('Usuario autenticado, inicializando chat...');
          initializeChat();
          loadChatMessages();
        } else {
          console.log('Usuario no autenticado, redirigiendo...');
          window.location.href = '/login';
        }
      })
      .catch(error => {
        console.error('Error en la verificación de autenticación:', error);
        // Opcional: Mostrar mensaje de error al usuario
        alert('Error al verificar la autenticación. Por favor, recarga la página.');
      });
  }

  function initializeChat() {
    // Verificar que Pusher y Echo estén disponibles
    if (typeof Pusher === 'undefined' || typeof Echo === 'undefined') {
      console.error('Pusher o Echo no están definidos.');
      return;
    }

    // Configurar Echo
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '54e2d06004a1969229bf',
      cluster: 'us2',
      forceTLS: true
    });

    // Crear la interfaz del chat
    createChatInterface();

    // Configurar los websockets
    setupWebsockets();
  }

  function loadChatMessages() {
    fetch('/chat/messages')
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al cargar los mensajes');
        }
        return response.json();
      })
      .then(messages => {
        if (!messages) return;
        
        const chatContent = document.getElementById('chatContent');
        if (!chatContent) {
          console.error('No se encontró el elemento chatContent');
          return;
        }

        // Limpiar mensajes existentes
        chatContent.innerHTML = '';

        // Renderizar los mensajes
        renderMessages(messages, chatContent);
      })
      .catch(error => {
        console.error('Error al cargar los mensajes:', error);
        showErrorMessage('Error al cargar los mensajes.');
      });
  }

  function renderMessages(messages, container) {
    const roleColors = {
      'super_admin': '#FFBF00',
      'panel_user': '#00AEEF',
      'Vendedor': '#9f86c0',
      'Analista': '#A3E635',
      'Administrador': '#FFBF00',
      'Logistica': '#14B8A6',
    };

    messages.forEach(message => {
      const roleColor = roleColors[message.role] || '#000000';
      const messageElement = document.createElement('p');
      messageElement.style.color = roleColor;
      messageElement.innerHTML = `
        <strong>${message.sender}:</strong> ${message.message} 
        <span style="color: gray; font-size: 0.85em;">(${message.created_at})</span>
      `;
      container.appendChild(messageElement);
    });

    container.scrollTop = container.scrollHeight;
  }

  function showErrorMessage(message) {
    const chatContent = document.getElementById('chatContent');
    if (chatContent) {
      const errorElement = document.createElement('p');
      errorElement.style.color = 'red';
      errorElement.textContent = message;
      chatContent.appendChild(errorElement);
    }
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
  const input = document.getElementById('messageInput');
  const message = input.value.trim();
  
  if (!message) return;
  
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (!csrfToken) {
    console.error('No se encontró el token CSRF');
    return;
  }

  input.value = '';

  fetch('/chat/send', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({ message })
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Error al enviar el mensaje');
      }
      return response.json();
    })
    .then(data => {
      console.log('Mensaje enviado exitosamente:', data);
    })
    .catch(error => {
      console.error('Error:', error);
      showErrorMessage('Error al enviar el mensaje. Por favor, intente nuevamente.');
      // Restaurar el mensaje en el input en caso de error
      input.value = message;
    });
};