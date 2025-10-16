// Espera o HTML carregar completamente antes de executar o código
document.addEventListener('DOMContentLoaded', function () {

    const hamburgerButton = document.getElementById('hamburger-button');

    
    const navbarContent = document.getElementById('navbarSupportedContent');

    
    if (hamburgerButton && navbarContent) {

        hamburgerButton.addEventListener('click', function () {
            
           
            navbarContent.classList.toggle('show');
            
        });
    }
});