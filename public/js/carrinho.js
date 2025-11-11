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
        // Botões de aumentar quantidade
        document.querySelectorAll('.btn-aumentar').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const itemRow = e.target.closest('.item-carrinho');
                if (!itemRow) return;
                
                const quantidadeInput = itemRow.querySelector('.quantidade-input');
                const novaQuantidade = parseInt(quantidadeInput.value) + 1;
                
                if (novaQuantidade <= 99) {
                    quantidadeInput.value = novaQuantidade;
                    this.updateItemQuantity(itemRow.dataset.id, novaQuantidade);
                }
            });
        });

        // Botões de diminuir quantidade
        document.querySelectorAll('.btn-diminuir').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const itemRow = e.target.closest('.item-carrinho');
                if (!itemRow) return;
                
                const quantidadeInput = itemRow.querySelector('.quantidade-input');
                const novaQuantidade = parseInt(quantidadeInput.value) - 1;
                
                if (novaQuantidade >= 1) {
                    quantidadeInput.value = novaQuantidade;
                    this.updateItemQuantity(itemRow.dataset.id, novaQuantidade);
                } else if (novaQuantidade === 0) {
                    // Se quantidade for 0, remover item
                    if (confirm('Deseja remover este item do carrinho?')) {
                        this.removeItem(itemRow.dataset.id);
                    } else {
                        quantidadeInput.value = 1;
                    }
                }
            });
        });

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

        // Botões de remover item
        document.querySelectorAll('.btn-remover').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const itemRow = e.target.closest('.item-carrinho');
                if (!itemRow) return;
                
                const itemId = itemRow.dataset.id;
                const itemNome = itemRow.querySelector('.item-nome')?.textContent || 'este item';
                
                if (confirm(`Tem certeza que deseja remover "${itemNome}" do carrinho?`)) {
                    this.removeItem(itemId);
                }
            });
        });

        // Botão de limpar carrinho
        const limparBtn = document.getElementById('limpar-carrinho');
        if (limparBtn) {
            limparBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
                    this.clearCart();
                }
            });
        }

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
     * Atualiza quantidade de um item
     * Chama CarrinhoController::atualizarQuantidade()
     */
    updateItemQuantity(id, quantidade) {
        const form = document.getElementById('form-atualizar');
        if (!form) {
            console.error('Formulário de atualização não encontrado');
            return;
        }

        const idInput = document.getElementById('update-id');
        const quantidadeInput = document.getElementById('update-quantidade');

        if (idInput && quantidadeInput) {
            idInput.value = id;
            quantidadeInput.value = quantidade;
            
            // Adicionar feedback visual
            const itemRow = document.querySelector(`[data-id="${id}"]`);
            if (itemRow) {
                itemRow.style.opacity = '0.6';
            }
            
            form.submit();
        }
    }

    /**
     * Remove um item do carrinho
     * Chama CarrinhoController::removerDoCarrinho()
     */
    removeItem(id) {
        const form = document.getElementById('form-remover');
        if (!form) {
            console.error('Formulário de remoção não encontrado');
            return;
        }

        const idInput = document.getElementById('remove-id');
        if (idInput) {
            idInput.value = id;
            
            // Adicionar animação de saída
            const itemRow = document.querySelector(`[data-id="${id}"]`);
            if (itemRow) {
                itemRow.style.transition = 'all 0.3s ease';
                itemRow.style.opacity = '0';
                itemRow.style.transform = 'translateX(-20px)';
            }
            
            setTimeout(() => {
                form.submit();
            }, 300);
        }
    }

    /**
     * Limpa todo o carrinho
     * Chama CarrinhoController::limparCarrinho()
     */
    clearCart() {
        const form = this.createForm({ acao: 'limpar' });
        document.body.appendChild(form);
        form.submit();
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
            // Redirecionar para página de checkout
            window.location.href = 'index.php?url=checkout';
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
     * Valida cupom
     * Em produção, fazer requisição AJAX para validar no servidor
     */
    async validateCoupon(cupom) {
        // Simulação de validação (substituir por AJAX real em produção)
        return new Promise((resolve) => {
            setTimeout(() => {
                const cuponsValidos = {
                    'DESC10': { discount: 10, type: 'percentage' },
                    'PROMO20': { discount: 20, type: 'percentage' },
                    'WELCOME15': { discount: 15, type: 'percentage' },
                    'FRETE': { discount: 0, type: 'free_shipping' }
                };

                if (cuponsValidos[cupom]) {
                    resolve({
                        valid: true,
                        discount: cuponsValidos[cupom].discount,
                        type: cuponsValidos[cupom].type
                    });
                } else {
                    resolve({
                        valid: false,
                        message: 'Cupom inválido ou expirado.'
                    });
                }
            }, 500);
        });

        /* Implementação real com AJAX:
        try {
            const response = await fetch('index.php?url=api/validar-cupom', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cupom })
            });
            return await response.json();
        } catch (error) {
            console.error('Erro ao validar cupom:', error);
            return { valid: false, message: 'Erro ao validar cupom.' };
        }
        */
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

    /**
     * Método estático para adicionar produto programaticamente
     * Usa CarrinhoController::adicionarAoCarrinho()
     */
    static addProduct(id, nome, preco, imagem = '', quantidade = 1) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?url=carrinho';
        form.style.display = 'none';

        const fields = {
            acao: 'adicionar',
            id: id,
            nome: nome,
            preco: preco,
            imagem: imagem,
            quantidade: quantidade
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
    }
}

/**
 * ====================================================================
 * FUNÇÃO AJAX PARA ADICIONAR PRODUTO (Alternativa moderna)
 * ====================================================================
 * NOTA: Requer criação de api_carrinho.php que use CarrinhoController
 */
async function adicionarProdutoAoCarrinho(produto) {
    // Validação
    if (!produto || !produto.id || !produto.nome || !produto.preco) {
        console.error('Dados do produto incompletos:', produto);
        alert('Erro: Dados do produto incompletos.');
        return { success: false, message: 'Dados incompletos' };
    }

    // Preparar dados no formato esperado pelo CarrinhoController
    const formData = new FormData();
    formData.append('acao', 'adicionar');
    formData.append('id', produto.id);
    formData.append('nome', produto.nome);
    formData.append('preco', produto.preco);
    formData.append('imagem', produto.imagem || '');
    formData.append('quantidade', produto.quantidade || 1);

    try {
        // Enviar requisição para API que usa CarrinhoController
        const response = await fetch('api_carrinho.php', {
            method: 'POST',
            body: formData
        });

        // Verificar se a resposta é JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Resposta do servidor não é JSON');
        }

        const result = await response.json();

        // Processar resultado
        if (result.success) {
            // Atualizar contador do carrinho
            if (result.totalItens !== undefined) {
                const cartBadge = document.querySelector('.cart-count');
                if (cartBadge) {
                    cartBadge.textContent = result.totalItens;
                    cartBadge.classList.add('pulse');
                    setTimeout(() => cartBadge.classList.remove('pulse'), 500);
                }
            }

            // Mostrar notificação de sucesso
            if (window.carrinhoManager) {
                window.carrinhoManager.showNotification(
                    `${produto.nome} adicionado ao carrinho!`, 
                    'success'
                );
            } else {
                alert('Produto adicionado ao carrinho!');
            }

            return result;
        } else {
            throw new Error(result.message || 'Erro ao adicionar produto');
        }

    } catch (error) {
        console.error('Erro na requisição AJAX:', error);
        
        // Mostrar erro ao usuário
        const errorMessage = error.message || 'Erro de rede. Tente novamente.';
        if (window.carrinhoManager) {
            window.carrinhoManager.showNotification(errorMessage, 'error');
        } else {
            alert(errorMessage);
        }

        return { success: false, message: errorMessage };
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