<?php
require_once __DIR__ . '/Database.php';

class Auth {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::conectar();
        $this->startSession();
    }

    // ================================
    // MÉTODOS ESTÁTICOS PRINCIPAIS
    // ================================
    
    private static function startSessionSafe() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function handleRequest() {
        $auth = new self();
        
        // Verificar se é uma requisição de status de autenticação
        if (isset($_GET['check_auth'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'logged_in' => self::isUserLoggedIn(),
                'user_data' => self::getCurrentUserData()
            ]);
            exit;
        }
        
        // Processar ações POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            
            // Detectar ação pelos campos do formulário se não houver action explícita
            if (empty($action)) {
                if (isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['firstName'])) {
                    $action = 'login';
                } elseif (isset($_POST['firstName']) && isset($_POST['lastName'])) {
                    $action = 'register';
                }
            }
            
            switch($action) {
                case 'login':
                    $auth->processLogin();
                    break;
                case 'register':
                    $auth->processRegister();
                    break;
                case 'check_email':
                    $auth->checkEmailExists();
                    break;
                case 'logout':
                    $auth->logout();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit;
                
                default:
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Ação não identificada']);
                    break;
            }
        }
    }
    
    public static function isUserLoggedIn() {
        self::startSessionSafe();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public static function getCurrentUserData() {
        self::startSessionSafe();
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? null,
            'email' => $_SESSION['user_email'] ?? null,
            'is_admin' => $_SESSION['is_admin'] ?? false
        ];
    }
    // ================================
    // MÉTODOS DE INSTÂNCIA
    // ================================

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    private function createSession($userData) {
        $_SESSION['user_id'] = $userData['id_user'];
        $_SESSION['user_name'] = $userData['nome'] ?? $userData['name'] ?? $userData['usuario'] ?? 'Usuário';
        $_SESSION['user_email'] = $userData['email'];
        $_SESSION['is_admin'] = (bool)$userData['is_admin'];
    }
    
     public static function getAdminId()
{
    self::startSessionSafe();

    if (!isset($_SESSION['user_id'])) {
        return null; // Usuário não logado
    }

    require_once __DIR__ . '/../model/Admin.php';
    $adminModel = new Admin();
    return $adminModel->getIdAdminByUser($_SESSION['user_id']);
}
    public function logout() {
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 42000, '/');
        }
        
        session_destroy();
    }

    // ================================
    // MÉTODOS AUXILIARES
    // ================================
    
    public function findUserByEmail($email) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return null;
        }
    }
    
    public function findUserById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id_user = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return null;
        }
    }
    
    public function emailExists($email, $excludeId = null) {
        try {
            $sql = "SELECT id_user FROM user WHERE email = ?";
            $params = [$email];
            
            if ($excludeId) {
                $sql .= " AND id_user != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Erro ao verificar email: " . $e->getMessage());
            return true;
        }
    }

    // ================================
    // VERIFICAÇÃO DE EMAIL EXISTENTE
    // ================================
    
    public function checkEmailExists() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }
        
        try {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email)) {
                echo json_encode(['exists' => false]);
                return;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['exists' => false]);
                return;
            }
            
            $exists = $this->emailExists($email);
            echo json_encode(['exists' => $exists]);
            
        } catch (Exception $e) {
            error_log("Erro ao verificar email: " . $e->getMessage());
            echo json_encode(['exists' => false]);
        }
    }

    // ================================
    // PROCESSO DE REGISTRO COMPLETO
    // ================================
    
    public function processRegister() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        // 1. Coletar TODOS os dados (usuário e endereço)
        // Assumindo que seu formulário agora envia esses campos de endereço.
        // Ajuste os nomes (ex: 'cep', 'cidade') se forem diferentes no seu formulário.
        try {
            // Dados do Usuário
            $firstName = trim($_POST['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? '');
            $email = trim(strtolower($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            $birthDate = $_POST['birthDate'] ?? '';
            $terms = isset($_POST['terms']);

            // Dados do Endereço
            $endereco = trim($_POST['endereco'] ?? '');
            $cep = trim($_POST['cep'] ?? '');
            $complemento = trim($_POST['complemento'] ?? '');
            $bairro = trim($_POST['bairro'] ?? '');
            $cidade = trim($_POST['cidade'] ?? '');
            $estado = trim($_POST['estado'] ?? ''); // Ex: 'SP'

            // 2. Validações
            $errors = [];

            // Validações do Usuário (como no seu código original)
            if (empty($firstName)) $errors[] = 'Nome é obrigatório';
            elseif (strlen($firstName) < 2) $errors[] = 'Nome deve ter pelo menos 2 caracteres';

            if (empty($lastName)) $errors[] = 'Sobrenome é obrigatório';
            elseif (strlen($lastName) < 2) $errors[] = 'Sobrenome deve ter pelo menos 2 caracteres';

            if (empty($email)) $errors[] = 'Email é obrigatório';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';

            if (empty($password)) $errors[] = 'Senha é obrigatória';
            elseif (strlen($password) < 8) $errors[] = 'Senha deve ter pelo menos 8 caracteres';

            if ($password !== $confirmPassword) $errors[] = 'As senhas não coincidem';
            if (!$terms) $errors[] = 'Você deve aceitar os termos de uso';
            if (empty($birthDate)) $errors[] = 'Data de nascimento é obrigatória';

            // TODO: Adicionar validações para os campos de endereço se necessário
            // Ex: if (empty($cep)) $errors[] = 'CEP é obrigatório';
            // Ex: if (empty($endereco)) $errors[] = 'Endereço é obrigatório';


            if (!empty($errors)) {
                echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
                return;
            }

            // 3. Verificação de Email (antes de iniciar a transação)
            if ($this->emailExists($email)) {
                echo json_encode(['success' => false, 'message' => 'Este email já está cadastrado']);
                return;
            }

            // Dados do usuário prontos para o DB
            $userData = [
                'nome' => $firstName . ' ' . $lastName,
                'email' => $email,
                'senha' => password_hash($password, PASSWORD_DEFAULT),
                'is_admin' => false, // Padrão para novos registros
                'data_nascimento' => $birthDate
            ];

            // 4. Executar os INSERTs como uma Transação
            
            // Inicia a transação
            $this->pdo->beginTransaction();

            // 4a. Inserir na tabela 'user'
            $stmtUser = $this->pdo->prepare("
                INSERT INTO user (nome, email, senha, is_admin, data_nascimento) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $resultUser = $stmtUser->execute([
                $userData['nome'],
                $userData['email'],
                $userData['senha'],
                $userData['is_admin'] ? 1 : 0,
                $userData['data_nascimento']
            ]);

            if (!$resultUser) {
                // Se falhar no usuário, desfaz tudo
                $this->pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Erro ao criar conta. Tente novamente. (Erro User)']);
                return;
            }

            // Obter o ID do usuário recém-criado
            $userId = $this->pdo->lastInsertId();
            $userData['id_user'] = $userId; // Adiciona o ID aos dados da sessão

            // 4b. Inserir na tabela 'endereco'
            // Assumindo que 'id_endereco' é AUTO_INCREMENT
            $stmtEndereco = $this->pdo->prepare("
                INSERT INTO endereco (id_user, endereco, cep, complemento, bairro, cidade, estado)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $resultEndereco = $stmtEndereco->execute([
                $userId,        // <--- A Chave Estrangeira que faz o "link"
                $endereco,
                $cep,
                $complemento,
                $bairro,
                $cidade,
                $estado
            ]);

            if (!$resultEndereco) {
                // Se falhar no endereço, desfaz tudo (inclusive o usuário)
                $this->pdo->rollBack();
                echo json_encode(['success' => false, 'message' => 'Erro ao salvar endereço. Tente novamente. (Erro Endereco)']);
                return;
            }

            // 5. Sucesso!
            // Se chegou até aqui, os dois INSERTs funcionaram.
            $this->pdo->commit(); // Confirma as mudanças no banco

            // Cria a sessão e envia a resposta de sucesso
            $this->createSession($userData);

            echo json_encode([
                'success' => true, 
                'redirect' => '../public/index.php?url=home'
            ]);

        } catch (Exception $e) {
            // Se qualquer exceção do PDO ou outra ocorrer, desfaz tudo
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Erro no cadastro: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro inesperado. Tente novamente.']);
        }
    }
    // ================================
    // PROCESSO DE LOGIN COMPLETO
    // ================================
    
    public function processLogin() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        try {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);

            $errors = [];

            if (empty($email)) {
                $errors[] = 'Email é obrigatório';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            }

            if (empty($password)) {
                $errors[] = 'Senha é obrigatória';
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
                return;
            }

            $user = $this->findUserByEmail($email);

            if (!$user || !password_verify($password, $user['senha'])) {
                sleep(1);
                echo json_encode(['success' => false, 'message' => 'Email ou senha incorretos']);
                return;
            }

            $this->createSession($user);

            if ($remember) {
                $expires = time() + (30 * 24 * 60 * 60);
                setcookie('remember_user', $user['id_user'], $expires, '/', '', false, true);
            }

            echo json_encode([
                'success' => true, 
                'redirect' => '../public/index.php?url=home',
                'user' => [
                    'id' => $user['id_user'],
                    'name' => $user['nome'] ?? $user['name'] ?? $user['usuario'] ?? 'Usuário',
                    'email' => $user['email'],
                    'is_admin' => (bool)$user['is_admin']
                ]
            ]);

        } catch (Exception $e) {
            error_log("Erro no login: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro inesperado. Tente novamente.']);
        }
    }

}

// ================================
// AUTO-EXECUÇÃO PARA ENDPOINTS
// ================================

// Se o arquivo foi chamado diretamente (não incluído), processar requisição
if (basename($_SERVER['SCRIPT_NAME']) === 'user.php') {
    Auth::handleRequest();
}

