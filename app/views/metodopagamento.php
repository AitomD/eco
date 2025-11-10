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

<style>
    .payment-option {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    .payment-option:has(input:checked) {
        border-color: #3483fa;
        border-width: 2px;
        padding: calc(1.25rem - 1px);
    }

    .payment-option .form-check-input {
        margin-top: 0.25em;
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 3rem;
        padding: 2rem 0;
        background: linear-gradient(135deg, rgba(97, 0, 148, 0.1), rgba(0, 123, 255, 0.1));
        border-radius: 15px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0 1.5rem;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        font-weight: bold;
        font-size: 1.2rem;
        position: relative;
        z-index: 2;
        transition: all 0.4s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    
    .step-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .step-label {
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .step.completed .step-number {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        transform: scale(1.1);
        animation: pulse-completed 2s infinite;
    }
    
    .step.completed .step-icon {
        color: #28a745;
        transform: scale(1.1);
    }
    
    .step.completed .step-label {
        color: #28a745;
        font-weight: 700;
    }
    
    .step.active .step-number {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        transform: scale(1.2);
        animation: pulse-active 1.5s infinite;
        box-shadow: 0 0 25px rgba(0, 123, 255, 0.5);
    }
    
    .step.active .step-icon {
        color: #007bff;
        transform: scale(1.2);
    }
    
    .step.active .step-label {
        color: #007bff;
        font-weight: 700;
    }
    
    .step.pending .step-number {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .step.pending .step-icon {
        color: #6c757d;
    }
    
    .step.pending .step-label {
        color: #6c757d;
    }
    
    .step-line {
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #dee2e6, #adb5bd);
        margin: 0 1rem;
        border-radius: 2px;
        position: relative;
        top: -25px;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    .step.completed + .step-line {
        background: linear-gradient(90deg, #28a745, #20c997);
        box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
    }
    
    @keyframes pulse-active {
        0% { box-shadow: 0 0 25px rgba(0, 123, 255, 0.5); }
        50% { box-shadow: 0 0 35px rgba(0, 123, 255, 0.8); }
        100% { box-shadow: 0 0 25px rgba(0, 123, 255, 0.5); }
    }
    
    @keyframes pulse-completed {
        0% { transform: scale(1.1); }
        50% { transform: scale(1.15); }
        100% { transform: scale(1.1); }
    }
    
    @media (max-width: 768px) {
        .step {
            margin: 0 0.5rem;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .step-label {
            font-size: 0.8rem;
        }
        
        .step-line {
            width: 40px;
            top: -20px;
        }
    }

    .credit-card-form {
        display: none;
        margin-top: 2rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .pix-info {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        text-align: center;
    }

    .boleto-info {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    span, h5, h6 {
        color: var(--black);
    }
</style>

<body>
    <!-- Indicador de progresso -->
    <div class="container py-4 mt-3">
        <div class="step-indicator">
            <div class="step completed">
                <div class="step-number">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="step-label">Carrinho</span>
            </div>
            <div class="step-line"></div>
            <div class="step completed">
                <div class="step-number">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <span class="step-label">Entrega</span>
            </div>
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">
                    <i class="bi bi-credit-card"></i>
                </div>
                <span class="step-label">Pagamento</span>
            </div>
        </div>
    </div>

    <main class="container py-4 mt-3 bg-white my-3">
        <?php if ($erroCompra): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($erroCompra) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>1
        <?php endif; ?>
        
        <form id="form-pagamento" method="POST" action="">
            <input type="hidden" name="finalizar_compra" value="1">
            <div class="row g-4">
    <main class="container py-4 mt-3 bg-white my-3">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="bg-white rounded shadow p-4">
                    <h5 class="fw-bold mb-4 text-ml-dark">Escolha a forma de pagamento</h5>
                    
                    <!-- PIX -->
                    <label for="pix" class="payment-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="payment-option" id="pix" checked>
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">PIX</span>
                                    <span class="text-muted" style="font-size: 0.9em;">Aprovação imediata</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success">À vista</span>
                                <div class="text-muted" style="font-size: 0.9em;">5% de desconto</div>
                            </div>
                        </div>
                        <div id="pix-info" class="pix-info">
                            <i class="bi bi-qr-code" style="font-size: 2rem; color: #007bff;"></i>
                            <p class="mt-2 mb-1"><strong>Pagamento via PIX</strong></p>
                            <p class="text-muted small">Após confirmar o pedido, você receberá o código PIX para pagamento.</p>
                        </div>
                    </label>



                    <!-- Cartão de Débito -->
                    <label for="debito" class="payment-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="payment-option" id="debito">
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Cartão de Débito</span>
                                    <span class="text-muted" style="font-size: 0.9em;">Débito imediato</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success">À vista</span>
                                <div class="text-muted" style="font-size: 0.9em;">3% de desconto</div>
                            </div>
                        </div>
                    </label>

                    <!-- Boleto -->
                    <label for="boleto" class="payment-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="payment-option" id="boleto">
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Boleto Bancário</span>
                                    <span class="text-muted" style="font-size: 0.9em;">Vencimento em 3 dias úteis</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success">À vista</span>
                                <div class="text-muted" style="font-size: 0.9em;">2% de desconto</div>
                            </div>
                        </div>
                        <div id="boleto-info" class="boleto-info">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text text-primary me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <p class="mb-1"><strong>Pagamento via Boleto</strong></p>
                                    <p class="text-muted small mb-0">O boleto será enviado por email e poderá ser pago em qualquer banco, lotérica ou pelo internet banking.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    
                    <label for="credito" class="payment-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="payment-option" id="credito">
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Cartão de Crédito</span>
                                    <span class="text-muted" style="font-size: 0.9em;">Até 12x sem juros</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-credit-card text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <div id="credit-card-form" class="credit-card-form">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="card-number" class="form-label">Número do cartão</label>
                                    <input type="text" class="form-control" id="card-number" placeholder="0000 0000 0000 0000" maxlength="19">
                                </div>
                                <div class="col-md-6">
                                    <label for="card-name" class="form-label">Nome no cartão</label>
                                    <input type="text" class="form-control" id="card-name" placeholder="Nome completo">
                                </div>
                                <div class="col-md-3">
                                    <label for="card-expiry" class="form-label">Validade</label>
                                    <input type="text" class="form-control" id="card-expiry" placeholder="MM/AA" maxlength="5">
                                </div>
                                <div class="col-md-3">
                                    <label for="card-cvv" class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="card-cvv" placeholder="000" maxlength="4">
                                </div>
                                <div class="col-12">
                                    <label for="installments" class="form-label">Parcelas</label>
                                    <select class="form-select" id="installments">
                                        <option value="1">1x de R$ <?= number_format($valoresCarrinho['valor_final'], 2, ',', '.') ?> à vista</option>
                                        <option value="2">2x de R$ <?= number_format($valoresCarrinho['valor_final'] / 2, 2, ',', '.') ?> sem juros</option>
                                        <option value="3">3x de R$ <?= number_format($valoresCarrinho['valor_final'] / 3, 2, ',', '.') ?> sem juros</option>
                                        <option value="6">6x de R$ <?= number_format($valoresCarrinho['valor_final'] / 6, 2, ',', '.') ?> sem juros</option>
                                        <option value="12">12x de R$ <?= number_format($valoresCarrinho['valor_final'] / 12, 2, ',', '.') ?> sem juros</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php?url=paginaRetirada" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Voltar
                    </a>
                    <button id="btn-finalizar" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Finalizar Compra
                    </button>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white rounded shadow p-4" style="position: sticky; top: 20px;">
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
                        <span>R$ <?= number_format($valoresCarrinho['valor_original'], 2, ',', '.') ?></span>
                    </div>
                    
                    <?php if ($cupomAplicado && $valoresCarrinho['desconto'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">Desconto cupom (<?= htmlspecialchars($cupomAplicado['codigo']) ?>):</span>
                            <span class="text-success">-R$ <?= number_format($valoresCarrinho['desconto'], 2, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between mb-2" id="desconto-pagamento" style="display: none;">
                        <span class="text-success">Desconto no pagamento:</span>
                        <span class="text-success" id="desconto-valor">-R$ 0,00</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Frete:</span>
                        <span class="text-success">GRÁTIS</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span class="text-ml-dark">Total</span>
                        <span class="text-ml-dark" id="total-final">R$ <?= number_format($valoresCarrinho['valor_final'], 2, ',', '.') ?></span>
                    </div>

                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-shield-check text-success me-2"></i>
                            <span class="fw-bold text-success">Compra 100% Segura</span>
                        </div>
                        <small class="text-muted">
                            Seus dados estão protegidos e a transação é segura.
                        </small>
                    </div>
                </div>
            </div>
        </div>
        </form>
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
            'pix': 0.05,      // 5%
            'credito': 0,     // 0%
            'debito': 0.03,   // 3%
            'boleto': 0.02    // 2%
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
