<?php
// Inicializar sessão se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir os controladores
require_once __DIR__ . '/../controller/CarrinhoController.php';
require_once __DIR__ . '/../controller/cupons-carrinho.php';
require_once __DIR__ . '/../core/Database.php';

// Verificar se o usuário está logado
$isLoggedIn = isset($_SESSION['user_id']);

// Obter dados do carrinho através do controlador
$itensCarrinho = CarrinhoController::getItens();
$totalCarrinho = CarrinhoController::calcularTotal();
$totalItens = CarrinhoController::contarItens();

// Obter cupons e calcular valor final
$cupomAplicado = CuponsCarrinhoController::getCupomAplicado();
$valoresCarrinho = CuponsCarrinhoController::calcularValorFinal($totalCarrinho);

// Obter dados do usuário logado (cidade e CEP)
$userData = null;
if ($isLoggedIn) {
    try {
        $pdo = Database::conectar();
        $stmt = $pdo->prepare("SELECT cidade, cep FROM endereco WHERE id_user = ? LIMIT 1");
        $stmt->execute([$_SESSION['user_id']]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erro ao buscar dados do endereço: " . $e->getMessage());
        $userData = null;
    }
}

// Se o carrinho estiver vazio, redirecionar
if (empty($itensCarrinho)) {
    header('Location: index.php?url=carrinho');
    exit;
}
?>


<body>
    <div class="container py-4 mt-3">
        <div class="step-indicator bg-dark p-4 rounded shadow-lg">

            <div class="step completed">
                <div class="step-number" style="background: var(--pmain); color: var(--clear);">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="step-label text-white">Carrinho</span>
            </div>

            <div class="step-line" style="background: var(--pmain);"></div>

            <div class="step active">
                <div class="step-number" style="background: var(--psec); color: var(--clear);">
                    <i class="bi bi-truck"></i>
                </div>
                <span class="step-label text-white">Entrega</span>
            </div>

            <div class="step-line"></div>

            <div class="step pending">
                <div class="step-number bg-secondary text-white">
                    <i class="bi bi-credit-card"></i>
                </div>
                <span class="step-label text-muted">Pagamento</span>
            </div>

        </div>
    </div>

    <main class="container py-4 mt-3 bg-dark rounded shadow-lg">

        <div class="row g-4">

            <div class="col-lg-7">

                <div class="bg-dark border border-secondary rounded p-4 shadow">

                    <h5 class="fw-bold mb-4 text-white">Escolha a forma de entrega</h5>


                    <label for="envEndereco" class="shipping-option w-100 py-3 px-3 rounded mb-3"
                        style="background: rgba(255,255,255,0.05); cursor:pointer;">
                        <div class="d-flex justify-content-between">

                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="entrega-option"
                                    id="envEndereco" checked> <!-- Adicionado 'checked' por padrão -->
                                <div>
                                    <span class="fw-bold text-white d-block">Enviar no meu endereço</span>
                                    <!-- Endereço do usuário logado -->
                                    <?php if ($userData && ($userData['cidade'])): ?>
                                        <span class="text-white" style="font-size: 0.9em;"><?php echo htmlspecialchars($userData['cidade']); ?> - <?php echo htmlspecialchars($userData['cep']); ?></span>
                                    <?php else: ?>
                                        <span class="text-light " style="font-size: 0.9em;">Endereço não cadastrado</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <span class="fw-bold text-primary">Grátis</span>
                        </div>
                    </label>

                </div>
            </div>

            <div class="col-lg-5">

                <div class="bg-dark border border-secondary rounded p-4 shadow" style="position: sticky; top: 20px;">

                    <h6 class="fw-bold mb-3 text-white">Resumo da compra</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-light">Produto</span>
                        <span class="text-light">R$
                            <?= number_format($valoresCarrinho['valor_original'], 2, ',', '.') ?></span>
                    </div>

                    <?php if ($cupomAplicado && $valoresCarrinho['desconto'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">
                                Desconto (<?= htmlspecialchars($cupomAplicado['codigo']) ?>)
                            </span>
                            <span class="text-success">
                                -R$ <?= number_format($valoresCarrinho['desconto'], 2, ',', '.') ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-light">Frete</span>
                        <span class="fw-bold text-primary">GRÁTIS</span>
                    </div>

                    <hr class="border-secondary my-3">

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span class="text-white">Total</span>
                        <span class="text-white">
                            R$ <?= number_format($valoresCarrinho['valor_final'], 2, ',', '.') ?>
                        </span>
                    </div>

                </div>

                <div class="text-center mt-4 w-100">
                    <div class="d-flex justify-content-between">

                        <a href="index.php?url=carrinho" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Voltar ao Carrinho
                        </a>

                        <button id="btn-continuar" class="btn-product w-50 mx-2">
                            Próximo <i class="bi bi-arrow-right ms-2"></i>
                        </button>

                    </div>
                </div>

            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnContinuar = document.getElementById('btn-continuar');

            btnContinuar.addEventListener('click', function () {
                const entregaSelecionada = document.querySelector('input[name="entrega-option"]:checked');

                if (!entregaSelecionada) {
                    // Substitua 'alert' por um modal de notificação se tiver um
                    console.warn('Nenhuma opção de entrega selecionada.');
                    return;
                }

                if (entregaSelecionada.id === 'envEndereco') {
                    // Opção "Enviar no meu endereço" está selecionada, ir para pagamento.
                    window.location.href = 'index.php?url=metodopagamento';

                }
            });

        });
    </script>
</body>
