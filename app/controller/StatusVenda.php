<?php
require_once __DIR__ . '/../core/user.php';
require_once __DIR__ . '/../model/Venda.php';

header('Content-Type: application/json');

$idAdmin = Auth::getAdminId();
if (!$idAdmin) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

$idPedido = $_POST['id_pedido'] ?? null;
$novoStatus = $_POST['status'] ?? null;

if (!$idPedido || !$novoStatus) {
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
    exit;
}

$vendaModel = new Venda();
$ok = $vendaModel->atualizarStatus($idPedido, $idAdmin, $novoStatus);

echo json_encode([
    'success' => $ok,
    'message' => $ok ? 'Status atualizado com sucesso!' : 'Falha ao atualizar status.'
]);
exit;

