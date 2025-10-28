<?php
// Inicializar sessão se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir os controladores
require_once __DIR__ . '/../controller/CarrinhoController.php';

// Verificar se o usuário está logado
$isLoggedIn = isset($_SESSION['user_id']);

// Obter dados do carrinho através do controlador
$itensCarrinho = CarrinhoController::getItens();
$totalCarrinho = CarrinhoController::calcularTotal();
$totalItens = CarrinhoController::contarItens();

// Se o carrinho estiver vazio, redirecionar
if (empty($itensCarrinho)) {
    header('Location: index.php?url=carrinho');
    exit;
}
?>

<style>
    /* Faz o item da lista ser clicável */
    .list-group-item-action {
        cursor: pointer;
    }
    
    .step-indicator {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }
    
    .step {
        display: flex;
        align-items: center;
        margin: 0 1rem;
    }
    
    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
        font-weight: bold;
        color: white;
    }
    
    .step.completed .step-number {
        background-color: #28a745;
    }
    
    .step.active .step-number {
        background-color: #007bff;
    }
    
    .step.pending .step-number {
        background-color: #6c757d;
    }
    
    .step-line {
        width: 50px;
        height: 2px;
        background-color: #dee2e6;
        margin: 0 1rem;
    }
</style>

<main class="container py-4">
    <!-- Indicador de progresso -->
    <div class="step-indicator">
        <div class="step completed">
            <div class="step-number">1</div>
            <span>Carrinho</span>
        </div>
        <div class="step-line"></div>
        <div class="step active">
            <div class="step-number">2</div>
            <span>Seguro</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-number">3</div>
            <span>Entrega</span>
        </div>
        <div class="step-line"></div>
        <div class="step pending">
            <div class="step-number">4</div>
            <span>Pagamento</span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Seção do seguro -->
        <div class="col-lg-8">
            <section class="bg-white rounded shadow-sm p-4">

                <h5 class="fw-bold mb-4 text-ml-title">Adicione um seguro</h5>

                <div class="list-group list-group-flush">

                    <label for="seguro-12" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">

                        <div class="d-flex align-items-center">
                            <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-12">
                            <span class="fw-bold text-ml-dark" data-seguro='12'>12 meses de Garantia estendida</span>
                        </div>

                        <div class="text-end">
                            <span class="fs-5 fw-bold text-ml-dark">R$ 289</span>
                            <div>
                                <span class="text-primary fw-semibold">12x R$ 24,08</span>
                                <span class="text-primary" style="font-size: 0.9em;"> sem juros</span>
                            </div>
                        </div>
                    </label>

                    <label for="seguro-18" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-18">
                            <span class="fw-bold text-ml-dark" data-seguro="18">18 meses de Garantia estendida</span>
                        </div>
                        <div class="text-end">
                            <span class="fs-5 fw-bold text-ml-dark">R$ 463</span>
                            <div>
                                <span class="text-primary fw-semibold">12x R$ 36,17</span>
                                <span class="text-primary" style="font-size: 0.9em;"> sem juros</span>
                            </div>
                        </div>
                    </label>

                    <label for="seguro-24" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-24">
                            <span class="fw-bold text-ml-dark" data-seguro="24">24 meses de Garantia estendida</span>
                        </div>
                        <div class="text-end">
                            <span class="fs-5 fw-bold text-ml-dark">R$ 499</span>
                            <div>
                                <span class="text-primary fw-semibold">12x R$ 41,58</span>
                                <span class="text-primary" style="font-size: 0.9em;"> sem juros</span>
                            </div>
                        </div>
                    </label>

                    <label for="seguro-none" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-none" checked>
                            <span class="fw-bold text-ml-dark">Sem seguro</span>
                        </div>
                    </label>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php?url=carrinho" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Voltar ao Carrinho
                    </a>
                    <button id="btn-prosseguir" class="btn btn-primary">
                        Próximo: Entrega <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </section>
        </div>

        <!-- Resumo do pedido -->
        <div class="col-lg-4">
            <div class="bg-white rounded shadow-sm p-4" style="position: sticky; top: 20px;">
                <h6 class="fw-bold mb-3">Resumo da compra</h6>

                <!-- Items do carrinho -->
                <?php foreach ($itensCarrinho as $item): ?>
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <img src="<?= htmlspecialchars($item['imagem']) ?>" 
                             alt="<?= htmlspecialchars($item['nome']) ?>" 
                             class="img-fluid rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 0.9rem;"><?= htmlspecialchars($item['nome']) ?></h6>
                            <small class="text-muted">Qty: <?= $item['quantidade'] ?></small>
                            <div class="fw-bold">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal (<?= $totalItens ?> <?= $totalItens === 1 ? 'item' : 'itens' ?>):</span>
                    <span>R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></span>
                </div>
                
                <div class="d-flex justify-content-between mb-2" id="seguro-valor" style="display: none !important;">
                    <span class="text-muted">Seguro:</span>
                    <span id="seguro-preco">R$ 0,00</span>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Frete:</span>
                    <span class="text-success">GRÁTIS</span>
                </div>

                <hr class="my-3">

                <div class="d-flex justify-content-between fs-5 fw-bold">
                    <span>Total</span>
                    <span id="total-final">R$ <?= number_format($totalCarrinho, 2, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seguroOptions = document.querySelectorAll('input[name="seguro-option"]');
    const seguroValorDiv = document.getElementById('seguro-valor');
    const seguroPreco = document.getElementById('seguro-preco');
    const totalFinal = document.getElementById('total-final');
    const btnProsseguir = document.getElementById('btn-prosseguir');
    
    const baseTotal = <?= $totalCarrinho ?>;
    
    const seguroPrecos = {
        'seguro-12': 289,
        'seguro-18': 463,
        'seguro-24': 499,
        'seguro-none': 0
    };
    
    function atualizarTotal() {
        const seguroSelecionado = document.querySelector('input[name="seguro-option"]:checked');
        const valorSeguro = seguroPrecos[seguroSelecionado.id] || 0;
        const novoTotal = baseTotal + valorSeguro;
        
        if (valorSeguro > 0) {
            seguroValorDiv.style.display = 'flex';
            seguroPreco.textContent = 'R$ ' + valorSeguro.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
        } else {
            seguroValorDiv.style.display = 'none';
        }
        
        totalFinal.textContent = 'R$ ' + novoTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    }
    
    seguroOptions.forEach(option => {
        option.addEventListener('change', atualizarTotal);
    });
    
    btnProsseguir.addEventListener('click', function() {
        // Salvar a opção de seguro selecionada na sessão
        const seguroSelecionado = document.querySelector('input[name="seguro-option"]:checked');
        if (seguroSelecionado) {
            // Aqui você pode fazer uma requisição AJAX para salvar na sessão
            // Por enquanto, vamos apenas redirecionar
            window.location.href = 'index.php?url=paginaRetirada';
        }
    });
});
</script>