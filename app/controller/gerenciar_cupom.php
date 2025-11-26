<?php
/**
 * Controller para Gerenciar Cupons
 * Funções: atualizar_cupom
 * Acesso restrito: Apenas admin com cargo 'Desenvolvedor'
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../model/Admin.php';

// Iniciar sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Função: Validar Permissão do Usuário
 */
function validarPermissao()
{
    $userId = $_SESSION['id_user'] ?? $_SESSION['user_id'] ?? null;

    if (empty($userId)) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
        exit;
    }

    $adminModel = new Admin();
    if (!$adminModel->isAdminDesenvolvedor($userId)) {
        echo json_encode(['success' => false, 'message' => 'Permissão negada. Apenas desenvolvedores podem gerenciar cupons.']);
        exit;
    }
}

/**
 * Função: Atualizar Cupom Existente (Inclui ativar/desativar)
 */
function atualizarCupom()
{
    try {
        // Validar permissão
        validarPermissao();

        // Receber e validar dados
        $id_cupom = isset($_POST['id_cupom']) ? (int)$_POST['id_cupom'] : null;
        $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
        $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
        $tipo_desconto = isset($_POST['tipo_desconto']) ? trim($_POST['tipo_desconto']) : 'porcentagem';
        $valor_desconto = isset($_POST['valor_desconto']) ? $_POST['valor_desconto'] : null;
        $uso_total = isset($_POST['uso_total']) ? (int)$_POST['uso_total'] : null;
        $uso_user = isset($_POST['uso_user']) ? (int)$_POST['uso_user'] : null;
        $data_inicio = isset($_POST['data_inicio']) && !empty($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
        $data_fim = isset($_POST['data_fim']) && !empty($_POST['data_fim']) ? $_POST['data_fim'] : null;
        
        // Aqui é onde o status é definido (1 = Ativo, 0 = Inativo)
        $ativo = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;

        // Validações básicas
        if (!$id_cupom || $id_cupom <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID do cupom inválido.']);
            exit;
        }

        if (empty($codigo) || $valor_desconto === null || $valor_desconto === '') {
            echo json_encode(['success' => false, 'message' => 'Campos obrigatórios faltando (código ou valor).']);
            exit;
        }

        $pdo = Database::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        // Verificar se o cupom existe
        $checkCupom = $pdo->prepare('SELECT id_cupom FROM cupons WHERE id_cupom = :id LIMIT 1');
        $checkCupom->execute([':id' => $id_cupom]);
        if (!$checkCupom->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode(['success' => false, 'message' => 'Cupom não encontrado.']);
            exit;
        }

        // Verificar se o novo código já existe (em outro cupom)
        $checkCodigo = $pdo->prepare('SELECT id_cupom FROM cupons WHERE codigo = :codigo AND id_cupom != :id LIMIT 1');
        $checkCodigo->execute([':codigo' => $codigo, ':id' => $id_cupom]);
        if ($checkCodigo->fetch(PDO::FETCH_ASSOC)) {
            echo json_encode(['success' => false, 'message' => 'Código já está em uso em outro cupom.']);
            exit;
        }

        // Atualizar cupom
        $sql = "UPDATE cupons SET
                    codigo = :codigo,
                    descricao = :descricao,
                    tipo_desconto = :tipo_desconto,
                    valor_desconto = :valor_desconto,
                    uso_total = :uso_total,
                    uso_user = :uso_user,
                    data_inicio = :data_inicio,
                    data_fim = :data_fim,
                    ativo = :ativo
                WHERE id_cupom = :id";

        $stmt = $pdo->prepare($sql);

        $params = [
            ':id' => $id_cupom,
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
            // Mensagem genérica pois pode ter atualizado dados ou apenas o status
            echo json_encode(['success' => true, 'message' => 'Cupom atualizado com sucesso.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Nenhuma alteração foi feita ao cupom.']);
        }

    } catch (Exception $e) {
        error_log('gerenciar_cupom.php - atualizarCupom erro: ' . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao atualizar cupom.',
            'error' => $e->getMessage()
        ]);
    }
}

/**
 * Router: Determinar qual ação executar
 */
$acao = isset($_POST['acao']) ? trim($_POST['acao']) : '';

switch ($acao) {
    case 'atualizar':
        atualizarCupom();
        break;
    
    // Caso alguém tente chamar 'excluir' via API antiga, retornará erro
    default:
        echo json_encode(['success' => false, 'message' => 'Ação não reconhecida.']);
        exit;
}
?>