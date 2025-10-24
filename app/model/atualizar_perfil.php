<?php
// Inicia a sessão (se você usa sessões para verificar login)
session_start(); 

require_once(__DIR__ . '/../core/Database.php');

try {
    $database = new Database(); // Instancia a classe
    $conn = $database->conectar(); // Pega a conexão (que é um objeto PDO)
} catch (Exception $e) {
    // Se a conexão falhar, envia um erro
    $response = ['success' => false, 'message' => 'Erro de conexão: ' . $e->getMessage()];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Prepara um array para a resposta JSON
$response = ['success' => false, 'message' => ''];

// Verifica se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Coleta e limpa os dados do formulário
    $id_user = $_POST['id_user'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $data_nascimento = $_POST['data_nascimento'];
    $senha_nova = $_POST['senha_nova'];
    $senha_confirmar = $_POST['senha_confirmar'];

    // 2. Validações básicas
    if (empty($nome) || empty($email) || empty($id_user)) {
        $response['message'] = 'Nome e Email são obrigatórios.';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Formato de email inválido.';
        echo json_encode($response);
        exit;
    }

    // 3. Validação de Senha (se o usuário tentou alterar)
    $update_senha = false;
    if (!empty($senha_nova)) {
        if ($senha_nova !== $senha_confirmar) {
            $response['message'] = 'As senhas não conferem.';
            echo json_encode($response);
            exit;
        }
        // Hash da nova senha
        $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
        $update_senha = true;
    }

    // 4. Validação de Email Duplicado (Versão PDO)
    // Usando 'user' como nome da tabela, conforme seu print
    $stmt_check = $conn->prepare("SELECT id_user FROM user WHERE email = ? AND id_user != ?");
    
    // Em PDO, os parâmetros são passados no execute()
    $stmt_check->execute([$email, $id_user]);

    // fetch() retorna a linha se encontrar, ou 'false' se não
    if ($stmt_check->fetch()) {
        $response['message'] = 'Este email já está sendo usado por outra conta.';
        echo json_encode($response);
        $stmt_check->closeCursor(); // Em PDO, usa-se closeCursor()
        $conn = null; // Em PDO, fecha-se a conexão atribuindo null
        exit;
    }
    // Limpa o statement para a próxima query
    $stmt_check->closeCursor();


    // 5. Constrói e Executa a Query UPDATE (Versão PDO)
    try {
        if ($update_senha) {
            // Se a senha for alterada
            $sql = "UPDATE user SET nome = ?, email = ?, data_nascimento = ?, senha = ? WHERE id_user = ?";
            $stmt = $conn->prepare($sql);
            // Passa os parâmetros como um array para o execute()
            $stmt->execute([$nome, $email, $data_nascimento, $senha_hash, $id_user]);
        } else {
            // Se a senha NÃO for alterada
            $sql = "UPDATE user SET nome = ?, email = ?, data_nascimento = ? WHERE id_user = ?";
            $stmt = $conn->prepare($sql);
            // Passa os parâmetros como um array para o execute()
            $stmt->execute([$nome, $email, $data_nascimento, $id_user]);
        }

        // Se o execute() não lançou uma exceção, deu certo
        $response['success'] = true;
        $response['message'] = 'Dados atualizados com sucesso!';

        $stmt->closeCursor();

    } catch (PDOException $e) { // Captura erros específicos do PDO
        $response['message'] = 'Erro no banco de dados: ' . $e->getMessage();
    }

    $conn = null; // Fecha a conexão PDO

} else {
    $response['message'] = 'Método de requisição inválido.';
}

// Retorna a resposta em JSON para o JavaScript
header('Content-Type: application/json');
echo json_encode($response);
?>