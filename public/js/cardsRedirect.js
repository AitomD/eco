/**
 * cardsRedirect.js
 * Script para redirecionar ao clicar nos cards da home para a página de produtos
 * com filtro aplicado baseado no card clicado
 */

document.addEventListener('DOMContentLoaded', function () {
    // Mapear os cards para suas categorias/filtros
    // Números correspondem aos data-categoria em produto.php
    const cardFilterMap = {
        'card1': { nome: 'Celulares', categoria: '3' },      // Smartphones
        'card2': { nome: 'PC Desktop', categoria: '1' },     // Computadores Desktop
        'card3': { nome: 'Notebooks', categoria: '2' }       // Notebooks
    };

    // Buscar todos os cards com imagem
    const cards = document.querySelectorAll('.card.text-bg-dark');

    cards.forEach((card, index) => {
        // Identificar qual card é (1, 2 ou 3)
        const cardNumber = index + 1;
        const cardKey = `card${cardNumber}`;
        const cardData = cardFilterMap[cardKey];

        if (cardData) {
            // Tornar o card clicável
            card.style.cursor = 'pointer';
            card.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';

            // Adicionar hover effect
            card.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 16px rgba(97, 0, 148, 0.3)';
            });

            card.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });

            // Evento de clique
            card.addEventListener('click', function () {
                // Redirecionar para página de produtos com filtro de categoria
                const url = `index.php?url=produto&categoria=${cardData.categoria}`;
                window.location.href = url;
            });
        }
    });
});


