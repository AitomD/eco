// Carrinho de compras - Funcionalidades JavaScript

class CarrinhoManager {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateCartCount();
    }

    bindEvents() {
        // Botões de adicionar ao carrinho (para usar em outras páginas)
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-add-cart') || e.target.closest('.btn-add-cart')) {
                e.preventDefault();
                this.handleAddToCart(e.target.closest('.btn-add-cart'));
            }
        });

        // Eventos específicos da página do carrinho
        // Comentado para evitar conflito com o JavaScript da página carrinho.php
        // if (window.location.href.includes('carrinho')) {
        //     this.bindCartPageEvents();
        // }
    }

    bindCartPageEvents() {
        // Aumentar quantidade
        document.querySelectorAll('.btn-aumentar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const itemRow = e.target.closest('.item-carrinho');
                const quantidadeInput = itemRow.querySelector('.quantidade-input');
                const novaQuantidade = parseInt(quantidadeInput.value) + 1;
                
                if (novaQuantidade <= 99) {
                    quantidadeInput.value = novaQuantidade;
                    this.updateItemQuantity(itemRow.dataset.id, novaQuantidade);
                }
            });
        });

        // Diminuir quantidade
        document.querySelectorAll('.btn-diminuir').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const itemRow = e.target.closest('.item-carrinho');
                const quantidadeInput = itemRow.querySelector('.quantidade-input');
                const novaQuantidade = parseInt(quantidadeInput.value) - 1;
                
                if (novaQuantidade >= 1) {
                    quantidadeInput.value = novaQuantidade;
                    this.updateItemQuantity(itemRow.dataset.id, novaQuantidade);
                }
            });
        });

        // Mudança direta no input de quantidade
        document.querySelectorAll('.quantidade-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const itemRow = e.target.closest('.item-carrinho');
                const quantidade = parseInt(e.target.value);
                
                if (quantidade >= 1 && quantidade <= 99) {
                    this.updateItemQuantity(itemRow.dataset.id, quantidade);
                } else {
                    e.target.value = 1;
                    this.updateItemQuantity(itemRow.dataset.id, 1);
                }
            });
        });

        // Remover item
        document.querySelectorAll('.btn-remover').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const itemRow = e.target.closest('.item-carrinho');
                const itemId = itemRow.dataset.id;
                
                if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
                    this.removeItem(itemId);
                }
            });
        });

        // Finalizar compra
        const finalizarBtn = document.getElementById('finalizar-compra');
        if (finalizarBtn) {
            finalizarBtn.addEventListener('click', () => {
                this.handleCheckout();
            });
        }

        // Aplicar cupom
        const aplicarCupomBtn = document.getElementById('aplicar-cupom');
        if (aplicarCupomBtn) {
            aplicarCupomBtn.addEventListener('click', () => {
                this.handleCoupon();
            });
        }
    }

    handleAddToCart(button) {
        // Obter dados do produto
        const productCard = button.closest('.card, .product-card');
        
        // Tentar obter dados dos atributos data- primeiro
        let productId = button.dataset.id || productCard.dataset.id || Math.random().toString(36).substr(2, 9);
        let productName = button.dataset.nome || productCard.querySelector('.card-title, h6').textContent.trim();
        let productPrice = button.dataset.preco || this.extractPrice(productCard.querySelector('.card-price, .price'));
        let productImage = button.dataset.imagem || productCard.querySelector('img').src;

        // Criar formulário para enviar dados
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?url=carrinho';
        form.style.display = 'none';

        const fields = {
            acao: 'adicionar',
            id: productId,
            nome: productName,
            preco: productPrice,
            imagem: productImage,
            quantidade: 1
        };

        Object.keys(fields).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();

        // Feedback visual
        this.showAddToCartFeedback(button);
    }

    extractPrice(element) {
        if (!element) return 0;
        
        let priceText = element.textContent;
        
        // Se não encontrou preço, tenta procurar em outros elementos
        if (!priceText || !priceText.includes('R$')) {
            const priceElements = element.closest('.card').querySelectorAll('.card-text');
            for (let el of priceElements) {
                if (el.textContent.includes('R$') || el.textContent.includes('Preço:')) {
                    priceText = el.textContent;
                    break;
                }
            }
        }
        
        // Extrair apenas os números e formatação de preço
        priceText = priceText
            .replace(/[^\d,\.]/g, '')  // Remove tudo exceto dígitos, vírgulas e pontos
            .replace(/\./g, '')        // Remove pontos de milhares
            .replace(',', '.');        // Substitui vírgula por ponto decimal
            
        return parseFloat(priceText) || 0;
    }

    showAddToCartFeedback(button) {
        const originalText = button.innerHTML;
        const originalClass = button.className;
        
        button.innerHTML = '<i class="bi bi-check"></i> Adicionado!';
        button.className = button.className.replace('btn-product', 'btn-success');
        button.disabled = true;

        setTimeout(() => {
            button.innerHTML = originalText;
            button.className = originalClass;
            button.disabled = false;
        }, 2000);
    }

    updateItemQuantity(id, quantidade) {
        const form = document.getElementById('form-atualizar');
        if (form) {
            document.getElementById('update-id').value = id;
            document.getElementById('update-quantidade').value = quantidade;
            form.submit();
        }
    }

    removeItem(id) {
        const form = document.getElementById('form-remover');
        if (form) {
            document.getElementById('remove-id').value = id;
            form.submit();
        }
    }

    handleCheckout() {
        // Verificar se está logado
        const isLoggedIn = document.body.dataset.isLoggedIn === 'true';
        
        if (!isLoggedIn) {
            if (confirm('Você precisa estar logado para finalizar a compra. Deseja fazer login agora?')) {
                window.location.href = 'index.php?url=login';
            }
        } else {
            // Redirecionar para checkout (implementar quando necessário)
            alert('Funcionalidade de checkout será implementada em breve!');
            // window.location.href = 'index.php?url=checkout';
        }
    }

    handleCoupon() {
        const cupomInput = document.getElementById('cupom-input');
        const cupom = cupomInput.value.trim();
        
        if (cupom) {
            // Implementar validação de cupom via AJAX
            this.validateCoupon(cupom);
        } else {
            alert('Por favor, digite um código de cupom válido.');
        }
    }

    validateCoupon(cupom) {
        // Simulação de validação de cupom
        // Em produção, fazer uma requisição AJAX para validar o cupom
        const cuponsValidos = ['DESC10', 'PROMO20', 'WELCOME15'];
        
        if (cuponsValidos.includes(cupom.toUpperCase())) {
            alert(`Cupom ${cupom} aplicado com sucesso!`);
            // Atualizar total com desconto
        } else {
            alert('Cupom inválido ou expirado.');
        }
    }

    updateCartCount() {
        // Atualizar contador do carrinho no navbar será feito pelo PHP na próxima requisição
        // Esta função pode ser expandida no futuro para atualizações em tempo real via AJAX
    }

    // Método para adicionar produto de forma programática
    static addProduct(id, nome, preco, imagem, quantidade = 1) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?url=carrinho';
        form.style.display = 'none';

        const fields = { acao: 'adicionar', id, nome, preco, imagem, quantidade };

        Object.keys(fields).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
}

// Inicializar o gerenciador do carrinho quando a página carregar
document.addEventListener('DOMContentLoaded', () => {
    new CarrinhoManager();
});

// Exportar para uso global
window.CarrinhoManager = CarrinhoManager;