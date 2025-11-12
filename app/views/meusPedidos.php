<?php
// Sessão já iniciada no index.php
require_once __DIR__ . '/../model/Pedido.php';

// Verifica se o usuário está logado
$id_usuario_logado = $_SESSION['user_id'] ?? null;
$meus_pedidos = [];

if ($id_usuario_logado) {
    $pedidoModel = new Pedido();
    $meus_pedidos = $pedidoModel->buscarPorUsuario($id_usuario_logado);
}
?>

<div class="tab-pane fade overflow-y-auto" id="pane-meus-pedidos" role="tabpanel" aria-labelledby="link-meus-pedidos" tabindex="0" style="max-height:600px;">
    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
        <h2 class="h4 mb-0 fw-bold">Meus Pedidos</h2>
        <p class="text-muted">Aqui você poderá visualizar seu histórico de pedidos.</p>
        <div class="mt-4">

            <?php if (!$id_usuario_logado): ?>
                <div class="alert alert-warning" role="alert">
                    Você precisa estar logado para ver seus pedidos.
                </div>

            <?php elseif (empty($meus_pedidos)): ?>
                <div class="alert alert-info" role="alert">
                    Você ainda não fez nenhum pedido.
                </div>

            <?php else: ?>
                <?php
                function ordenarPedidosPorStatus($a, $b)
                {
                    // Define a prioridade 
                    $ordemStatus = [
                        'entregue' => 1,
                        'enviado' => 1,
                        'concluido' => 1,
                        'confirmado' => 2,
                        'processando' => 2,
                        'pendente' => 3,
                        'cancelado' => 4,
                    ];

                    $ordemA = $ordemStatus[strtolower($a['status'])] ?? 5;
                    $ordemB = $ordemStatus[strtolower($b['status'])] ?? 5;

                    if ($ordemA == $ordemB) {
                        return strtotime($b['data_pedido']) - strtotime($a['data_pedido']);
                    }

                    return $ordemA - $ordemB;
                }

                usort($meus_pedidos, 'ordenarPedidosPorStatus');
                ?>

                <div class="list-group">
                    <?php foreach ($meus_pedidos as $pedido): ?>
                        <?php
                        $status_class = '';
                        $status_texto = htmlspecialchars(ucfirst($pedido['status']));
                        $status_comparar = strtolower($pedido['status']);

                        switch ($status_comparar) {
                            case 'enviado':
                            case 'concluido':
                            case 'entregue':
                                $status_class = 'text-success';
                                break;
                            case 'confirmado':
                            case 'processando':
                                $status_class = 'text-warning';
                                break;
                            case 'pendente':
                            case 'cancelado':
                                $status_class = 'text-danger';
                                break;
                            default:
                                $status_class = 'text-muted';
                        }

                        $url_detalhes = "index.php?url=pedido-sucesso&id=" . $pedido['id_pedido'];
                        ?>

                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border rounded">
                            <div class="d-flex w-100 justify-content-between">
                                <p class="mb-1"><strong>Pedido #</strong> <span class="fw-bold"><?php echo $pedido['id_pedido']; ?></span></p>
                                <p class="mb-1"><strong>Status:</strong> <span class="fw-bold <?php echo $status_class; ?>"><?php echo $status_texto; ?></span></p>
                                <p><strong>Valor Final:</strong> R$ <?php echo number_format($pedido['total_final'], 2, ',', '.'); ?></p>
                                <small class="text-muted">
                                    <?php
                                    $data = new DateTime($pedido['data_pedido']);
                                    echo $data->format('d/m/Y H:i');
                                    ?>
                                </small>
                            </div>
                            <div class="d-flex justify-content-end align-items-end">
                                <a href="<?php echo $url_detalhes; ?>" class="btn btn-product w-25 text-center">Detalhes</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
