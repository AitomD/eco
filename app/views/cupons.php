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

    /* --- Card Estilo Ticket --- */
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
        /* Removida qualquer opacidade padrão */
        opacity: 1 !important;
    }

    .coupon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .coupon-header {
        /* Fundo extremamente claro para destacar o topo, mas sem escurecer */
        background-color: #f8f9fa;
        padding: 1.5rem 1rem;
        text-align: center;
        border-bottom: 2px dashed #dee2e6;
        position: relative;
    }

    /* Detalhe das bolinhas (picote) */
    .coupon-header::before,
    .coupon-header::after {
        content: '';
        position: absolute;
        bottom: -10px;
        width: 20px;
        height: 20px;
        background-color: #f8f9fa;
        border-radius: 50%;
        z-index: 2;
    }

    .coupon-header::before {
        left: -10px;
    }

    .coupon-header::after {
        right: -10px;
    }

    .discount-badge {
        font-size: 2rem;
        font-weight: 800;
        color: var(--pmain);
        line-height: 1;
    }

    .discount-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        font-weight: 600;
    }

    .coupon-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    /* Área do Código */
    .code-container {
        background: #ffffff;
        border: 2px dashed var(--pmain);
        border-radius: 8px;
        padding: 0.75rem;
        margin: 1rem 0;
        text-align: center;
        cursor: pointer;
        transition: background 0.2s;
    }

    .code-container:hover {
        background: #f8f9fa;
    }

    .coupon-code-text {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        font-size: 1.2rem;
        color: #343a40;
    }

    .copy-hint {
        font-size: 0.7rem;
        color: var(--pmain);
        display: block;
        margin-top: 2px;
    }

    .coupon-dates {
        font-size: 0.8rem;
        color: #6c757d;
        border-top: 1px solid #f1f3f5;
        padding-top: 0.75rem;
        margin-top: auto;
    }

    /* Card Admin */
    .add-coupon-card {
        height: 100%;
        min-height: 300px;
        border: 2px dashed #dee2e6;
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 12px;
        transition: all 0.3s ease;
        color: #adb5bd;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .add-coupon-card:hover {
        border-color: var(--pmain);
        color: var(--pmain);
        background-color: #fff;
    }
</style>

<div class="container pt-5 pb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom pb-3">
        <div>
            <h2 class="fw-bold mb-0 text-light"><i class="bi bi-ticket-perforated-fill me-2"></i>Meus Cupons</h2>
            <p class="mb-0 mt-1 text-white-50">Gerencie e aplique descontos exclusivos na sua compra.</p>
        </div>
    </div>
</div>

<main class="container py-4 h-auto">
    <div class="row g-4">

        <?php if (!empty($cupons)): ?>
            <?php foreach ($cupons as $cupom): ?>
                <?php
                // --- TRATAMENTO DE DADOS (Preparo para o HTML) ---
        
                // 1. Formatar Valor do Desconto
                $valorFormatado = '';
                if ($cupom['tipo_desconto'] == 'porcentagem') {
                    $valorFormatado = number_format($cupom['valor_desconto'], 0) . '%';
                    $tipoLabel = 'DE DESCONTO';
                } else {
                    $valorFormatado = 'R$ ' . number_format($cupom['valor_desconto'], 2, ',', '.');
                    $tipoLabel = 'DE CRÉDITO';
                }

                // 2. Formatar Datas
                $dataInicio = date('d/m/Y', strtotime($cupom['data_inicio']));
                $dataFim = date('d/m/Y', strtotime($cupom['data_fim']));


                ?>

                <div class="col-md-6 col-lg-4 <?= $classeOpacidade ?>">
                    <div class="coupon-card">

                        <div class="coupon-header">
                            <div class="discount-badge"><?= $valorFormatado ?></div>
                            <div class="discount-label"><?= $tipoLabel ?></div>
                        </div>

                        <div class="coupon-body">
                            <p class="text-center text-secondary mb-2 small">
                                <?= htmlspecialchars($cupom['descricao']) ?>
                            </p>

                            <div class="code-container " onclick="copiarCodigo('<?= $cupom['codigo'] ?>', this)"
                                style="cursor: pointer;">
                                <div class="coupon-code-text ">
                                    <i class="bi bi-scissors me-1 small opacity-50"></i>
                                    <?= strtoupper(htmlspecialchars($cupom['codigo'])) ?>
                                </div>
                                <small class="copy-hint" style="transition: all 0.3s;">Clique para copiar</small>
                            </div>

                            <div class="coupon-dates d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-calendar-check me-1"></i> Validade:
                                </div>
                                <div class="fw-bold text-dark">
                                    Até <?= $dataFim ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($isDesenvolvedor): ?>
                            <div class="coupon-footer text-center py-2 border-top bg-light">
                                <i class="bi bi-pencil-square fs-5 btn-editar-cupom text-dark" style="cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#modalGerenciarCupom" data-id="<?= $cupom['id'] ?? $cupom['id_cupom'] ?>"
                                    data-codigo="<?= htmlspecialchars($cupom['codigo'] ?? '') ?>"
                                    data-descricao="<?= htmlspecialchars($cupom['descricao'] ?? '') ?>"
                                    data-tipo="<?= $cupom['tipo_desconto'] ?? 'valor' ?>"
                                    data-valor="<?= $cupom['valor_desconto'] ?? '0' ?>"
                                    data-inicio="<?= isset($cupom['data_inicio']) ? date('Y-m-d\TH:i', strtotime($cupom['data_inicio'])) : '' ?>"
                                    data-fim="<?= isset($cupom['data_fim']) ? date('Y-m-d\TH:i', strtotime($cupom['data_fim'])) : '' ?>"
                                    data-ativo="<?= $cupom['ativo'] ?? '1' ?>" title="Editar Cupom">
                                </i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($isDesenvolvedor): ?>
            <div class="col-md-6 col-lg-4">
                <div class="add-coupon-card" data-bs-toggle="modal" data-bs-target="#modalAdicionarCupom">
                    <i class="bi bi-plus-circle display-4 mb-3"></i>
                    <h5 class="fw-bold">Criar Novo Cupom</h5>
                    <p class="small px-4 text-center">Clique para configurar um novo código promocional.</p>
                </div>
            </div>
        <?php endif; ?>

    </div>

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

<!-----MODAL DE ADIÇÃO DE CUPOM(SOMENTE ADMIN COM CARGO = 'Desenvolvedor')------>
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

<!---MODAL DE EDIÇÃO DE CUPOM JÁ EXISTENTE--->
<div class="modal fade" id="modalGerenciarCupom" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Editar Cupom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditarCupom">
                <div class="modal-body">
                    <input type="hidden" name="id_cupom" id="modalId">
                    <input type="hidden" name="acao" value="atualizar">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label ">Código</label>
                            <input type="text" class="form-control" name="codigo" id="modalCodigo" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ">Tipo</label>
                            <select class="form-select" name="tipo_desconto" id="modalTipo" required>
                                <option value="porcentagem">Porcentagem</option>
                                <option value="valor">Valor Fixo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label ">Valor</label>
                            <input type="number" step="0.01" class="form-control" name="valor_desconto" id="modalValor"
                                required>
                        </div>

                        <div class="col-12">
                            <label class="form-label ">Descrição</label>
                            <textarea class="form-control" name="descricao" id="modalDescricao" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label ">Data Início</label>
                            <input type="datetime-local" class="form-control" name="data_inicio" id="modalInicio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label ">Data Fim</label>
                            <input type="datetime-local" class="form-control" name="data_fim" id="modalFim">
                        </div>

                        <div class="col-12">
                            <label class="form-label ">Ativo ?</label>
                            <select class="form-select" name="ativo" id="modalAtivo" required>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Salvar Alterações
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * ====================================================================
     * 1. FUNÇÕES AUXILIARES (DEFINIDAS NO ESCOPO GLOBAL)
     * ====================================================================
     */

    // Função auxiliar: Copiar código para a área de transferência
    function copiarCodigo(codigo, elemento) {
        navigator.clipboard.writeText(codigo).then(() => {
            const hint = elemento.querySelector('.copy-hint');
            const textoOriginal = hint.textContent;
            hint.textContent = 'Copiado!';
            setTimeout(() => {
                hint.textContent = textoOriginal;
            }, 1500);
        });
    }

    // Função auxiliar: Fechar alerta
    function fecharAlerta() {
        const alerta = document.getElementById('alert-info');
        if (alerta) {
            alerta.classList.add('d-none');
        }
    }

    /**
     * ====================================================================
     * 2. LÓGICA DE INICIALIZAÇÃO (DOM CONTENT LOADED)
     * ====================================================================
     */
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos do DOM
        const modalElement = document.getElementById('modalGerenciarCupom');
        const formEditar = document.getElementById('formEditarCupom');
        
        // Nota: O botão btnExcluir foi removido do JS pois a exclusão física não é mais permitida.
        // Certifique-se de remover o botão <button id="btnExcluir"> do seu HTML também.

        // Cria/Obtém a instância do Bootstrap para o Modal
        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalElement);

        // Evento: Abrir modal com dados do cupom selecionado
        modalElement.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            // Extrair informações dos atributos data-*
            const id = button.getAttribute('data-id');
            const codigo = button.getAttribute('data-codigo');
            const descricao = button.getAttribute('data-descricao');
            const tipo = button.getAttribute('data-tipo');
            const valor = button.getAttribute('data-valor');
            const inicio = button.getAttribute('data-inicio');
            const fim = button.getAttribute('data-fim');
            const ativo = button.getAttribute('data-ativo');

            // Preencher campos do formulário no modal
            document.getElementById('modalId').value = id;
            document.getElementById('modalCodigo').value = codigo;
            document.getElementById('modalDescricao').value = descricao;
            document.getElementById('modalTipo').value = tipo;
            document.getElementById('modalValor').value = valor;
            document.getElementById('modalInicio').value = inicio;
            document.getElementById('modalFim').value = fim;
            document.getElementById('modalAtivo').value = ativo;
        });

        // Evento: Submissão do formulário de edição (Inclui Atualizar Status Ativo/Inativo)
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            // Garante que a ação de atualização seja enviada
            formData.append('acao', 'atualizar');

            fetch('../app/controller/gerenciar_cupom.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Cupom atualizado com sucesso!');
                        
                        // Fechar modal usando a instância
                        modalInstance.hide();
                        
                        // Recarregar página
                        location.reload();
                    } else {
                        alert('❌ Erro: ' + (data.message || 'Falha ao atualizar cupom.'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('❌ Erro ao atualizar cupom. Verifique o console.');
                });
        });

    }); // <--- FIM DO DOMContentLoaded
</script>