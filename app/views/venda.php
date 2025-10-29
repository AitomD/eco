<?php
require_once __DIR__ . '/../model/Admin.php';
require_once __DIR__ . '/../core/user.php';
require_once __DIR__ . '/../model/Loja.php';
require_once __DIR__ . '/../model/Venda.php';

$idAdmin = Auth::getAdminId();

if (!$idAdmin) {
    echo "Admin não encontrado ou usuário não logado.";
    exit;
}

$lojaModel = new Loja();
$loja = $lojaModel->buscarPorAdminId($idAdmin);

$vendaModel = new Venda();
$vendas = $vendaModel->buscarVendasPorAdmin($idAdmin);

?>

<div class="container-fluid text-center">
    <?php if ($loja): ?>
        <h3>Dados da loja</h3>
        <h5 class="card-title">
            Loja: <span class="text-primary"><?= htmlspecialchars($loja['nome']) ?></span>
        </h5>
        <p class="card-text">
            CNPJ: <span class="text-secondary"><?= htmlspecialchars($loja['cnpj']) ?></span>
        </p>

        <hr>

        <h4>Pedidos da loja</h4>

        <?php if (!empty($vendas)): ?>
            <table class="table table-striped table-hover mt-3">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Desconto</th>
                        <th>Total Final</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendas as $venda): ?>
                        <tr>
                            <td><?= htmlspecialchars($venda['nome_cliente']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($venda['data_pedido'])) ?></td>
                            <td>
                                <select class="form-select status-venda" data-pedido-id="<?= $venda['id_pedido'] ?>" style="min-width: 140px;">
                                    <option value="pendente" <?= $venda['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                    <option value="confirmado" <?= $venda['status'] === 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                                    <option value="enviado" <?= $venda['status'] === 'enviado' ? 'selected' : '' ?>>Enviado</option>
                                    <option value="entregue" <?= $venda['status'] === 'entregue' ? 'selected' : '' ?>>Entregue</option>
                                </select>
                            </td>
                            <td>R$ <?= number_format($venda['total'], 2, ',', '.') ?></td>
                            <td>R$ <?= number_format($venda['desconto'], 2, ',', '.') ?></td>
                            <td><strong>R$ <?= number_format($venda['total_final'], 2, ',', '.') ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum pedido encontrado para esta loja.</p>
        <?php endif; ?>

    <?php else: ?>
        <p>Nenhuma loja encontrada para este admin.</p>
    <?php endif; ?>
</div>
<script>
    document.querySelectorAll('.status-venda').forEach(select => {
        select.addEventListener('change', function() {
            const idPedido = this.dataset.pedidoId;
            const novoStatus = this.value;

            fetch('../app/controller/StatusVenda.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id_pedido=${idPedido}&status=${novoStatus}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.classList.add('bg-success', 'text-white');
                        setTimeout(() => this.classList.remove('bg-success', 'text-white'), 1500);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Erro ao atualizar o status.'));
        });
    });
</script>