<?php
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/user.php';

$response = ['sucesso' => false, 'erro' => ''];
$pdo = Database::conectar();

// pega o admin para passar ao sql
$adminId = Auth::getAdminId();
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new Exception("Método inválido");

    $action = $_POST['action'] ?? '';
    $id_produto = $_POST['id_produto'] ?? null;

    if (!$id_produto) throw new Exception("ID do produto não informado.");

    // ==================
    // AÇÃO DE ATUALIZAR
    // ==================
    if ($action === 'update') {

        // Define @admin_id para a trigger capturar
        $stmtAdmin = $pdo->prepare("SET @admin_id = :admin_id");
        $stmtAdmin->execute(['admin_id' => $adminId]);


        $tipo = $_POST['tipo'] ?? '';
        $id_especifico = $_POST['id_especifico'] ?? null;

        // 1. Atualiza Tabela Pai (Produto)
        $sqlPai = "UPDATE produto SET nome = ?, preco = ? WHERE id_produto = ?";
        $stmtPai = $pdo->prepare($sqlPai);
        $stmtPai->execute([$_POST['nome'], $_POST['preco'], $id_produto]);

        // 2. Atualiza Tabela Filha
        if ($tipo === 'info' && $id_especifico) {
            $sqlInfo = "UPDATE produto_info SET 
                ram=?, armazenamento=?, processador=?, placa_mae=?, placa_video=?, fonte=?, cor=?, descricao=?
                WHERE id_info=?";
            $stmtInfo = $pdo->prepare($sqlInfo);
            $stmtInfo->execute([
                $_POST['ram'],
                $_POST['armazenamento'],
                $_POST['processador'],
                $_POST['placa_mae'],
                $_POST['placa_video'],
                $_POST['fonte'],
                $_POST['cor'],
                $_POST['descricao'],
                $id_especifico
            ]);
        } elseif ($tipo === 'celular' && $id_especifico) {
            $sqlCel = "UPDATE celular SET 
                ram=?, armazenamento=?, cor=?, tamanho_tela=?, processador=?, camera_traseira=?, camera_frontal=?, bateria=?
                WHERE id_celular=?";
            $stmtCel = $pdo->prepare($sqlCel);
            $stmtCel->execute([
                $_POST['ram'],
                $_POST['armazenamento'],
                $_POST['cor'],
                $_POST['tamanho_tela'],
                $_POST['processador'],
                $_POST['camera_traseira'],
                $_POST['camera_frontal'],
                $_POST['bateria'],
                $id_especifico
            ]);
        }

        $response['sucesso'] = true;

        // ==================
        // AÇÃO DE DELETAR
        // ==================
    } elseif ($action === 'delete') {

        // Precisamos descobrir os IDs filhos antes de apagar o pai
        $stmtBusca = $pdo->prepare("SELECT id_info, id_celular FROM produto WHERE id_produto = ?");
        $stmtBusca->execute([$id_produto]);
        $prod = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        // 1. Deletar produto pai (Se tiver CASCADE no banco, isso já apagaria os filhos)
        // Mas vamos garantir apagando na ordem correta para evitar erro de constraint

        // Apaga o Pai primeiro (pois é ele quem segura a FK no seu banco)
        $stmtDelPai = $pdo->prepare("DELETE FROM produto WHERE id_produto = ?");
        $stmtDelPai->execute([$id_produto]);

        // 2. Apagar os filhos órfãos
        if (!empty($prod['id_info'])) {
            $pdo->prepare("DELETE FROM produto_info WHERE id_info = ?")->execute([$prod['id_info']]);
        }
        if (!empty($prod['id_celular'])) {
            $pdo->prepare("DELETE FROM celular WHERE id_celular = ?")->execute([$prod['id_celular']]);
        }

        // Apagar imagens também (Opcional, recomendável)
        // $pdo->prepare("DELETE FROM imagem WHERE id_produto = ?")... (depende da sua estrutura)

        $response['sucesso'] = true;
    }
} catch (Exception $e) {
    $response['erro'] = $e->getMessage();
}

echo json_encode($response);
