

<div class="success-container my-5">
    <div class="success-card">
        <div class="success-header">
            <div class="success-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <h2 class="mb-0">Pedido Realizado com Sucesso!</h2>
            <p class="mb-0">Seu pedido foi processado e confirmado</p>
        </div>

        <div class="p-4">
            <div class="pedido-info">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Número do Pedido</h6>
                        <p class="fw-bold mb-0">#<?= str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT) ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Data do Pedido</h6>
                        <p class="mb-0"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Status</h6>
                        <span class="badge bg-warning text-light">
                            <?= ucfirst($pedido['status']) ?>
                        </span>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Loja</h6>
                        <p class="mb-0"><?= htmlspecialchars($pedido['nome_loja'] ?? 'Loja Principal') ?></p>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold mt-4 mb-3">Itens do Pedido</h6>
            <div class="produtos-lista">
                <?php foreach ($produtos as $produto): ?>
                    <div class="produto-item">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1"><?= htmlspecialchars($produto['nome_produto']) ?></h6>
                                <?php if ($produto['cor']): ?>
                                    <small class="text-muted">Cor: <?= htmlspecialchars($produto['cor']) ?></small>
                                <?php endif; ?>
                                <div class="small text-muted">
                                    Quantidade: <?= $produto['quantidade'] ?> x R$
                                    <?= number_format($produto['preco_unitario'], 2, ',', '.') ?>
                                </div>
                                <?php if ($produto['nome_loja']): ?>
                                    <small class="text-muted">
                                        Loja: <?= htmlspecialchars($produto['nome_loja']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal: </span>
                <span class="fw-bold">
                    R$ <?= number_format($pedido['total'], 2, ',', '.') ?>
                </span>
            </div>

            <?php if (!empty($pedido['desconto']) && $pedido['desconto'] > 0): ?>
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>
                        Cupom:
                    </span>
                    <span>
                        <strong><?= htmlspecialchars($pedido['codigo_cupom']) ?></strong>
                        (-R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?>)
                    </span>
                </div>
            <?php endif; ?>


            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Total Pago:</span>
                <span class="text-success">R$ <?= number_format($pedido['total_final'], 2, ',', '.') ?></span>
            </div>

            <div class="mt-4 p-3 border rounded">
                <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2"></i>Próximos Passos</h6>
                <ul class="mb-0">
                    <li>Acompanhe o status do seu pedido na área "Meus Pedidos" no perfil do usuário.</li>
                    <li>Em caso de dúvidas, entre em contato conosco.</li>
                </ul>
            </div>

            <div class="btn-group-actions mt-4">
                <a href="index.php?url=produto" class="btn btn-success w-50 mb-3">
                    <i class="bi bi-plus-circle me-2"></i>Continuar Comprando
                </a>
                <div class="d-flex col-md-6 gap-4">
                    <a href="index.php?url=meuperfil" class="btn btn-product w-50 mb-3">
                        <i class="bi bi-list-ul me-2"></i>Ver Meus Pedidos
                    </a>
                    <a href="index.php?url=produto" class="btn btn-product w-50 mb-3">
                        <i class="bi bi-house me-2"></i>Voltar à Loja
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</div>