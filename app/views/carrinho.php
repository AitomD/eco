<?php
// Inicializar sessão se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir os controladores
require_once __DIR__ . '/../controller/CarrinhoController.php';
require_once __DIR__ . '/../controller/cupons-carrinho.php';

// Nota: Processamento de ações já foi feito no index.php

// Verificar se o usuário está logado
$isLoggedIn = isset($_SESSION['user_id']);

// Obter dados do carrinho através do controlador
$itensCarrinho = CarrinhoController::getItens();
$totalCarrinho = CarrinhoController::calcularTotal();
$totalItens = CarrinhoController::contarItens();

// Obter cupons e calcular valor final
$cuponsDisponiveis = CuponsCarrinhoController::getCuponsDisponiveis();
$cupomAplicado = CuponsCarrinhoController::getCupomAplicado();
$valoresCarrinho = CuponsCarrinhoController::calcularValorFinal($totalCarrinho);
?>


<div class="container my-5 pt-5 carrinho-container">
    <!-- Indicador de progresso -->
    <?php if (!empty($itensCarrinho)): ?>
        <div class="step-indicator mb-4">
            <div class="step active">
                <div class="step-number">
                    <i class="bi bi-cart-check"></i>
                </div>
                <span class="step-label" style="color: white;">Carrinho</span>
            </div>
            <div class="step-line"></div>
            <div class="step pending">
                <div class="step-number">
                    <i class="bi bi-truck"></i>
                </div>
                <span class="step-label" style="color: white;">Entrega</span>
            </div>
            <div class="step-line"></div>
            <div class="step pending">
                <div class="step-number">
                    <i class="bi bi-credit-card"></i>
                </div>
                <span class="step-label" style="color: white;">Pagamento</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <h2 class="text-white mb-4">
                <i class="bi bi-cart2 me-3"></i>Meu Carrinho
                <?php if ($totalItens > 0): ?>
                    <span class="badge bg-primary ms-2"><?= $totalItens ?>
                        <?= $totalItens === 1 ? 'item' : 'itens' ?></span>
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
                        <a href="index.php?url=produto" class="btn btn-lg">
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
                    <div class="card-header" style="background-color: var(--pmain); color: var(--clear);">
                        <h5 class="mb-0 text-white">Produtos no Carrinho</h5>
                    </div>

                    <div class="card-body">

                        <?php foreach ($itensCarrinho as $item): ?>
                            <div class="row align-items-center py-3 border-bottom border-secondary item-carrinho"
                                data-id="<?= $item['id'] ?>">

                                <!-- Imagem do produto -->
                                <div class="col-md-2">
                                    <img src="<?= htmlspecialchars($item['imagem']) ?>"
                                        alt="<?= htmlspecialchars($item['nome']) ?>" class="img-fluid rounded">
                                </div>

                                <!-- Nome e preço -->
                                <div class="col-md-4">
                                    <h6 class="text-white mb-1">
                                        <?= htmlspecialchars($item['nome']) ?>
                                    </h6>
                                    <p class="small text-white mb-0">
                                        Preço unitário: R$ <?= number_format($item['preco'], 2, ',', '.') ?>
                                    </p>
                                </div>

                                <!-- Quantidade -->
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary btn-sm btn-diminuir" type="button">
                                            <i class="bi bi-dash"></i>
                                        </button>

                                        <input class="form-control text-center quantidade-input"
                                            value="<?= $item['quantidade'] ?>" min="1" max="99">

                                        <button class="btn btn-outline-secondary btn-sm btn-aumentar" type="button">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Preço total -->
                                <div class="col-md-2">
                                    <p class="fw-bold text-white mb-1">
                                        R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?>
                                    </p>
                                </div>

                                <!-- Remover -->
                                <div class="col-md-1 text-end">
                                    <button class="btn btn-outline-danger btn-sm btn-remover" title="Remover item">
                                        <i class="bi bi-trash fs-6"></i>
                                    </button>
                                </div>

                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!-- Botões de ação -->
                <div class="d-flex justify-content-between">
                    <a href="index.php?url=produto" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left me-2"></i>
                        Continuar Comprando
                    </a>

                    <form method="POST" class="d-inline">
                        <input type="hidden" name="acao" value="limpar">
                        <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('Tem certeza que deseja limpar o carrinho?')">
                            <i class="bi bi-trash me-2"></i>
                            Limpar Carrinho
                        </button>
                    </form>
                </div>

            </div>

            <!-- Resumo do pedido -->
            <div class="col-md-4">

                <div class="card bg-dark border-secondary">

                    <div class="card-header" style="background-color: var(--pmain); color: var(--clear);">
                        <h5 class="mb-0 text-white">Resumo do Pedido</h5>
                    </div>

                    <div class="card-body">

                        <!-- Subtotal -->
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-light">
                                Subtotal (<?= $totalItens ?>     <?= $totalItens === 1 ? 'item' : 'itens' ?>):
                            </span>
                            <span class="text-light">
                                R$ <?= number_format($valoresCarrinho['valor_original'], 2, ',', '.') ?>
                            </span>
                        </div>

                        <!-- Cupom aplicado -->
                        <?php if ($cupomAplicado && $valoresCarrinho['desconto'] > 0): ?>
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1 me-2">
                                    <span class="text-success d-block">
                                        Desconto (<?= htmlspecialchars($cupomAplicado['codigo']) ?>)
                                    </span>
                                    <button class="btn btn-link text-light btn-sm text-danger p-0 mt-1 w-100"
                                        onclick="removerCupom()" title="Remover cupom">
                                        <i class="bi bi-x-circle me-1"></i>Remover
                                    </button>
                                </div>
                                <span class="text-success fw-bold">-R$
                                    <?= number_format($valoresCarrinho['desconto'], 2, ',', '.') ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Frete -->
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-light">Frete:</span>
                            <span class="text-primary fw-bold">Grátis</span>
                        </div>

                        <hr class="border-secondary">

                        <!-- Total final -->
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold text-white h5">Total:</span>
                            <span class="fw-bold text-white h5">
                                R$ <?= number_format($valoresCarrinho['valor_final'], 2, ',', '.') ?>
                            </span>
                        </div>

                        <!-- Cupom de desconto -->
                        <?php if (!$cupomAplicado): ?>

                            <div class="mb-3">
                                <form method="POST" id="form-cupom">
                                    <input type="hidden" name="acao_cupom" value="aplicar">
                                    <input type="hidden" name="valor_carrinho" value="<?= $totalCarrinho ?>">

                                    <div class="input-group">
                                        <input type="text" class="form-control cupom-input" name="codigo_cupom"
                                            placeholder="Código do cupom" id="cupom-input" value="">
                                        <button class="btn btn-outline-secondary cupom-btn" type="submit">
                                            Aplicar
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Cupons disponíveis -->
                            <?php if (!empty($cuponsDisponiveis)): ?>

                                <div class="mb-2">
                                    <button class="btn btn-sm text-white p-2 w-100" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#cuponsDisponiveis">
                                        <i class="bi bi-tag me-1"></i>
                                        Ver cupons disponíveis
                                    </button>

                                    <div class="collapse" id="cuponsDisponiveis">
                                        <div class="mt-2">

                                            <?php foreach (array_slice($cuponsDisponiveis, 0, 3) as $cupom): ?>
                                                <div class="border border-secondary rounded p-2 mb-2 cupom-disponivel"
                                                    data-codigo="<?= htmlspecialchars($cupom['codigo']) ?>">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="flex-grow-1 me-2">
                                                            <small
                                                                class="text-white fw-bold d-block"><?= htmlspecialchars($cupom['codigo']) ?></small>
                                                            <small
                                                                class="text-light"><?= htmlspecialchars($cupom['descricao']) ?></small>
                                                            <button
                                                                class="btn w-100 btn-sm mt-2 text-white aplicar-cupom-disponivel py-2">
                                                                Usar
                                                            </button>
                                                        </div>
                                                    <?php endforeach; ?>

                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>

                                <?php endif; ?>

                                <!-- Continuar -->
                                <button class="btn btn-success w-100 btn-lg mb-3 mt-3" id="finalizar-compra">
                                    <i class="bi bi-arrow-right me-2"></i>
                                    Próximo: Entrega
                                </button>

                                <div class="text-center">
                                    <small class="text-white text-muted">
                                        <i class="bi bi-shield-check me-1"></i>
                                        Compra 100% segura
                                    </small>
                                </div>
                            </div>
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

<form id="form-remover-cupom" method="POST" style="display: none;">
    <input type="hidden" name="acao_cupom" value="remover">
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Atualizar quantidade
        document.querySelectorAll('.btn-aumentar').forEach(btn => {
            btn.addEventListener('click', function () {
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
            btn.addEventListener('click', function () {
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
            input.addEventListener('change', function () {
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
            btn.addEventListener('click', function () {
                const itemRow = this.closest('.item-carrinho');
                const itemId = itemRow.dataset.id;

                if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
                    removerItem(itemId);
                }
            });
        });

        // Finalizar compra
        document.getElementById('finalizar-compra')?.addEventListener('click', function () {
            <?php if (!$isLoggedIn): ?>
                if (confirm('Você precisa estar logado para finalizar a compra. Deseja fazer login agora?')) {
                    window.location.href = 'index.php?url=login';
                }
            <?php else: ?>
                // Ir diretamente para a página de entrega
                window.location.href = 'index.php?url=paginaRetirada';
            <?php endif; ?>
        });

        // Aplicar cupons disponíveis
        document.querySelectorAll('.aplicar-cupom-disponivel').forEach(btn => {
            btn.addEventListener('click', function () {
                const cupomDiv = this.closest('.cupom-disponivel');
                const codigo = cupomDiv.dataset.codigo;
                const cupomInput = document.getElementById('cupom-input');

                if (cupomInput) {
                    cupomInput.value = codigo;
                    document.getElementById('form-cupom').submit();
                }
            });
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

    // Função global para remover cupom
    function removerCupom() {
        document.getElementById('form-remover-cupom').submit();
    }
</script>