<?php
require_once '../app/core/Database.php';

$cupons = [];

try {
    $pdo = Database::conectar();

    $query = "
        SELECT codigo, descricao, data_inicio, data_fim
        FROM cupons
        WHERE ativo = 1
          AND CURDATE() BETWEEN data_inicio AND data_fim
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $cupons = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Evite expor mensagens de erro em produção, apenas para debug
    die("Erro ao buscar cupons: " . $e->getMessage());
}
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
                            Válido de <?= date('d/m/Y', strtotime($cupom['data_inicio'])) ?>
                            até <?= date('d/m/Y', strtotime($cupom['data_fim'])) ?>
                        </div>
                        <div class="d-flex align-items-end gap-2 btn-corner">
                            <i class="bi bi-info-circle fs-5 b-btn" role="button" onclick="mostrarAlerta()"></i>
                            <button class="btn btn-primary apply-btn">Aplicar</button>
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
                <p class="text-muted">Nenhum cupom disponível no momento.</p>
            </div>
        <?php endif; ?>
    </div>
</main>