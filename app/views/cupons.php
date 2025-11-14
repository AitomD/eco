<?php
require_once __DIR__ . '/../controller/cupons-carrinho.php';

// Obter cupons disponíveis usando a classe
$cupons = CuponsCarrinhoController::getCuponsDisponiveis();
?>

<div class="text-start bg-light w-100 py-2">
    <h3 class="fw-semibold mb-1 text-center" style="color: var(--black);">Meus Cupons</h3>

</div>

<main class="container py-4 h-auto ">

    <div class="row g-3 ">
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
                            <button class="btn btn-primary apply-btn" onclick="aplicarCupom('<?= htmlspecialchars($cupom['codigo']) ?>')">
                                Aplicar no Carrinho
                            </button>
                        </div>
                        <div id="alert-info"
                            class="alert alert-info alert-dismissible fade show position-fixed bottom-0 end-0 m-3 d-none"
                            role="alert" style="z-index: 1055; max-width: 600px;">
                            <strong>Informação:</strong> Só pode ser utilizado apenas 1 cupom por compra!
                            <button type="button" class="btn-close" onclick="fecharAlerta()" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    <strong>Nenhum cupom disponível no momento.</strong>
                    <br>Verifique se:
                    <ul>
                        <li>O banco de dados está conectado</li>
                        <li>A tabela 'cupons' existe</li>
                        <li>Existem cupons ativos na tabela</li>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
function mostrarAlerta() {
    const alertElement = document.getElementById('alert-info');
    if (alertElement) {
        alertElement.classList.remove('d-none');
        
        // Auto-fechar após 5 segundos
        setTimeout(() => {
            alertElement.classList.add('d-none');
        }, 5000);
    }
}

function fecharAlerta() {
    const alertElement = document.getElementById('alert-info');
    if (alertElement) {
        alertElement.classList.add('d-none');
    }
}

function aplicarCupom(codigo) {
    // Desabilitar o botão durante o processamento
    const botao = event.target;
    const textoOriginal = botao.innerHTML;
    botao.disabled = true;
    botao.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Aplicando...';
    
    // Aplicar o cupom via AJAX
    const formData = new FormData();
    formData.append('ajax_cupom', '1');
    formData.append('codigo_cupom', codigo);
    
    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            // Cupom aplicado com sucesso
            botao.innerHTML = '<i class="bi bi-check-circle me-1"></i>Aplicado!';
            botao.classList.remove('btn-primary');
            botao.classList.add('btn-success');
            
            // Redirecionar para o carrinho após 1 segundo
            setTimeout(() => {
                window.location.href = 'index.php?url=carrinho';
            }, 1000);
        } else {
            // Erro ao aplicar cupom
            alert(data.mensagem || 'Erro ao aplicar cupom');
            botao.innerHTML = textoOriginal;
            botao.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
        botao.innerHTML = textoOriginal;
        botao.disabled = false;
    });
}

// Adicionar estilos para os botões durante o carregamento
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .coupon-card {
            transition: transform 0.2s ease;
        }
        .coupon-card:hover {
            transform: translateY(-2px);
        }
    `;
    document.head.appendChild(style);
});
</script>