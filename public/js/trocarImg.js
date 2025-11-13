 function trocarImagemPrincipal(novaImagem, elemento) {
        // Atualiza a imagem principal
        const imagemPrincipal = document.getElementById('imagem-principal');

        // Efeito de fade
        imagemPrincipal.style.opacity = '0.5';

        setTimeout(() => {
            imagemPrincipal.src = novaImagem;
            imagemPrincipal.style.opacity = '1';
        }, 150);

        // Remove a classe 'active' de todas as miniaturas
        document.querySelectorAll('.miniatura-img').forEach(img => {
            img.classList.remove('active');
        });

        // Adiciona a classe 'active' na miniatura clicada
        elemento.classList.add('active');
    }