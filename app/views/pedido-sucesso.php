<?php
// Inicializar sessão se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?url=login');
    exit;
}

// Obter ID do pedido
$idPedido = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$idPedido) {
    header('Location: index.php?url=carrinho');
    exit;
}

// Incluir o controlador de pedidos
require_once __DIR__ . '/../controller/PedidoController.php';

// Buscar detalhes do pedido
$detalhesPedido = PedidoController::buscarDetalhesPedido($idPedido, $_SESSION['user_id']);

if (!$detalhesPedido) {
    header('Location: index.php?url=meusPedidos');
    exit;
}

$pedido = $detalhesPedido['pedido'];
$produtos = $detalhesPedido['produtos'];
?>

<style>
    .success-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(25, 135, 84, 0.1));
    }

    .success-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1000px;
        width: 100%;
    }

    .success-header {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .success-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        animation: bounce 2s infinite;
    }

    .pedido-info {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin: 1rem 0;
    }

    .produto-item {
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 0;
    }

    .produto-item:last-child {
        border-bottom: none;
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }

    .btn-group-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    @media (max-width: 576px) {
        .btn-group-actions {
            flex-direction: column;
        }
    }
</style>

<div class="success-container my-5">
    <div class="success-card">
        <!-- Header de sucesso -->
        <div class="success-header">
            <div class="success-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <h2 class="mb-0">Pedido Realizado com Sucesso!</h2>
            <p class="mb-0">Seu pedido foi processado e confirmado</p>
        </div>

        <!-- Detalhes do pedido -->
        <div class="p-4">
            <div class="pedido-info">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Número do Pedido</h6>
                        <p class="fw-bold mb-0">#<?= str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Data do Pedido</h6>
                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Status</h6>
                        <span class="badge bg-warning text-light">
                            <?= ucfirst($pedido['status']) ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Loja</h6>
                        <p class="mb-0"><?= htmlspecialchars($pedido['nome_loja'] ?: 'Loja Principal') ?></p>
                    </div>
                </div>
            </div>

            <!-- Produtos do pedido -->
            <h6 class="fw-bold mt-4 mb-3">Itens do Pedido</h6>
            <div class="produtos-lista">
                <?php foreach ($produtos as $produto): ?>
                    <div class="produto-item">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1"><?= htmlspecialchars($produto['nome_produto']) ?></h6>
                                <?php if ($produto['cor']): ?>
                                    <small class="text-muted">Cor: <?= htmlspecialchars($produto['cor']) ?></small>
                                <?php endif; ?>
                                <div class="small text-muted">
                                    Quantidade: <?= $produto['quantidade'] ?> x R$
                                    <?= number_format($produto['preco_unitario'], 2, ',', '.') ?>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="fw-bold">
                                    R$ <?= number_format($produto['subtotal'], 2, ',', '.') ?>
                                </div>
                                <?php if ($produto['nome_loja']): ?>
                                    <small class="text-muted">
                                        Loja: <?= htmlspecialchars($produto['nome_loja']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Resumo financeiro -->
            <div class="mt-4 p-3 bg-light rounded">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span>
                </div>
                <?php if ($pedido['desconto'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Desconto:</span>
                        <span>-R$ <?= number_format($pedido['cupom'], 2, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                <hr>
                <div class="d-flex justify-content-between fw-bold fs-5">
                    <span>Total Pago:</span>
                    <span class="text-success">R$ <?= number_format($pedido['total_final'], 2, ',', '.') ?></span>
                </div>
            </div>

            <!-- Informações adicionais -->
            <div class="mt-4 p-3 border rounded">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Próximos Passos</h6>
                <ul class="mb-0">
                    <li>Acompanhe o status do seu pedido na área "Meus Pedidos" no perfil do usuário.</li>
                    <li>Em caso de dúvidas, entre em contato conosco</li>
                </ul>
            </div>

            <!-- Botões de ação -->
            <div class="btn-group-actions mt-4">
                <!-- Botão Continuar Comprando acima dos outros -->
                <a href="index.php?url=produto" class="btn btn-success w-50 mb-3">
                    <i class="bi bi-plus-circle me-2"></i>Continuar Comprando
                </a>

                <!-- Outros botões, todos com a mesma largura -->
                <div class="d-flex col-md-6 gap-4">
                    <a href="index.php?url=meuperfil" class="btn btn-product w-50 mb-3">
                        <i class="bi bi-list-ul me-2"></i>Ver Meus Pedidos
                    </a>
                    <a href="index.php?url=produto" class="btn btn-product w-50 mb-3">
                        <i class="bi bi-house me-2"></i>Voltar à Loja
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Opcional: Scroll suave para o topo quando a página carregar
        document.addEventListener('DOMContentLoaded', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>