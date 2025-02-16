

//modal post
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editPostForm'); // Formulário de edição
    const modal = document.getElementById('editPostModal'); // Modal de edição
    const modalContent = document.querySelector('.modal-content'); // Conteúdo do modal

    // Função para abrir o modal de edição no mesmo local do clique
    function openEditModal(postId, content, event) {
        document.getElementById('postId').value = postId; // Define o ID do post
        document.getElementById('editPostContent').value = content; // Define o conteúdo do post

        // Captura a posição do clique
        const rect = event.target.getBoundingClientRect();
        const topPosition = rect.top + window.scrollY;
        const leftPosition = rect.left + window.scrollX;

        // Exibe o modal e posiciona no mesmo local do clique
        modal.style.display = 'block';
        modalContent.style.position = 'absolute';
        modalContent.style.top = `${topPosition}px`;
        modalContent.style.left = `${leftPosition}px`;
    }

    // Função para fechar o modal
    function closeEditModal() {
        modal.style.display = 'none';
    }

    // Evento para enviar os dados via AJAX ao salvar as alterações
    editForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Impede o envio tradicional

        const conteudo = document.getElementById('editPostContent').value;
        const postId = document.getElementById('postId').value;

        // Envia via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'edit_post.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('conteudo=' + encodeURIComponent(conteudo) + '&post_id=' + encodeURIComponent(postId));

        // Processa a resposta do servidor
        xhr.onload = function() {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                closeEditModal();
                setTimeout(() => location.reload(), 300);
            } else {
                alert('Erro: ' + response.error);
            }
        };
    });

    // Exemplo de uso: atribuir a função a um botão de edição
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function(event) {
            const postId = this.dataset.postId;
            const content = this.dataset.content;
            openEditModal(postId, content, event);
        });
    });
});

//modal post
function openEditModal(postId, postContent) {
    // Preenche o modal com o conteúdo da publicação
    document.getElementById('postId').value = postId;
    document.getElementById('editPostContent').value = postContent;

    // Exibe o modal
    document.getElementById('editPostModal').style.display = "block";
}

function closeEditModal() {
    // Fecha o modal
    document.getElementById('editPostModal').style.display = "none";
}



//modal comentarios 

    // Função para abrir o modal de edição do comentário
    function openEditCommentModal(commentId, content) {
        // Preenche o modal com os dados do comentário
        document.getElementById('commentId').value = commentId; // ID do comentário
        document.getElementById('editCommentContent').value = content; // Conteúdo do comentário
        document.getElementById('editCommentModal').style.display = 'block'; // Exibe o modal
    }
    
    // Função para fechar o modal de edição
    function closeEditCommentModal() {
        document.getElementById('editCommentModal').style.display = 'none';
    }
    
    
    // Função para enviar a edição do comentário via AJAX
    document.getElementById('editCommentForm').onsubmit = function(event) {
        event.preventDefault(); // Impede o envio do formulário
    
        var comentarioId = document.getElementById('commentId').value;
        var postId = document.getElementById('postId').value;
        var novoComentario = document.getElementById('editCommentContent').value;
    
        var formData = new FormData();
        formData.append('comentario_id', comentarioId);
        formData.append('post_id', postId);
        formData.append('comentario', novoComentario);
    
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'editar_comentario.php', true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Atualiza o conteúdo do comentário na página sem recarregar
                    var commentElement = document.getElementById('comment-' + comentarioId);
                    commentElement.querySelector('p').innerHTML = novoComentario.replace(/\n/g, '<br>'); // Atualiza o comentário na página
    
                    // Fecha o modal
                    closeEditCommentModal();
                } else {
                    alert(response.error);
                }
            } else {
                alert('Erro ao salvar o comentário. Tente novamente.');
            }
        };
        xhr.send(formData);
    };




    //aqui ão as reações e interações destas
    document.querySelectorAll('.reactions button').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const reactionType = this.getAttribute('data-reaction');
    
            fetch('add_reaction.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'id_post': postId,
                    'tipo': reactionType
                })
            }).then(response => response.text()).then(data => {
                console.log(data);  // Aqui você pode verificar se a resposta está correta
                location.reload();  // Atualiza a página para refletir a nova reação
            });
        });
    });