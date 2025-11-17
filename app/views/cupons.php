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

<div class=" bg-light w-100 py-2">

    <div class="d-flex justify-content-center align-items-center ">
        <h3 class="fw-semibold mb-1 " style="color: var(--black);">Meus Cupons</h3>
    </div>

</div>

<main class="container py-4 h-auto ">

    <div class="row g-3">

        <?php if (!empty($cupons)): ?>
            <?php foreach ($cupons as $cupom): ?>

                <div class="col-md-4">
                    <div class="coupon-card p-3 border rounded shadow-sm">
                        <h6><?= htmlspecialchars($cupom['codigo']) ?></h6>
                        <div class="coupon-meta"><?= htmlspecialchars($cupom['descricao']) ?></div>
                        <div class="coupon-meta">
                            <?php if (!empty($cupom['data_inicio']) && !empty($cupom['data_fim'])): ?>
                                Válido de <?= date('d/m/Y', strtotime($cupom['data_inicio'])) ?>
                                até <?= date('d/m/Y', strtotime($cupom['data_fim'])) ?>
                            <?php elseif (!empty($cupom['data_fim'])): ?>
                                Válido até <?= date('d/m/Y', strtotime($cupom['data_fim'])) ?>
                            <?php else: ?>
                                Cupom válido
                            <?php endif; ?>
                        </div>
                        <div class="coupon-meta">
                            <small>
                                <?php if ($cupom['tipo_desconto'] === 'porcentagem'): ?>
                                    Desconto: <?= $cupom['valor_desconto'] ?>%
                                <?php else: ?>
                                    Desconto: R$ <?= number_format($cupom['valor_desconto'], 2, ',', '.') ?>
                                <?php endif; ?>
                            </small>
                        </div>
                        <div class="d-flex align-items-end gap-2 btn-corner">
                            <i class="bi bi-info-circle fs-5 b-btn" role="button" onclick="mostrarAlerta()"></i>
                            <button class="btn btn-primary apply-btn"
                                onclick="aplicarCupom('<?= htmlspecialchars($cupom['codigo']) ?>')">
                                Aplicar no Carrinho
                            </button>
                        </div>
                    </div>
                </div> <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($isDesenvolvedor): ?>
            <div class="col-md-4">
                <div class="carrinho-container card  text-light 
                    d-flex flex-column justify-content-center align-items-center" id="btn-abrir-modal-cupom"
                    style=" min-height: 250px; cursor: pointer; ">

                    <i class="bi bi-plus-circle" style="font-size: 3rem;"></i>
                    <h6 class="mt-2">Adicionar Novo Cupom</h6>
                </div>
            </div>
        <?php endif; ?>


        <?php if (empty($cupons) && !$isDesenvolvedor): ?>
            <div class="col-12">
                <div class="alert alert-danger">
                    <p class="text-center me-auto"><strong>Nenhum cupom disponível no momento.</strong></p>
                </div>
            </div>
        <?php endif; ?>


        <div id="alert-info"
            class="alert alert-info alert-dismissible fade show position-fixed bottom-0 end-0 m-3 d-none" role="alert"
            style="z-index: 1055; max-width: 600px;">
            <strong>Informação:</strong> Só pode ser utilizado apenas 1 cupom por compra!
            <button type="button" class="btn-close" onclick="fecharAlerta()" aria-label="Close"></button>
        </div>

    </div>

</main>

<div class="modal fade " id="modalAdicionarCupom" tabindex="-1" aria-labelledby="modalAdicionarCupomLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg ">

        <div class="modal-content p-3">

            <div class="modal-header">
                <h5 class="modal-title " id="modalAdicionarCupomLabel">Adicionar Novo Cupom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formAdicionarCupom">
                <div class="mb-3">
                    <label for="codigo" class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" name="codigo"
                        placeholder="Nome / código de identificação do cupom" required>
                </div>

                <div class="mb-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <input type="text" class="form-control" id="descricao" name="descricao"
                        placeholder="Uma pequena descrição do cupom">
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tipo_desconto" class="form-label">Tipo Desconto</label>
                            <select class="form-select" id="tipo_desconto" name="tipo_desconto" required>
                                <option value="" selected disabled>Selecione...</option>
                                <option value="porcentagem">Porcentagem</option>
                                <option value="valor">Valor Fixo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="valor_desconto" class="form-label">Valor</label>
                            <input type="number" class="form-control" id="valor_desconto" name="valor_desconto" min="0"
                                 placeholder="Valor de desconto" required>
                        </div>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="uso_total" class="form-label">Uso Total</label>
                            <input type="number" class="form-control" id="uso_total" name="uso_total" min="1"
                                placeholder="Quantos podem usar" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="uso_user" class="form-label">Uso por Usuário</label>
                            <input type="number" class="form-control" id="uso_user" name="uso_user" min="1"
                                placeholder="Usos por pessoa" required>
                        </div>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="data_fim" class="form-label">Data de Encerramento</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Cancelar</button>

                    <button type="submit" class="btn-product w-50" id="btnSalvarCupom">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>