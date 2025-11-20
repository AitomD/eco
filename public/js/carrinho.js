/**
 * ====================================================================
 * CARRINHO DE COMPRAS - Sistema Completo de Gerenciamento
 * ====================================================================
 * Integrado com CarrinhoController.php
 * Gerencia todas as funcionalidades do carrinho de compras
 */

class CarrinhoManager {
    constructor() {
        this.carrinho = [];
        this.init();
    }

    /**
     * Inicializa o gerenciador do carrinho
     */
    init() {
        this.bindGlobalEvents();
        this.updateCartCount();
        
        // Se estiver na página do carrinho, inicializar eventos específicos
        if (this.isCartPage()) {
            this.bindCartPageEvents();
        }
    }

    /**
     * Verifica se está na página do carrinho
     */
    isCartPage() {
        return window.location.href.includes('url=carrinho') || 
               document.querySelector('.page-carrinho') !== null ||
               document.getElementById('form-atualizar') !== null;
    }

    /**
     * Vincula eventos globais (funcionam em todas as páginas)
     */
    bindGlobalEvents() {
        // Event delegation para botões de adicionar ao carrinho
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.btn-add-cart');
            if (button) {
                e.preventDefault();
                this.handleAddToCart(button);
            }
        });

        // Event delegation para botões "Comprar agora"
        document.addEventListener('click', (e) => {
            const button = e.target.closest('.btn-comprar-agora');
            if (button) {
                e.preventDefault();
                this.handleBuyNow(button);
            }
        });

        // Prevenir submissão múltipla de formulários
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && submitBtn.disabled) {
                    e.preventDefault();
                    return false;
                }
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
            });
        });
    }

    /**
     * Vincula eventos específicos da página do carrinho
     */
    bindCartPageEvents() {
      
        // Mudança direta no input de quantidade
        document.querySelectorAll('.quantidade-input').forEach(input => {
            input.addEventListener('change', (e) => {
                const itemRow = e.target.closest('.item-carrinho');
                if (!itemRow) return;
                
                let quantidade = parseInt(e.target.value);
                
                // Validação
                if (isNaN(quantidade) || quantidade < 1) {
                    quantidade = 1;
                } else if (quantidade > 99) {
                    quantidade = 99;
                }
                
                e.target.value = quantidade;
                this.updateItemQuantity(itemRow.dataset.id, quantidade);
            });

            // Prevenir caracteres não numéricos
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });


        // Botão de finalizar compra
        const finalizarBtn = document.getElementById('finalizar-compra');
        if (finalizarBtn) {
            finalizarBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleCheckout();
            });
        }

        // Sistema de cupons
        const aplicarCupomBtn = document.getElementById('aplicar-cupom');
        if (aplicarCupomBtn) {
            aplicarCupomBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleCoupon();
            });
        }

        // Permitir aplicar cupom com Enter
        const cupomInput = document.getElementById('cupom-input');
        if (cupomInput) {
            cupomInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.handleCoupon();
                }
            });
        }

        // Botão continuar comprando
        const continuarBtn = document.getElementById('continuar-comprando');
        if (continuarBtn) {
            continuarBtn.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = 'index.php?url=produtos';
            });
        }
    }

    /**
     * Adiciona produto ao carrinho
     * Integrado com CarrinhoController::adicionarAoCarrinho()
     * @param {HTMLElement} button - Botão que foi clicado
     */
    handleAddToCart(button) {
        // Prevenir cliques múltiplos
        if (button.disabled) return;

        // Extrair dados do produto dos atributos data-*
        const id = button.dataset.id;
        const nome = button.dataset.nome;
        const preco = button.dataset.preco;
        const imagem = button.dataset.imagem || '';

        // Validação rigorosa
        if (!id || !nome || !preco) {
            console.error('Erro: Dados do produto incompletos', {
                id, nome, preco, button
            });
            this.showNotification('Erro ao adicionar produto. Dados incompletos.', 'error');
            return;
        }

        // Validar preço
        const precoNum = parseFloat(preco.toString().replace(',', '.'));
        if (isNaN(precoNum) || precoNum <= 0) {
            console.error('Erro: Preço inválido', preco);
            this.showNotification('Erro ao adicionar produto. Preço inválido.', 'error');
            return;
        }

        // Criar e enviar formulário (será processado por CarrinhoController::processarAcao())
        const form = this.createForm({
            acao: 'adicionar',
            id: id,
            nome: nome,
            preco: preco,
            imagem: imagem,
            quantidade: 1
        });

        // Feedback visual antes de enviar
        this.showAddToCartFeedback(button);
        this.showNotification('Produto adicionado ao carrinho!', 'success');

        // Enviar formulário
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Cria um formulário para envio de dados
     * Envia para index.php?url=carrinho que chama CarrinhoController::processarAcao()
     */
    createForm(data) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?url=carrinho';
        form.style.display = 'none';

        Object.keys(data).forEach(key => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        });

        return form;
    }

    /**
     * Mostra feedback visual ao adicionar produto
     */
    showAddToCartFeedback(button) {
        const originalHTML = button.innerHTML;
        const originalClass = button.className;
        
        // Mudar aparência do botão
        button.innerHTML = '<i class="bi bi-check-circle"></i> Adicionado!';
        button.classList.remove('btn-product', 'btn-primary', 'btn-outline-primary');
        button.classList.add('btn-success');
        button.disabled = true;

        // Restaurar após 2 segundos
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.className = originalClass;
            button.disabled = false;
        }, 2000);
    }

    /**
     * Lida com o botão "Comprar agora"
     * Adiciona ao carrinho e redireciona para checkout
     * @param {HTMLElement} button - Botão que foi clicado
     */
    handleBuyNow(button) {
        // Prevenir cliques múltiplos
        if (button.disabled) return;

        // Extrair dados do produto
        const id = button.dataset.id;
        const nome = button.dataset.nome;
        const preco = button.dataset.preco;
        const imagem = button.dataset.imagem || '';

        console.log('Dados do produto para compra:', { id, nome, preco, imagem });

        // Validação
        if (!id || !nome || !preco) {
            console.error('Erro: Dados do produto incompletos para compra', {
                id, nome, preco, button
            });
            this.showNotification('Erro ao processar compra. Dados incompletos.', 'error');
            return;
        }

        // Validar preço
        const precoNum = parseFloat(preco.toString().replace(',', '.'));
        if (isNaN(precoNum) || precoNum <= 0) {
            console.error('Erro: Preço inválido para compra', preco);
            this.showNotification('Erro ao processar compra. Preço inválido.', 'error');
            return;
        }

        // Feedback visual
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-clock"></i> Processando...';

        // Dados para adicionar ao carrinho
        const data = {
            acao: 'adicionar',
            id: id,
            nome: nome,
            preco: preco,
            imagem: imagem,
            quantidade: 1,
            comprar_agora: 'true'
        };

        // Enviar para carrinho e depois redirecionar
        this.sendToCartAndRedirect(data, button, originalText);
    }

    /**
     * Envia para carrinho e redireciona para checkout
     */
    sendToCartAndRedirect(data, button, originalText) {
        // Criar formulário para envio
        const form = this.createForm(data);
        
        this.showNotification('Produto adicionado! Redirecionando...', 'success');
        
        // Enviar formulário
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Restaura o botão ao estado original
     */
    resetButton(button, originalText) {
        button.disabled = false;
        button.innerHTML = originalText;
    }


    /**
     * Processa checkout
     */
    handleCheckout() {
        // Verificar se há itens no carrinho
        const itensCarrinho = document.querySelectorAll('.item-carrinho');
        if (itensCarrinho.length === 0) {
            this.showNotification('Seu carrinho está vazio!', 'warning');
            return;
        }

        // Verificar se está logado
        const isLoggedIn = document.body.dataset.isLoggedIn === 'true' || 
                          document.body.dataset.loggedin === 'true' ||
                          sessionStorage.getItem('isLoggedIn') === 'true';
        
        if (!isLoggedIn) {
            const confirmar = confirm('Você precisa estar logado para finalizar a compra.\n\nDeseja fazer login agora?');
            if (confirmar) {
                // Salvar URL de retorno
                sessionStorage.setItem('returnUrl', window.location.href);
                window.location.href = 'index.php?url=login';
            }
        } else {
            // Redirecionar para página de retirada
            window.location.href = 'index.php?url=paginaRetirada';
        }
    }

    /**
     * Processa aplicação de cupom
     */
    handleCoupon() {
        const cupomInput = document.getElementById('cupom-input');
        if (!cupomInput) return;

        const cupom = cupomInput.value.trim().toUpperCase();
        
        if (!cupom) {
            this.showNotification('Por favor, digite um código de cupom.', 'warning');
            cupomInput.focus();
            return;
        }

        // Desabilitar botão temporariamente
        const btn = document.getElementById('aplicar-cupom');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Validando...';
        }

        // Validar cupom
        this.validateCoupon(cupom)
            .then(result => {
                if (result.valid) {
                    this.applyCoupon(cupom, result.discount);
                } else {
                    this.showNotification(result.message || 'Cupom inválido ou expirado.', 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao validar cupom:', error);
                this.showNotification('Erro ao validar cupom. Tente novamente.', 'error');
            })
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-tag"></i> Aplicar';
                }
            });
    }
 

    /**
     * Aplica cupom ao carrinho
     * Pode ser expandido para integrar com CuponsCarrinhoController
     */
    applyCoupon(cupom, discount) {
        // Criar formulário para aplicar cupom
        const form = this.createForm({
            acao: 'aplicar_cupom',
            cupom: cupom,
            desconto: discount
        });

        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Atualiza contador do carrinho no navbar
     * O contador é atualizado pelo PHP através de CarrinhoController::contarItens()
     */
    updateCartCount() {
        const cartBadge = document.querySelector('.cart-count');
        if (cartBadge) {
            // Adicionar animação de pulse quando atualizar
            cartBadge.classList.add('pulse');
            setTimeout(() => {
                cartBadge.classList.remove('pulse');
            }, 500);
        }
    }

    /**
     * Mostra notificação para o usuário
     */
    showNotification(message, type = 'info') {
        // Tipos: 'success', 'error', 'warning', 'info'
        
        // Remover notificações anteriores
        const oldNotifications = document.querySelectorAll('.cart-notification');
        oldNotifications.forEach(n => n.remove());

        // Criar notificação
        const notification = document.createElement('div');
        notification.className = `cart-notification alert alert-${type === 'error' ? 'danger' : type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease-out;
        `;
        
        // Adicionar ícone baseado no tipo
        const icons = {
            success: 'bi-check-circle',
            error: 'bi-exclamation-circle',
            warning: 'bi-exclamation-triangle',
            info: 'bi-info-circle'
        };
        
        notification.innerHTML = `
            <i class="bi ${icons[type]} me-2"></i>
            <span>${message}</span>
        `;

        // Adicionar à página
        document.body.appendChild(notification);

        // Remover após 4 segundos
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

  
}

/**
 * ====================================================================
 * INICIALIZAÇÃO E EXPORTAÇÃO
 * ====================================================================
 */

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.carrinhoManager = new CarrinhoManager();
    console.log('✓ CarrinhoManager inicializado com sucesso!');
    console.log('✓ Integrado com CarrinhoController.php');
});

// Exportar para uso global
window.CarrinhoManager = CarrinhoManager;
window.adicionarProdutoAoCarrinho = adicionarProdutoAoCarrinho;

/**
 * ====================================================================
 * ESTILOS PARA ANIMAÇÕES
 * ====================================================================
 */
if (!document.getElementById('cart-notification-styles')) {
    const style = document.createElement('style');
    style.id = 'cart-notification-styles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        .cart-count.pulse {
            animation: pulse 0.5s ease-in-out;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.3);
            }
        }

        .item-carrinho {
            transition: all 0.3s ease;
        }

        .cart-notification {
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .cart-notification i {
            font-size: 1.2em;
        }
    `;
    document.head.appendChild(style);
}