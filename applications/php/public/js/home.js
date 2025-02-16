

// Função para abrir a janela de mensagens de um usuário específico
function openMessageWindow(userId) {
    document.getElementById('to_user_id').value = userId;
    document.getElementById('message-window').style.display = 'flex';
    loadMessages(userId);
}

// Função para carregar as mensagens do usuário selecionado
function loadMessages(userId) {
    const messageList = document.getElementById('message-list');
    messageList.innerHTML = ''; // Limpa a lista de mensagens antes de carregar

    fetch(`load_messages.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            data.forEach(message => {
                const messageItem = document.createElement('div');
                messageItem.classList.add('message-item');
                const photo = document.createElement('img');
                photo.src = `uploads/${message.foto}`;
                photo.classList.add('message-photo');
                messageItem.appendChild(photo);

                const content = document.createElement('div');
                content.classList.add('message-content');
                const userName = document.createElement('span');
                userName.classList.add('message-user-name');
                userName.innerText = message.nome;
                content.appendChild(userName);

                const messageContent = document.createElement('p');
                messageContent.innerText = message.message;
                content.appendChild(messageContent);

                const time = document.createElement('span');
                time.classList.add('message-time');
                time.innerText = message.formatted_time; 
                content.appendChild(time);

                messageItem.appendChild(content);
                messageList.appendChild(messageItem);
            });
        })
        .catch(error => console.error('Erro ao carregar mensagens:', error));
}

// Enviar mensagem via AJAX
document.querySelector('.message-input').addEventListener('submit', function(event) {
    event.preventDefault();

    const messageInput = this.querySelector('input[name="message"]');
    const toUserId = document.getElementById('to_user_id').value;

    fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            message: messageInput.value,
            to_user_id: toUserId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            messageInput.value = '';
            loadMessages(toUserId);
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Erro ao enviar mensagem:', error));
});


function closeMessageWindow() {
    document.getElementById('message-window').style.display = 'none';
}

function closeMessageWindow() {
    const messageWindow = document.getElementById('message-window');
    messageWindow.style.animation = 'slideOut 0.8s ease-out'; // Defina a animação
    setTimeout(() => {
        messageWindow.style.display = 'none';
        messageWindow.style.animation = ''; // Limpe a animação
    }, 300); // Tempo igual ao da animação
}
