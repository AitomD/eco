<?php
// Define o tipo de conteúdo da resposta como JSON
header('Content-Type: application/json');

// Requer o arquivo de configuração do banco de dados. 
// ATENÇÃO: Verifique se este caminho está correto em seu projeto.
require __DIR__ . '/../core/Database.php';

// Inicialização da resposta padrão
$response = ['success' => false, 'message' => ''];

// ** 1. CONFIGURAÇÃO E CONEXÃO COM O BANCO DE DADOS **
// ATENÇÃO: Substitua pelos seus dados de conexão reais.
$host = 'localhost'; 
$db   = 'ecommerce'; // Baseado no contexto anterior
$user = 'root'; 
$pass = ''; // Senha vazia, comum em ambientes XAMPP/WAMP
$charset = 'utf8mb4'; 

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Define o modo de busca padrão como array associativo
    PDO::ATTR_EMULATE_PREPARES => false, // Usa prepared statements nativos para segurança
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Retorna erro de conexão e encerra
    $response['message'] = "Erro de conexão com o banco de dados: " . $e->getMessage();
    echo json_encode($response);
    exit;
}

// ** 2. CAPTURA E VALIDAÇÃO DA REQUISIÇÃO **
// Verifica se o método de requisição é POST, conforme esperado pelo AJAX
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Método de requisição inválido. Apenas POST é permitido.';
    echo json_encode($response);
    exit;
}

// Filtra e limpa os dados do POST (assumindo campos do formulário meuperfil.php)
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT); 
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$data_nascimento = filter_input(INPUT_POST, 'data_nascimento', FILTER_SANITIZE_SPECIAL_CHARS); 
$senha_nova = filter_input(INPUT_POST, 'senha_nova');
$senha_confirmar = filter_input(INPUT_POST, 'senha_confirmar');

// Validação básica dos dados essenciais
if (!$user_id || !$nome || !$email || !$data_nascimento) {
    $response['message'] = 'Dados obrigatórios (ID, Nome, Email ou Data de Nascimento) ausentes ou inválidos.';
    echo json_encode($response);
    exit;
}

// Validação de senhas: se uma nova senha foi fornecida, ela deve coincidir com a confirmação
if (!empty($senha_nova)) {
    if ($senha_nova !== $senha_confirmar) {
        $response['message'] = 'A nova senha e a confirmação não coincidem.';
        echo json_encode($response);
        exit;
    }
    // Criptografa a senha para armazenamento seguro
    $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
}

// ** 3. MONTAGEM DA CONSULTA SQL (Prepared Statement) **
// Define a parte inicial do SQL e os parâmetros básicos
$sql = "UPDATE user SET nome = ?, email = ?, data_nascimento = ?";
$params = [$nome, $email, $data_nascimento];

// Adiciona a senha à consulta e aos parâmetros se ela foi alterada
if (!empty($senha_nova)) {
    $sql .= ", senha = ?";
    $params[] = $senha_hash; 
}

// Adiciona a cláusula WHERE para garantir que apenas o usuário correto seja atualizado
$sql .= " WHERE id_user = ?";
$params[] = $user_id; 

// ** 4. EXECUÇÃO DA CONSULTA **
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Verifica se alguma linha foi realmente alterada no banco de dados
    if ($stmt->rowCount() > 0) {
        $response['success'] = true;
        $response['message'] = 'Perfil e dados atualizados com sucesso!';
    } else {
        // Retorna sucesso (pois a operação não falhou), mas informa que nada mudou
        $response['success'] = true; 
        $response['message'] = 'Nenhuma alteração detectada ou usuário não encontrado.';
    }
} catch (\PDOException $e) {
    // Captura erros específicos da execução do SQL (ex: erro de sintaxe, coluna inexistente)
    $response['message'] = "Erro na execução SQL: " . $e->getMessage();
}

// Retorna a resposta final em formato JSON
echo json_encode($response);
?>
