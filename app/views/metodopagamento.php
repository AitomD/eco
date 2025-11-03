<?php
// Inicializar sessão se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir os controladores
require_once __DIR__ . '/../controller/CarrinhoController.php';
require_once __DIR__ . '/../controller/cupons-carrinho.php';

// Verificar se o usuário está logado
$isLoggedIn = isset($_SESSION['user_id']);

// Obter dados do carrinho através do controlador
$itensCarrinho = CarrinhoController::getItens();
$totalCarrinho = CarrinhoController::calcularTotal();
$totalItens = CarrinhoController::contarItens();

// Obter cupons e calcular valor final
$cupomAplicado = CuponsCarrinhoController::getCupomAplicado();
$valoresCarrinho = CuponsCarrinhoController::calcularValorFinal($totalCarrinho);

// Se o carrinho estiver vazio, redirecionar
if (empty($itensCarrinho)) {
    header('Location: index.php?url=carrinho');
    exit;
}
?>

<body >

    <!-- STEP INDICATOR -->
    <div class="container py-4 mt-3">
        <div class="step-indicator bg-dark p-4 rounded shadow-lg">

            <div class="step completed">
                <div class="step-number" style="background: var(--pmain); color: #fff;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="step-label text-white">Carrinho</span>
            </div>

            <div class="step-line" style="background: var(--pmain);"></div>

            <div class="step completed">
                <div class="step-number" style="background: var(--pmain); color: #fff;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="step-label text-white">Entrega</span>
            </div>

            <div class="step-line" style="background: var(--pmain);"></div>

            <div class="step active">
                <div class="step-number" style="background: var(--psec); color: #fff;">
                    <i class="bi bi-credit-card"></i>
                </div>
                <span class="step-label text-white">Pagamento</span>
            </div>

        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <main class="container py-4 rounded shadow-lg">
        

        <div class="row g-4">

            <!-- FORMAS DE PAGAMENTO -->
            <div class="col-lg-7">

                <div class="bg-dark border border-secondary rounded p-4 shadow"
                    style="background:#161616;">

                    <h5 class="fw-bold mb-4 text-white">Escolha a forma de pagamento</h5>

                    <!-- PIX -->
                    <label for="pix"
                        class="payment-option w-100 py-3 px-3 rounded mb-3"
                        style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                        <div class="d-flex justify-content-between">

                            <div class="d-flex">
                                <input class="form-check-input me-3"
                                    type="radio" name="payment-option" id="pix" checked>
                                <div>
                                    <span class="fw-bold text-white d-block">PIX</span>
                                    <span class="text-light" style="font-size:0.9em;">Aprovação imediata</span>
                                </div>
                            </div>

                            <div class="text-end">
                                <span class="fw-bold text-primary">À vista</span>
                                <div class="text-light" style="font-size:0.9em;">5% de desconto</div>
                            </div>

                        </div>

                        <div id="pix-info"
                            class="pix-info mt-3 p-3 rounded text-center"
                            style="display:none; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:white;">
                            <i class="bi bi-qr-code bg-white p-2 rounded-2 fs-1" style="font-size:2rem; color:var(--black);"></i>
                            <p class="mt-2 mb-1"><strong>Pagamento via PIX</strong></p>
                            <p class="text-light small">Após confirmar o pedido, você receberá o QR Code para pagamento.</p>
                        </div>

                    </label>

                    <!-- DÉBITO -->
                    <label for="debito"
                        class="payment-option w-100 py-3 px-3 rounded mb-3"
                        style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3"
                                    type="radio" name="payment-option" id="debito">
                                <div>
                                    <span class="fw-bold text-white d-block">Cartão de Débito</span>
                                    <span class="text-light" style="font-size:0.9em;">Débito imediato</span>
                                </div>
                            </div>

                            <div class="text-end">
                                <span class="fw-bold text-primary">À vista</span>
                                <div class="text-light" style="font-size:0.9em;">3% de desconto</div>
                            </div>
                        </div>

                    </label>

                    <!-- BOLETO -->
                    <label for="boleto"
                        class="payment-option w-100 py-3 px-3 rounded mb-3"
                        style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3"
                                    type="radio" name="payment-option" id="boleto">
                                <div>
                                    <span class="fw-bold text-white d-block">Boleto Bancário</span>
                                    <span class="text-light" style="font-size:0.9em;">3 dias úteis</span>
                                </div>
                            </div>

                            <div class="text-end">
                                <span class="fw-bold text-primary">À vista</span>
                                <div class="text-light" style="font-size:0.9em;">2% de desconto</div>
                            </div>
                        </div>

                        <div id="boleto-info"
                            class="boleto-info mt-3 p-3 rounded"
                            style="display:none; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:white;">

                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text text-primary me-3" style="font-size:2rem;"></i>
                                <div>
                                    <p class="mb-1"><strong>Pagamento via boleto</strong></p>
                                    <p class="text-light small mb-0">
                                        O boleto será enviado ao seu e-mail.
                                    </p>
                                </div>
                            </div>

                        </div>

                    </label>

                    <!-- CRÉDITO -->
                    <label for="credito"
                        class="payment-option w-100 py-3 px-3 rounded mb-3"
                        style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                        <div class="d-flex justify-content-between">

                            <div class="d-flex">
                                <input class="form-check-input me-3"
                                    type="radio" name="payment-option" id="credito">

                                <div>
                                    <span class="fw-bold text-white d-block">Cartão de Crédito</span>
                                    <span class="text-light" style="font-size:0.9em;">Até 12x sem juros</span>
                                </div>
                            </div>

                            <i class="bi bi-credit-card text-primary" style="font-size:1.5rem;"></i>

                        </div>

                        <!-- FORM CARTÃO -->
                        <div id="credit-card-form"
                            class="credit-card-form mt-3 p-3 rounded"
                            style="display:none; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:white;">

                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label text-white">Número do Cartão</label>
                                    <input class="form-control bg-dark text-white border-secondary"
                                        id="card-number" maxlength="19" placeholder="0000 0000 0000 0000">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-white">Nome no Cartão</label>
                                    <input class="form-control bg-dark text-white border-secondary"
                                        id="card-name" placeholder="Nome completo">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label text-white">Validade</label>
                                    <input class="form-control bg-dark text-white border-secondary"
                                        id="card-expiry" maxlength="5" placeholder="MM/AA">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label text-white">CVV</label>
                                    <input class="form-control bg-dark text-white border-secondary"
                                        id="card-cvv" maxlength="4" placeholder="000">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white">Parcelas</label>
                                    <select class="form-select bg-dark text-white border-secondary" id="installments"></select>
                                </div>

                            </div>

                        </div>

                    </label>

                </div>

                <!-- BOTÕES -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php?url=paginaRetirada"
                        class="btn btn-outline-secondary"
                        style="border-color:#444; color:#ccc;">
                        <i class="bi bi-arrow-left me-2"></i>Voltar
                    </a>

                    <button id="btn-finalizar"
                        class="btn-product w-50 mx-2"
                        style="background:var(--pmain); color:white; border:none;">
                        <i class="bi bi-check-circle me-2"></i>
                        Finalizar Compra
                    </button>
                </div>

            </div>

            <!-- RESUMO -->
            <div class="col-lg-5">

                <div class="bg-dark border border-secondary rounded p-4 shadow"
                    style="position:sticky; top:20px; background:#161616;">

                    <h6 class="fw-bold mb-3 text-white">Resumo da compra</h6>

                    <?php foreach ($itensCarrinho as $item): ?>
                        <div class="d-flex align-items-center mb-3 pb-3"
                            style="border-bottom:1px solid rgba(255,255,255,0.1);">

                            <img src="<?= htmlspecialchars($item['imagem']) ?>"
                                class="img-fluid rounded me-3"
                                style="width:60px; height:60px; object-fit:cover;">

                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-white" style="font-size:0.9rem;">
                                    <?= htmlspecialchars($item['nome']) ?>
                                </h6>

                                <small class="text-light">Qtd: <?= $item['quantidade'] ?></small>
                                <div class="fw-bold text-white">
                                    R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-light">Subtotal:</span>
                        <span class="text-white">R$ <?= number_format($valoresCarrinho['valor_original'], 2, ',', '.') ?></span>
                    </div>

                    <?php if ($cupomAplicado && $valoresCarrinho['desconto'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">Desconto (<?= $cupomAplicado['codigo'] ?>):</span>
                            <span class="text-success">-R$ <?= number_format($valoresCarrinho['desconto'], 2, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-light">Frete:</span>
                        <span class="text-primary">GRÁTIS</span>
                    </div>

                    <hr style="border-color:rgba(255,255,255,0.1);">

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span class="text-white">Total</span>
                        <span class="text-white" id="total-final">
                            R$ <?= number_format($valoresCarrinho['valor_final'], 2, ',', '.') ?>
                        </span>
                    </div>

                    <div class="mt-3 p-3 rounded"
                        style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);">

                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            <span class="fw-bold text-success">Compra 100% Segura</span>
                        </div>

                        <small class="text-light">Seus dados estão protegidos.</small>

                    </div>

                </div>
            </div>

        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentOptions = document.querySelectorAll('input[name="payment-option"]');
            const creditCardForm = document.getElementById('credit-card-form');
            const pixInfo = document.getElementById('pix-info');
            const boletoInfo = document.getElementById('boleto-info');
            const descontoPagamento = document.getElementById('desconto-pagamento');
            const descontoValor = document.getElementById('desconto-valor');
            const totalFinal = document.getElementById('total-final');
            const btnFinalizar = document.getElementById('btn-finalizar');

            const baseTotal = <?= $valoresCarrinho['valor_final'] ?>;

            const descontos = {
                'pix': 0.05, // 5%
                'credito': 0, // 0%
                'debito': 0.03, // 3%
                'boleto': 0.02 // 2%
            };

            function atualizarTotal() {
                const paymentSelected = document.querySelector('input[name="payment-option"]:checked');
                if (!paymentSelected) return;

                const desconto = descontos[paymentSelected.id] || 0;
                const valorDesconto = Math.round(baseTotal * desconto * 100) / 100; // Arredondar para 2 casas decimais
                const novoTotal = baseTotal - valorDesconto;

                if (desconto > 0) {
                    descontoPagamento.style.display = 'flex';
                    descontoValor.textContent = '-R$ ' + valorDesconto.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                } else {
                    descontoPagamento.style.display = 'none';
                }

                totalFinal.textContent = 'R$ ' + novoTotal.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                // Atualizar parcelas do cartão de crédito
                const installmentsSelect = document.getElementById('installments');
                if (installmentsSelect) {
                    installmentsSelect.innerHTML = `
                    <option value="1">1x de R$ ${novoTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} à vista</option>
                    <option value="2">2x de R$ ${(novoTotal / 2).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} sem juros</option>
                    <option value="3">3x de R$ ${(novoTotal / 3).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} sem juros</option>
                    <option value="6">6x de R$ ${(novoTotal / 6).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} sem juros</option>
                    <option value="12">12x de R$ ${(novoTotal / 12).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} sem juros</option>
                `;
                }
            }

            function togglePaymentForms() {
                // Esconder todos os formulários
                creditCardForm.style.display = 'none';
                pixInfo.style.display = 'none';
                boletoInfo.style.display = 'none';

                // Mostrar o formulário correspondente
                const selected = document.querySelector('input[name="payment-option"]:checked');

                if (selected.id === 'credito') {
                    creditCardForm.style.display = 'block';
                } else if (selected.id === 'pix') {
                    pixInfo.style.display = 'block';
                } else if (selected.id === 'boleto') {
                    boletoInfo.style.display = 'block';
                }
            }

            paymentOptions.forEach(option => {
                option.addEventListener('change', function() {
                    togglePaymentForms();
                    atualizarTotal();
                });
            });

            // Máscara para número do cartão
            const cardNumber = document.getElementById('card-number');
            cardNumber.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
                let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                e.target.value = formattedValue;
            });

            // Máscara para data de validade
            const cardExpiry = document.getElementById('card-expiry');
            cardExpiry.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value;
            });

            // Máscara para CVV
            const cardCvv = document.getElementById('card-cvv');
            cardCvv.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });

            btnFinalizar.addEventListener('click', function() {
                const paymentSelected = document.querySelector('input[name="payment-option"]:checked');

                if (!paymentSelected) {
                    alert('Por favor, selecione uma forma de pagamento.');
                    return;
                }

                // Validar formulário do cartão de crédito se selecionado
                if (paymentSelected.id === 'credito') {
                    const cardNumber = document.getElementById('card-number').value;
                    const cardName = document.getElementById('card-name').value;
                    const cardExpiry = document.getElementById('card-expiry').value;
                    const cardCvv = document.getElementById('card-cvv').value;

                    if (!cardNumber || !cardName || !cardExpiry || !cardCvv) {
                        alert('Por favor, preencha todos os campos do cartão de crédito.');
                        return;
                    }

                    if (cardNumber.replace(/\s/g, '').length < 16) {
                        alert('Número do cartão deve ter 16 dígitos.');
                        return;
                    }

                    if (cardExpiry.length < 5) {
                        alert('Data de validade inválida.');
                        return;
                    }

                    if (cardCvv.length < 3) {
                        alert('CVV deve ter pelo menos 3 dígitos.');
                        return;
                    }
                }

                // Simular processamento
                btnFinalizar.disabled = true;
                btnFinalizar.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Processando...';

                setTimeout(function() {
                    alert('Pedido finalizado com sucesso!\n\nNúmero do pedido: #' + Math.floor(Math.random() * 100000) + '\n\nVocê receberá um email com os detalhes do pedido.');
                    // Redirecionar para página de confirmação ou limpar carrinho
                    window.location.href = 'index.php?url=home';
                }, 2000);
            });

            // Inicializar
            togglePaymentForms();
            atualizarTotal();
        });
    </script>
</body>

</html>