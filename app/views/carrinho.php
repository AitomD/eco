<?php
// Inicializar sessão se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir o controlador do carrinho
require_once __DIR__ . '/../controller/CarrinhoController.php';

// Verificar se o usuário está logado
$isLoggedIn = isset($_SESSION['user_id']);

// Obter dados do carrinho através do controlador
$itensCarrinho = CarrinhoController::getItens();
$totalCarrinho = CarrinhoController::calcularTotal();
$totalItens = CarrinhoController::contarItens();
?>

<div class="container mt-5 pt-5 carrinho-container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-white mb-4">
                <i class="bi bi-cart2 me-3"></i>Meu Carrinho
                <?php if ($totalItens > 0): ?>
                    <span class="badge bg-primary ms-2"><?= $totalItens ?> <?= $totalItens === 1 ? 'item' : 'itens' ?></span>
                <?php endif; ?>
            </h2>
        </div>
    </div>

    <?php if (empty($itensCarrinho)): ?>
        <!-- Carrinho vazio -->
        <div class="row">
            <div class="col-12">
                <div class="card border-secondary">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-cart-x text-light" style="font-size: 4rem;"></i>
                        <h4 class="text-light mt-3 mb-4">Seu carrinho está vazio</h4>
                        <p class="text-light mb-4">Adicione produtos ao carrinho para continuar suas compras</p>
                        <a href="index.php?url=home" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Continuar Comprando
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Carrinho com itens -->
        <div class="row">
            <!-- Lista de produtos -->
            <div class="col-md-8">
                <div class="card bg-dark border-secondary mb-4">
                    <div class="card-header bg-secondary">
                        <h5 class="text-white mb-0">Produtos no Carrinho</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($itensCarrinho as $item): ?>
                            <div class="row align-items-center py-3 border-bottom border-secondary item-carrinho" data-id="<?= $item['id'] ?>">
                                <div class="col-md-2">
                                    <img src="<?= htmlspecialchars($item['imagem']) ?>" 
                                         alt="<?= htmlspecialchars($item['nome']) ?>" 
                                         class="img-fluid rounded">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-white mb-1"><?= htmlspecialchars($item['nome']) ?></h6>
                                    <p class="text-muted small mb-0">Preço unitário: R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary btn-sm btn-diminuir" type="button">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control text-center quantidade-input" 
                                               value="<?= $item['quantidade'] ?>" min="1" max="99">
                                        <button class="btn btn-outline-secondary btn-sm btn-aumentar" type="button">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <p class="text-white fw-bold mb-1">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></p>
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-outline-danger btn-sm btn-remover" title="Remover item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?url=home" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-2"></i>Continuar Comprando
                    </a>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="acao" value="limpar">
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Tem certeza que deseja limpar o carrinho?')">
                            <i class="bi bi-trash me-2"></i>Limpar Carrinho
                        </button>
                    </form>
                </div>
            </div>

            <!-- Resumo do pedido -->
            <div class="col-md-4">
                <div class="card bg-dark border-secondary">
                    <div class="card-header bg-primary">
                        <h5 class="text-white mb-0">Resumo do Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal (<?= $totalItens ?> <?= $totalItens === 1 ? 'item' : 'itens' ?>):</span>
                            <span class="text-white">R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Frete:</span>
                            <span class="text-success">Grátis</span>
                        </div>
                        <hr class="border-secondary">
                        <div class="d-flex justify-content-between mb-4">
                            <span class="text-white fw-bold h5">Total:</span>
                            <span class="text-white fw-bold h5">R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></span>
                        </div>
                        
                        <!-- Cupom de desconto -->
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Código do cupom" id="cupom-input">
                                <button class="btn btn-outline-secondary" type="button" id="aplicar-cupom">
                                    Aplicar
                                </button>
                            </div>
                        </div>

                        <button class="btn btn-success w-100 btn-lg mb-3" id="finalizar-compra">
                            <i class="bi bi-credit-card me-2"></i>Finalizar Compra
                        </button>
                        
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Compra 100% segura
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Formas de pagamento -->
                <div class="card bg-dark border-secondary mt-3">
                    <div class="card-body">
                        <h6 class="text-white mb-3">Formas de Pagamento</h6>
                        <div class="d-flex justify-content-around">
                            <i class="bi bi-credit-card text-muted" title="Cartão de Crédito"></i>
                            <i class="bi bi-credit-card-2-front text-muted" title="Cartão de Débito"></i>
                            <i class="bi bi-bank text-muted" title="Transferência Bancária"></i>
                            <i class="bi bi-qr-code text-muted" title="PIX"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmação de remoção -->
<div class="modal fade" id="modalRemover" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">Remover Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-white">
                Tem certeza que deseja remover este item do carrinho?
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmar-remocao">Remover</button>
            </div>
        </div>
    </div>
</div>

<!-- Formulários ocultos para ações -->
<form id="form-atualizar" method="POST" style="display: none;">
    <input type="hidden" name="acao" value="atualizar">
    <input type="hidden" name="id" id="update-id">
    <input type="hidden" name="quantidade" id="update-quantidade">
</form>

<form id="form-remover" method="POST" style="display: none;">
    <input type="hidden" name="acao" value="remover">
    <input type="hidden" name="id" id="remove-id">
</form>

<style>
.item-carrinho {
    transition: all 0.3s ease;
}

.item-carrinho:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

.quantidade-input {
    background-color: #2d2d2d !important;
    border-color: #555 !important;
    color: white !important;
}

.quantidade-input:focus {
    background-color: #2d2d2d !important;
    border-color: #610094 !important;
    color: white !important;
    box-shadow: 0 0 0 0.2rem rgba(97, 0, 148, 0.25) !important;
}

.btn-outline-secondary {
    border-color: #555 !important;
    color: #adb5bd !important;
}

.btn-outline-secondary:hover {
    background-color: #610094 !important;
    border-color: #610094 !important;
    color: white !important;
}

.card {
    border-radius: 10px;
}

.badge {
    background-color: #610094 !important;
}

.carrinho-container .card {
    background-color: #1a1a1a !important;
    border-color: #333 !important;
}

.carrinho-container .text-white {
    color: white !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Atualizar quantidade
    document.querySelectorAll('.btn-aumentar').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemRow = this.closest('.item-carrinho');
            const quantidadeInput = itemRow.querySelector('.quantidade-input');
            const novaQuantidade = parseInt(quantidadeInput.value) + 1;
            
            if (novaQuantidade <= 99) {
                quantidadeInput.value = novaQuantidade;
                atualizarItem(itemRow.dataset.id, novaQuantidade);
            }
        });
    });

    document.querySelectorAll('.btn-diminuir').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemRow = this.closest('.item-carrinho');
            const quantidadeInput = itemRow.querySelector('.quantidade-input');
            const novaQuantidade = parseInt(quantidadeInput.value) - 1;
            
            if (novaQuantidade >= 1) {
                quantidadeInput.value = novaQuantidade;
                atualizarItem(itemRow.dataset.id, novaQuantidade);
            }
        });
    });

    // Atualizar quantidade diretamente no input
    document.querySelectorAll('.quantidade-input').forEach(input => {
        input.addEventListener('change', function() {
            const itemRow = this.closest('.item-carrinho');
            const quantidade = parseInt(this.value);
            
            if (quantidade >= 1 && quantidade <= 99) {
                atualizarItem(itemRow.dataset.id, quantidade);
            } else {
                this.value = 1;
                atualizarItem(itemRow.dataset.id, 1);
            }
        });
    });

    // Remover item
    document.querySelectorAll('.btn-remover').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemRow = this.closest('.item-carrinho');
            const itemId = itemRow.dataset.id;
            
            if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
                removerItem(itemId);
            }
        });
    });

    // Finalizar compra
    document.getElementById('finalizar-compra')?.addEventListener('click', function() {
        <?php if (!$isLoggedIn): ?>
            if (confirm('Você precisa estar logado para finalizar a compra. Deseja fazer login agora?')) {
                window.location.href = 'index.php?url=login';
            }
        <?php else: ?>
            // Implementar lógica de checkout
            alert('Redirecionando para o checkout...');
            // window.location.href = 'index.php?url=checkout';
        <?php endif; ?>
    });

    // Aplicar cupom
    document.getElementById('aplicar-cupom')?.addEventListener('click', function() {
        const cupom = document.getElementById('cupom-input').value.trim();
        if (cupom) {
            // Implementar lógica de cupom
            alert('Funcionalidade de cupom será implementada em breve!');
        }
    });

    function atualizarItem(id, quantidade) {
        try {
            const updateForm = document.getElementById('form-atualizar');
            const updateId = document.getElementById('update-id');
            const updateQuantidade = document.getElementById('update-quantidade');
            
            if (updateForm && updateId && updateQuantidade) {
                updateId.value = id;
                updateQuantidade.value = quantidade;
                updateForm.submit();
            } else {
                console.error('Elementos do formulário de atualização não encontrados');
            }
        } catch (error) {
            console.error('Erro ao atualizar item:', error);
        }
    }

    function removerItem(id) {
        try {
            const removeForm = document.getElementById('form-remover');
            const removeId = document.getElementById('remove-id');
            
            if (removeForm && removeId) {
                removeId.value = id;
                removeForm.submit();
            } else {
                console.error('Elementos do formulário de remoção não encontrados');
            }
        } catch (error) {
            console.error('Erro ao remover item:', error);
        }
    }
});
</script>
