function goBack() {
    let lastPage = sessionStorage.getItem("lastPage");

    // Se houver uma página salva e for diferente da atual, volta para ela
    if (lastPage && lastPage !== window.location.href) {
        sessionStorage.removeItem("lastPage"); // Remove para evitar loop
        window.location.href = lastPage;
    } else {
        window.location.href = "home.php"; // Se não houver histórico, vai para a home
    }
}