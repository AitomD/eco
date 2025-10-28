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
    /* Estilos para os cards de opção de entrega.
           Usamos 'has' para mudar a borda quando o input dentro dele estiver checado.
        */
    .shipping-option {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    /* Quando o input dentro da label está checado, muda a borda da label */
    .shipping-option:has(input:checked) {
        border-color: #3483fa;
        /* Azul do Mercado Livre */
        border-width: 2px;
        padding: calc(1.25rem - 1px);
        /* Compensa a borda mais grossa */
    }

    /* Garante que o input de rádio real fique alinhado */
    .shipping-option .form-check-input {
        margin-top: 0.25em;
    }

    span,
    h5,
    h6 {
        color: var(--black);
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
            <div class="step active">
                <div class="step-number">
                    <i class="bi bi-truck"></i>
                </div>
                <span class="step-label">Entrega</span>
            </div>
            <div class="step-line"></div>
            <div class="step pending">
                <div class="step-number">
                    <i class="bi bi-credit-card"></i>
                </div>
                <span class="step-label">Pagamento</span>
            </div>
        </div>
    </div>

    <main class="container py-4 mt-3 bg-white my-3">
        <div class="row g-4">
            <div class="col-lg-7">


                <div class="bg-white rounded shadow p-4">
                    <h5 class="fw-bold mb-4 text-ml-dark">Escolha a forma de entrega</h5>
                    <label for="mudaEndereco" class="shipping-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="entrega-option" id="mudaEndereco" checked>
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Alterar meu endereço</span>
                                </div>
                            </div>

                        </div>
                    </label>

                    <label for="envEndereco" class="shipping-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="entrega-option" id="envEndereco" checked>
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Enviar no meu endereço</span>
                                    <span class="text-muted" style="font-size: 0.9em;">Terra Boa - CEP 87240000</span>
                                </div>
                            </div>
                            <span class="fw-bold text-primary mx-3">Grátis</span>
                        </div>
                    </label>

                    <label for="retiraAgencia" class="shipping-option mb-0">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="entrega-option" id="retiraAgencia">
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Retirada na Agência HAFTECH</span>
                                </div>
                            </div>
                            <span class="fw-bold text-primary mx-3">Grátis</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white rounded shadow p-4" style="position: sticky; top: 20px;">
                    <h6 class="fw-bold mb-3">Resumo da compra</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Produto</span>
                        <span class="text-muted">R$ <?= number_format($valoresCarrinho['valor_original'], 2, ',', '.') ?></span>
                    </div>
                    
                    <?php if ($cupomAplicado && $valoresCarrinho['desconto'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-success">Desconto (<?= htmlspecialchars($cupomAplicado['codigo']) ?>):</span>
                            <span class="text-success">-R$ <?= number_format($valoresCarrinho['desconto'], 2, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Frete</span>
                        <span class="fw-bold text-primary">GRÁTIS</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span class="text-ml-dark">Total</span>
                        <span class="text-ml-dark">R$ <?= number_format($valoresCarrinho['valor_final'], 2, ',', '.') ?></span>
                    </div>
                </div>
                <div class="text-center mt-4 w-100">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?url=carrinho" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Voltar ao Carrinho
                        </a>
                        <button id="btn-continuar" class="btn btn-primary">
                            Finalizar Pedido <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="enderecoModal" tabindex="-1" aria-labelledby="enderecoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enderecoModalLabel">Alterar meu endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-novo-endereco">
                        <div class="row g-3">
                           
                            <div class="col-md-6"><label for="cep" class="form-label">CEP</label><input type="text" class="form-control" id="cep" placeholder="00000-000" required></div>
                            <div class="col-md-9"><label for="rua" class="form-label">Rua / Logradouro</label><input type="text" class="form-control" id="rua" required></div>
                            <div class="col-md-3"><label for="numero" class="form-label">Número</label><input type="text" class="form-control" id="numero" required></div>
                            <div class="col-md-6"><label for="complemento" class="form-label">Complemento <span class="text-muted">(Opcional)</span></label><input type="text" class="form-control" id="complemento"></div>
                            <div class="col-md-6"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" required></div>
                            <div class="col-md-8"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" required></div>
                            <div class="col-md-4"><label for="estado" class="form-label">Estado</label><input type="text" class="form-control" id="estado" required></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="form-novo-endereco">Salvar endereço</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnContinuar = document.getElementById('btn-continuar');
        
        btnContinuar.addEventListener('click', function() {
            // Verificar se uma opção de entrega foi selecionada
            const entregaSelecionada = document.querySelector('input[name="entrega-option"]:checked');
            
            if (!entregaSelecionada) {
                alert('Por favor, selecione uma opção de entrega.');
                return;
            }
            
            // Redirecionar para a página de método de pagamento
            window.location.href = 'index.php?url=metodopagamento';
        });
        
        // Abrir modal de endereço quando selecionar "Alterar meu endereço"
        const mudaEndereco = document.getElementById('mudaEndereco');
        if (mudaEndereco) {
            mudaEndereco.addEventListener('change', function() {
                if (this.checked) {
                    const modal = new bootstrap.Modal(document.getElementById('enderecoModal'));
                    modal.show();
                }
            });
        }
    });
    </script>
</body>

</html>