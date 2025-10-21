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
                case 'logout':
                    $auth->logout();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit;
                    break;
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
    // PROCESSO DE REGISTRO COMPLETO
    // ================================
    
    public function processRegister() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            return;
        }

        try {
            $firstName = trim($_POST['firstName'] ?? '');
            $lastName = trim($_POST['lastName'] ?? '');
            $email = trim(strtolower($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';
            $birthDate = $_POST['birthDate'] ?? '';
            $terms = isset($_POST['terms']);

            $errors = [];

            if (empty($firstName)) {
                $errors[] = 'Nome é obrigatório';
            } elseif (strlen($firstName) < 2) {
                $errors[] = 'Nome deve ter pelo menos 2 caracteres';
            }

            if (empty($lastName)) {
                $errors[] = 'Sobrenome é obrigatório';
            } elseif (strlen($lastName) < 2) {
                $errors[] = 'Sobrenome deve ter pelo menos 2 caracteres';
            }

            if (empty($email)) {
                $errors[] = 'Email é obrigatório';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email inválido';
            }

            if (empty($password)) {
                $errors[] = 'Senha é obrigatória';
            } elseif (strlen($password) < 8) {
                $errors[] = 'Senha deve ter pelo menos 8 caracteres';
            }

            if ($password !== $confirmPassword) {
                $errors[] = 'As senhas não coincidem';
            }

            if (!$terms) {
                $errors[] = 'Você deve aceitar os termos de uso';
            }

            if (empty($birthDate)) {
                $errors[] = 'Data de nascimento é obrigatória';
            }

            if (!empty($errors)) {
                echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
                return;
            }

            $userData = [
                'nome' => $firstName . ' ' . $lastName,
                'email' => $email,
                'senha' => password_hash($password, PASSWORD_DEFAULT),
                'is_admin' => false,
                'data_nascimento' => $birthDate
            ];

            if ($this->emailExists($email)) {
                echo json_encode(['success' => false, 'message' => 'Este email já está cadastrado']);
                return;
            }

            // INSERT sem o campo genero
            $stmt = $this->pdo->prepare("
                INSERT INTO user (nome, email, senha, is_admin, data_nascimento) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $userData['nome'],
                $userData['email'],
                $userData['senha'],
                $userData['is_admin'] ? 1 : 0,
                $userData['data_nascimento']
            ]);

            if ($result) {
                $userId = $this->pdo->lastInsertId();
                $userData['id_user'] = $userId;

                $this->createSession($userData);

                echo json_encode([
                    'success' => true, 
                    'redirect' => '../public/index.php?url=home'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao criar conta. Tente novamente.']);
            }

        } catch (Exception $e) {
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
?>
