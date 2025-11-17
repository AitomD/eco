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


// ===================================================================
// ADICIONE ESTE BLOCO PARA LER O ERRO DA SESSÃO
// ===================================================================
$erroCompra = null;
if (isset($_SESSION['erro_compra'])) {
    $erroCompra = $_SESSION['erro_compra'];
    // Limpa o erro da sessão para não mostrá-lo novamente
    unset($_SESSION['erro_compra']);
}
// ===================================================================

?>


<body>
    <!-- ... -->

    <body>

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

        <main class="container py-4 mt-3 bg-dark my-3">
            <?php if ($erroCompra): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Alerta:</strong> <?= htmlspecialchars($erroCompra) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form id="form-pagamento" method="POST" action="">
                <input type="hidden" name="finalizar_compra" value="1">
                <div class="row g-4">
                    <div class="col-lg-7">

                        <div class="bg-dark border border-secondary rounded p-4 shadow" style="background:#161616;">

                            <h5 class="fw-bold mb-4 text-white">Escolha a forma de pagamento</h5>

                            <!-- PIX -->
                            <label for="pix" class="payment-option w-100 py-3 px-3 rounded mb-3"
                                style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                                <div class="d-flex justify-content-between">

                                    <div class="d-flex">
                                        <input class="form-check-input me-3" type="radio" name="payment-option" id="pix"
                                            checked>
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

                                <div id="pix-info" class="pix-info mt-3 p-3 rounded text-center"
                                    style="display:none; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:white;">
                                    <i class="bi bi-qr-code bg-white p-2 rounded-2 fs-1"
                                        style="font-size:2rem; color:var(--black);"></i>
                                    <p class="mt-2 mb-1"><strong>Pagamento via PIX</strong></p>
                                    <p class="text-light small">Após confirmar o pedido, você receberá o QR Code para
                                        pagamento.</p>
                                </div>

                            </label>

                            <!-- DÉBITO -->
                            <label for="debito" class="payment-option w-100 py-3 px-3 rounded mb-3"
                                style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <input class="form-check-input me-3" type="radio" name="payment-option"
                                            id="debito">
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
                            <label for="boleto" class="payment-option w-100 py-3 px-3 rounded mb-3"
                                style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <input class="form-check-input me-3" type="radio" name="payment-option"
                                            id="boleto">
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

                                <div id="boleto-info" class="boleto-info mt-3 p-3 rounded"
                                    style="display:none; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:white;">

                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-text text-primary me-3"
                                            style="font-size:2rem;"></i>
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
                            <label for="credito" class="payment-option w-100 py-3 px-3 rounded mb-3"
                                style="background:rgba(255,255,255,0.05); cursor:pointer; border:1px solid rgba(255,255,255,0.15);">

                                <div class="d-flex justify-content-between">

                                    <div class="d-flex">
                                        <input class="form-check-input me-3" type="radio" name="payment-option"
                                            id="credito">

                                        <div>
                                            <span class="fw-bold text-white d-block">Cartão de Crédito</span>
                                            <span class="text-light" style="font-size:0.9em;">Até 12x sem juros</span>
                                        </div>
                                    </div>

                                    <i class="bi bi-credit-card text-primary" style="font-size:1.5rem;"></i>

                                </div>

                                <!-- FORM CARTÃO -->
                                <div id="credit-card-form" class="credit-card-form mt-3 p-3 rounded"
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
                                            <select class="form-select bg-dark text-white border-secondary"
                                                id="installments"></select>
                                        </div>

                                    </div>

                                </div>

                            </label>

                        </div>

                        <!-- BOTÕES -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php?url=paginaRetirada" class="btn btn-outline-secondary"
                                style="border-color:#444; color:#ccc;">
                                <i class="bi bi-arrow-left me-2"></i>Voltar
                            </a>


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

                                    <img src="<?= htmlspecialchars($item['imagem']) ?>" class="img-fluid rounded me-3"
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
                                <span class="text-white">R$
                                    <?= number_format($valoresCarrinho['valor_original'], 2, ',', '.') ?></span>
                            </div>

                            <?php if ($cupomAplicado && $valoresCarrinho['desconto'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-success">Desconto (<?= $cupomAplicado['codigo'] ?>):</span>
                                    <span class="text-success">-R$
                                        <?= number_format($valoresCarrinho['desconto'], 2, ',', '.') ?></span>
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

                            <button id="btn-finalizar" class="btn-product w-100 mt-3"
                                style="background:var(--pmain); color:white; border:none;">
                                <div class="d-flex " style="gap: 80px;">
                                    <div class="justify-content-start">
                                        <i class="bi bi-check-circle "></i>
                                    </div>
                                    <div class="text-center mx-5">Finalizar Compra</div>
                                </div>
                            </button>

                        </div>
                    </div>

                </div>
            </form>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const paymentOptions = document.querySelectorAll('input[name="payment-option"]');
                const creditCardForm = document.getElementById('credit-card-form');
                const pixInfo = document.getElementById('pix-info');
                const boletoInfo = document.getElementById('boleto-info');
                const totalFinal = document.getElementById('total-final');
                const btnFinalizar = document.getElementById('btn-finalizar');

                const baseTotal = <?= $valoresCarrinho['valor_final'] ?>;

                // Tabela de descontos
                const descontos = {
                    'pix': 0.05,
                    'debito': 0.03,
                    'boleto': 0.02,
                    'credito': 0
                };

                // Atualiza o total de acordo com o método selecionado
                function atualizarTotal() {
                    const paymentSelected = document.querySelector('input[name="payment-option"]:checked');
                    if (!paymentSelected) return;

                    const desconto = descontos[paymentSelected.id] || 0;
                    const valorDesconto = baseTotal * desconto;
                    const novoTotal = baseTotal - valorDesconto;

                    // Atualiza total na tela
                    totalFinal.textContent = 'R$ ' + novoTotal.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    // Atualiza parcelas do cartão
                    const installmentsSelect = document.getElementById('installments');
                    if (installmentsSelect) {
                        installmentsSelect.innerHTML = `
                <option value="1">1x de R$ ${novoTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })} à vista</option>
                <option value="2">2x de R$ ${(novoTotal / 2).toLocaleString('pt-BR', { minimumFractionDigits: 2 })} sem juros</option>
                <option value="3">3x de R$ ${(novoTotal / 3).toLocaleString('pt-BR', { minimumFractionDigits: 2 })} sem juros</option>
                <option value="6">6x de R$ ${(novoTotal / 6).toLocaleString('pt-BR', { minimumFractionDigits: 2 })} sem juros</option>
                <option value="12">12x de R$ ${(novoTotal / 12).toLocaleString('pt-BR', { minimumFractionDigits: 2 })} sem juros</option>
            `;
                    }
                }

                // Mostra/esconde blocos conforme o pagamento selecionado
                function togglePaymentForms() {
                    creditCardForm.style.display = 'none';
                    pixInfo.style.display = 'none';
                    boletoInfo.style.display = 'none';

                    const selected = document.querySelector('input[name="payment-option"]:checked');
                    if (!selected) return;

                    if (selected.id === 'credito') creditCardForm.style.display = 'block';
                    if (selected.id === 'pix') pixInfo.style.display = 'block';
                    if (selected.id === 'boleto') boletoInfo.style.display = 'block';
                }

                // Eventos para troca de pagamento
                paymentOptions.forEach(option => {
                    option.addEventListener('change', function () {
                        togglePaymentForms();
                        atualizarTotal();
                    });
                });

                // Máscaras
                const cardNumber = document.getElementById('card-number');
                const cardExpiry = document.getElementById('card-expiry');
                const cardCvv = document.getElementById('card-cvv');

                if (cardNumber) {
                    cardNumber.addEventListener('input', e => {
                        let value = e.target.value.replace(/\D/g, '').substring(0, 16);
                        e.target.value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                    });
                }

                if (cardExpiry) {
                    cardExpiry.addEventListener('input', e => {
                        let value = e.target.value.replace(/\D/g, '').substring(0, 4);
                        if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2);
                        e.target.value = value;
                    });
                }

                if (cardCvv) {
                    cardCvv.addEventListener('input', e => {
                        e.target.value = e.target.value.replace(/\D/g, '').substring(0, 4);
                    });
                }

                // Botão Finalizar
                btnFinalizar.addEventListener('click', function (e) {
                    e.preventDefault();
                    const paymentSelected = document.querySelector('input[name="payment-option"]:checked');

                    if (!paymentSelected) {
                        alert('Por favor, selecione uma forma de pagamento.');
                        return;
                    }

                    // Validação de cartão de crédito
                    if (paymentSelected.id === 'credito') {
                        const cardNumberVal = cardNumber.value.replace(/\s/g, '');
                        const cardName = document.getElementById('card-name').value.trim();
                        const cardExpiryVal = cardExpiry.value.trim();
                        const cardCvvVal = cardCvv.value.trim();

                        if (!cardNumberVal || cardNumberVal.length < 16) {
                            alert('Número do cartão inválido.');
                            return;
                        }
                        if (!cardName) {
                            alert('Informe o nome no cartão.');
                            return;
                        }
                        if (!/^\d{2}\/\d{2}$/.test(cardExpiryVal)) {
                            alert('Validade inválida. Use o formato MM/AA.');
                            return;
                        }
                        if (cardCvvVal.length < 3) {
                            alert('CVV inválido.');
                            return;
                        }
                    }

                    // Bloqueia botão e envia o form
                    btnFinalizar.disabled = true;
                    btnFinalizar.innerHTML = '<div class="spinner-border spinner-border-sm me-2" role="status"></div>Processando...';

                    document.getElementById('form-pagamento').submit();
                    
                });

                // Inicializa o estado correto ao carregar
                togglePaymentForms();
                atualizarTotal();
            });
        </script>

    </body>