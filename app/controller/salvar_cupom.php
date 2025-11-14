<?php
// Define que a resposta será um JSON
header('Content-Type: application/json');


require_once __DIR__ . '/../core/Database.php';

// Iniciar sessão (se ainda não foi iniciada) e carregar modelo Admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/Admin.php';

// Verificar permissão: somente admins com cargo 'Desenvolvedor' podem adicionar cupons
$userId = $_SESSION['id_user'] ?? $_SESSION['user_id'] ?? null;
if (empty($userId)) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$adminModel = new Admin();
if (!$adminModel->isAdminDesenvolvedor($userId)) {
    echo json_encode(['success' => false, 'message' => 'Permissão negada. Somente desenvolvedores podem adicionar cupons.']);
    exit;
}

// Recebe dados com proteção (evita notices)
$codigo = trim($_POST['codigo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$tipo_desconto = trim($_POST['tipo_desconto'] ?? 'porcentagem');
$valor_desconto = $_POST['valor_desconto'] ?? null;
$uso_total = isset($_POST['uso_total']) ? (int)$_POST['uso_total'] : null;
$uso_user = isset($_POST['uso_user']) ? (int)$_POST['uso_user'] : null;
$data_inicio = !empty($_POST['data_inicio']) ? $_POST['data_inicio'] : null; // Permite datas nulas
$data_fim = !empty($_POST['data_fim']) ? $_POST['data_fim'] : null;
$ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;

// Validações básicas
if ($codigo === '' || $valor_desconto === null || $valor_desconto === '') {
    echo json_encode(['success' => false, 'message' => 'Campos obrigatórios faltando (código ou valor).']);
    exit;
}

try {
    $pdo = Database::conectar();
    // Garantir que PDO lance exceções em erros (ajuda no debug)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    // Verificar se código já existe (unicidade)
    $check = $pdo->prepare('SELECT id FROM cupons WHERE codigo = :codigo LIMIT 1');
    $check->execute([':codigo' => $codigo]);
    if ($check->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(['success' => false, 'message' => 'Código já existe.']);
        exit;
    }

    $sql = "INSERT INTO cupons
                (codigo, descricao, tipo_desconto, valor_desconto, uso_total, uso_user, data_inicio, data_fim, ativo, criado_em)
            VALUES
                (:codigo, :descricao, :tipo_desconto, :valor_desconto, :uso_total, :uso_user, :data_inicio, :data_fim, :ativo, NOW())";

    $stmt = $pdo->prepare($sql);

    $params = [
        ':codigo' => $codigo,
        ':descricao' => $descricao,
        ':tipo_desconto' => $tipo_desconto,
        ':valor_desconto' => $valor_desconto,
        ':uso_total' => $uso_total,
        ':uso_user' => $uso_user,
        ':data_inicio' => $data_inicio,
        ':data_fim' => $data_fim,
        ':ativo' => $ativo
    ];

    $ok = $stmt->execute($params);

    if ($ok) {
        echo json_encode(['success' => true, 'message' => 'Cupom adicionado com sucesso.']);
    } else {
        $err = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Erro ao inserir cupom: ' . ($err[2] ?? 'erro desconhecido')]);
    }

} catch (Exception $e) {
    // Log completo para servidor
    error_log('salvar_cupom.php erro: ' . $e->getMessage());
    // Retornar também a mensagem de erro para ajudar debug local
    echo json_encode([
        'success' => false,
        'message' => 'Erro de conexão ou execução. Verifique logs.',
        'error' => $e->getMessage()
    ]);
}

?>