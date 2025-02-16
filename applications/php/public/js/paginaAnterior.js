    // Obtém a página anterior salva
    let lastPage = sessionStorage.getItem("lastPage");

    // Se a página anterior for diferente da atual, atualiza o armazenamento
    if (lastPage !== window.location.href) {
        sessionStorage.setItem("lastPage", window.location.href);
    }
