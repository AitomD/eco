<?php
require_once __DIR__ . '/../controller/cupons-carrinho.php';
require_once __DIR__ . '/../model/Admin.php';

// Obter cupons disponíveis usando a classe
$cupons = CuponsCarrinhoController::getCuponsDisponiveis();

// Verificar se o usuário é admin com cargo desenvolvedor
$isDesenvolvedor = false;
$userId = $_SESSION['id_user'] ?? $_SESSION['user_id'] ?? null;

if (!empty($userId)) {
    try {
        $admin = new Admin();
        $isDesenvolvedor = $admin->isAdminDesenvolvedor($userId);
    } catch (Exception $e) {
        error_log("Erro ao verificar admin: " . $e->getMessage());
        $isDesenvolvedor = false;
    }
}
?>

<style>
    @import url(main.css);

    /* Card Estilo Ticket Moderno */
    .coupon-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-left: 5px solid var(--pmain);
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .coupon-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .coupon-header {
        background-color: rgba(13, 110, 253, 0.05);
        padding: 1.25rem;
        text-align: center;
        border-bottom: 1px dashed #dee2e6;
    }

    .discount-badge {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-color);
    }

    .coupon-body {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .coupon-code {
        background: #f1f3f5;
        border: 1px dashed #adb5bd;
        padding: 0.5rem;
        border-radius: 6px;
        font-family: monospace;
        font-weight: 700;
        font-size: 1.1rem;
        color: #495057;
        letter-spacing: 1px;
        text-align: center;
        margin-bottom: 1rem;
        cursor: pointer;
    }

    /* Card de Adicionar (Admin) */
    .add-coupon-card {
        height: 100%;
        min-height: 280px;
        border: 2px dashed #dee2e6;
        background-color: #fcfcfc;
        border-radius: 12px;
        transition: all 0.2s ease;
        color: #6c757d;
    }

    .add-coupon-card:hover {
        border-color: var(--pmain);
        color: var(--pmain);
        background-color: rgba(13, 110, 253, 0.03);
    }

    /* Ajustes Gerais */
    .text-small-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>

<div class="container pt-5 pb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom pb-3">
        <div>
            <h2 class="fw-bold mb-0 text-light"><i class="bi bi-ticket-perforated me-2"></i>Meus Cupons</h2>
            <p class=" mb-0 mt-1 text-light">Gerencie e aplique descontos exclusivos.</p>
        </div>
    </div>
</div>

<main class="container py-4 h-auto">

    <div class="row g-4">

        <?php if (!empty($cupons)): ?>
            <?php foreach ($cupons as $cupom): ?>
                <div class="col-md-6 col-lg-4" style="max-width:300px;">

                    <div class="coupon-card shadow-sm h-100 d-flex flex-column">

                        <div class="coupon-header">
                        </div>

                        <div class="coupon-body flex-grow-1 overflow-y-auto" style="max-height: 200px;">
                            <div class="text-center mb-3">
                                <?= htmlspecialchars($cupom['descricao']) ?>
                            </div>

                            <div class="d-flex ...">
                            </div>
                        </div>

                        <div class="mt-auto border-top pt-2">
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($isDesenvolvedor): ?>
            <div class="col-md-6 col-lg-4">
                <div class="add-coupon-card d-flex flex-column justify-content-center align-items-center p-4"
                    id="btn-abrir-modal-cupom" style="cursor: pointer;" data-bs-toggle="modal"
                    data-bs-target="#modalAdicionarCupom">
                    <div class="mb-3 p-3 rounded-circle bg-light shadow-sm">
                        <i class="bi bi-plus-lg fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Criar Novo Cupom</h6>
                    <small class="text-center text-muted px-3">Adicione um novo código promocional ao sistema.</small>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($cupons) && !$isDesenvolvedor): ?>
            <div class="col-12">
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-ticket-perforated display-1 opacity-25"></i>
                    <h4 class="mt-3 fw-normal">Nenhum cupom disponível</h4>
                    <p>Fique atento, novas promoções podem surgir a qualquer momento!</p>
                </div>
            </div>
        <?php endif; ?>

        <div id="alert-info"
            class="alert alert-info shadow-lg border-0 alert-dismissible fade show position-fixed bottom-0 end-0 m-4 d-none"
            role="alert" style="z-index: 1055; max-width: 400px; border-left: 5px solid #0dcaf0;">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>Atenção:</strong><br>
                    Apenas 1 cupom por compra!
                </div>
            </div>
            <button type="button" class="btn-close" onclick="fecharAlerta()" aria-label="Close"></button>
        </div>

    </div>
</main>

<div class="modal fade" id="modalAdicionarCupom" tabindex="-1" aria-labelledby="modalAdicionarCupomLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header bg-light border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalAdicionarCupomLabel">Adicionar Novo Cupom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="formAdicionarCupom" class="row g-3">

                    <div class="col-md-12">
                        <label for="codigo" class="form-label fw-semibold text-secondary small">IDENTIFICAÇÃO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" class="form-control form-control-lg" id="codigo" name="codigo"
                                placeholder="Ex: VERAO2025" style="text-transform: uppercase;" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="descricao" class="form-label fw-semibold text-secondary small">DESCRIÇÃO</label>
                        <input type="text" class="form-control" id="descricao" name="descricao"
                            placeholder="Breve descrição do benefício">
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="col-md-6">
                        <label for="tipo_desconto" class="form-label fw-semibold text-secondary small">TIPO DE
                            DESCONTO</label>
                        <select class="form-select" id="tipo_desconto" name="tipo_desconto" required>
                            <option value="" selected disabled>Selecione...</option>
                            <option value="porcentagem">Porcentagem (%)</option>
                            <option value="valor">Valor Fixo (R$)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="valor_desconto" class="form-label fw-semibold text-secondary small">VALOR DO
                            DESCONTO</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-cash-coin"></i></span>
                            <input type="number" class="form-control" id="valor_desconto" name="valor_desconto" min="0"
                                step="0.01" placeholder="0.00" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="uso_total" class="form-label fw-semibold text-secondary small">LIMITE TOTAL DE
                            USOS</label>
                        <input type="number" class="form-control" id="uso_total" name="uso_total" min="1"
                            placeholder="Ex: 100">
                    </div>
                    <div class="col-md-6">
                        <label for="uso_user" class="form-label fw-semibold text-secondary small">LIMITE POR
                            USUÁRIO</label>
                        <input type="number" class="form-control" id="uso_user" name="uso_user" min="1"
                            placeholder="Ex: 1">
                    </div>

                    <div class="col-md-6">
                        <label for="data_inicio" class="form-label fw-semibold text-secondary small">INÍCIO DA
                            VALIDADE</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                    </div>
                    <div class="col-md-6">
                        <label for="data_fim" class="form-label fw-semibold text-secondary small">FIM DA
                            VALIDADE</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim">
                    </div>

                    <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border px-4"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btnSalvarCupom">
                            <i class="bi bi-check-lg me-1"></i> Salvar Cupom
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>