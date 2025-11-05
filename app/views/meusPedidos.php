<div class="tab-pane fade" id="pane-meus-pedidos" role="tabpanel" aria-labelledby="link-meus-pedidos" tabindex="0">
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

                    // Pega o valor da ordem para $a e $b (usa 5 como padrão se não encontrar)
                    $ordemA = $ordemStatus[strtolower($a['status'])] ?? 5;
                    $ordemB = $ordemStatus[strtolower($b['status'])] ?? 5;

                    if ($ordemA == $ordemB) {
                        // Se a prioridade for a mesma, ordena pela data (mais recente primeiro)
                        return strtotime($b['data_pedido']) - strtotime($a['data_pedido']);
                    }

                    // Ordena pela prioridade do status (1, 2, 3...)
                    return $ordemA - $ordemB;
                }

                // Aplica a ordenação ao array $meus_pedidos
                usort($meus_pedidos, 'ordenarPedidosPorStatus');

                // --- FIM DA LÓGICA DE ORDENAÇÃO ---
                ?>

                <div class="list-group">

                    <?php foreach ($meus_pedidos as $pedido): ?>

                        <?php
                        // --- Lógica para definir a cor do status ---
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
                        ?>

                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border rounded">

                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h5>

                                <small class="text-muted">
                                    <?php
                                    $data = new DateTime($pedido['data_pedido']);
                                    echo $data->format('d/m/Y H:i');
                                    ?>
                                </small>
                            </div>

                            <p class="mb-1">
                                <strong>Status:</strong> <span class="fw-bold <?php echo $status_class; ?>">
                                    <?php echo $status_texto; ?>
                                </span>
                            </p>

                            <<p class="mb-1">
                                Vendido por: <strong><?php echo htmlspecialchars($pedido['nome_loja']); ?></strong>
                                <br>
                                <small class="text-muted">
                                    <?php echo $pedido['quantidade_itens']; ?>

                                    <?php echo ($pedido['quantidade_itens'] > 1) ? 'itens' : 'item'; ?>
                                </small>
                                </p>
                        
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <strong class="h5 mb-0">
                                        Total: R$ <?php echo number_format($pedido['total_final'], 2, ',', '.'); ?>
                                    </strong>
                                </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>