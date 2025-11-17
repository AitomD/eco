<?php
require_once __DIR__ . '/../model/Admin.php';
require_once __DIR__ . '/../core/user.php';
require_once __DIR__ . '/../model/Loja.php';
require_once __DIR__ . '/../model/Produto.php';

// Admin logado
$idAdmin = Auth::getAdminId();

if (!$idAdmin) {
    die("Admin não logado.");
}

// Buscar loja do admin
$lojaModel = new Loja();
$loja = $lojaModel->buscarPorAdminId($idAdmin);

$idLoja = $loja['id_loja'];

// Buscar produtos da loja
$produtoModel = new Produto();
$produtos = $produtoModel->buscarPorLoja($idLoja);

?>

<h3 class="text-center mt-3">Produtos da loja: 
    <span class="text-primary"><?= htmlspecialchars($loja['nome_loja']) ?></span>
</h3>

<div class="overflow-y-auto" style="max-height:600px;">
<table class="table table-striped table-hover table-bordered mt-3 text-center align-middle">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Cor</th>
            <th>Data de modificação</th>
            <th>Info</th>
            <th>Deletar</th>
        </tr>
    </thead>

    <tbody>
    <?php if (!empty($produtos)): ?>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nome']) ?></td>

                <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>

                <td><?= htmlspecialchars($p['cor']) ?></td>

                <td><?= date('d/m/Y H:i', strtotime($p['data_att'])) ?></td>

                <td>
                    <button class="btn btn-sm " data-id="<?= $p['id_produto'] ?>">
                        <i class="bi bi-list fs-3"></i>
                    </button>
                </td>

                <td>
                    <button class="btn btn-sm btn-delete" data-id="<?= $p['id_produto'] ?>">
                        <i class="bi bi-trash fs-3"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="text-center text-muted">
                Nenhum produto encontrado para esta loja.
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
</div>