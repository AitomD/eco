<?php
require_once __DIR__ . '/Database.php';

class Auth {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::conectar();
        $this->startSession();
    }

    // ================================
    // MÉTODOS ESTÁTICOS PARA USO DIRETO
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
        
        // Detectar ação baseada na URL ou parâmetro
        $currentScript = basename($_SERVER['SCRIPT_NAME'], '.php');
        
        switch($currentScript) {
            case 'login':
                $auth->processLogin();
                break;
            case 'register':
                $auth->processRegister();
                break;
            case 'logout':
            case 'logout_new':
                $auth->logout();
                header('Location: ../public/index.php?url=home');
                exit;
                break;
            case 'user':
                // Quando chamado diretamente, verificar o método POST
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Detectar ação pelos campos do formulário
                    if (isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['firstName'])) {
                        // É um login (tem email/password, mas não firstName)
                        $auth->processLogin();
                    } elseif (isset($_POST['firstName']) && isset($_POST['lastName'])) {
                        // É um registro (tem firstName/lastName)
                        $auth->processRegister();
                    } else {
                        // Tentar detectar pela action
                        $action = $_POST['action'] ?? '';
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
                break;
            default:
                // Se não for um dos endpoints, apenas verificar se precisa processar algo
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $action = $_POST['action'] ?? '';
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
                    }
                }
                break;
        }
    }
    
    public static function quickLogin($email, $password, $remember = false) {
        $auth = new self();
        
        // Buscar usuário
        $user = $auth->findUserByEmail($email);
        
        if (!$user || !password_verify($password, $user['senha'])) {
            return [
                'success' => false,
                'message' => 'Email ou senha incorretos'
            ];
        }
        
        // Criar sessão
        $auth->createSession($user);
        
        // Cookie se solicitado
        if ($remember) {
            $expires = time() + (30 * 24 * 60 * 60);
            setcookie('remember_user', $user['id_user'], $expires, '/', '', false, true);
        }
        
        return [
            'success' => true,
            'user' => $user
        ];
    }
    
    public static function quickRegister($firstName, $lastName, $email, $password, $birthDate = null, $gender = null) {
        $auth = new self();
        
        // Verificar se email existe
        if ($auth->emailExists($email)) {
            return [
                'success' => false,
                'message' => 'Este email já está cadastrado'
            ];
        }
        
        $userData = [
            'nome' => $firstName . ' ' . $lastName,
            'email' => strtolower(trim($email)),
            'senha' => password_hash($password, PASSWORD_DEFAULT),
            'is_admin' => false,
            'genero' => in_array($gender, ['masculino', 'feminino', 'outro']) ? $gender : null
        ];
        
        // Salvar no banco
        $stmt = $auth->pdo->prepare("
            INSERT INTO user (nome, email, senha, is_admin, genero) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $result = $stmt->execute([
            $userData['nome'],
            $userData['email'],
            $userData['senha'],
            $userData['is_admin'] ? 1 : 0,
            $userData['genero']
        ]);
        
        if ($result) {
            $userData['id_user'] = $auth->pdo->lastInsertId();
            $auth->createSession($userData);
            
            return [
                'success' => true,
                'user' => $userData
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Erro ao criar conta. Tente novamente.'
        ];
    }
    
    public static function isUserLoggedIn() {
        self::startSessionSafe();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public static function getCurrentUserId() {
        self::startSessionSafe();
        return $_SESSION['user_id'] ?? null;
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
    
    public static function logoutUser() {
        self::startSessionSafe();
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        
        if (isset($_COOKIE['remember_user'])) {
            setcookie('remember_user', '', time() - 42000, '/');
        }
        
        session_destroy();
    }
    
    public static function requireUserLogin($redirectUrl = '../public/index.php?url=login') {
        if (!self::isUserLoggedIn()) {
            header("Location: $redirectUrl");
            exit;
        }
    }
    
    public static function requireUserAdmin($redirectUrl = '../public/index.php?url=home') {
        self::requireUserLogin();
        $userData = self::getCurrentUserData();
        if (!$userData['is_admin']) {
            header("Location: $redirectUrl");
            exit;
        }
    }

    // ================================
    // MÉTODOS DE INSTÂNCIA (ORIGINAIS)
    // ================================

    private function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public function getUserName() {
        return $_SESSION['user_name'] ?? null;
    }
    
    public function getUserEmail() {
        return $_SESSION['user_email'] ?? null;
    }
    
    public function isAdmin() {
        return $_SESSION['is_admin'] ?? false;
    }
    
    public function getUserBirthDate() {
        return $_SESSION['user_birth_date'] ?? null;
    }
    
    public function getUserData() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $this->getUserId(),
            'name' => $this->getUserName(),
            'email' => $this->getUserEmail(),
            'is_admin' => $this->isAdmin()
        ];
    }
    
    private function createSession($userData) {
        $_SESSION['user_id'] = $userData['id_user'];
        // Verificar diferentes possibilidades de campo nome
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
    
    public function requireLogin($redirectUrl = '../public/index.php?url=login') {
        if (!$this->isLoggedIn()) {
            header("Location: $redirectUrl");
            exit;
        }
    }
    
    public function requireAdmin($redirectUrl = '../public/index.php?url=home') {
        $this->requireLogin();
        if (!$this->isAdmin()) {
            header("Location: $redirectUrl");
            exit;
        }
    }

    private function validateUserData($data) {
        $errors = [];

        if (empty($data['nome'])) {
            $errors[] = 'Nome é obrigatório';
        } elseif (strlen($data['nome']) < 2 || strlen($data['nome']) > 60) {
            $errors[] = 'Nome deve ter entre 2 e 60 caracteres';
        }

        if (empty($data['email'])) {
            $errors[] = 'Email é obrigatório';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        } elseif (strlen($data['email']) > 100) {
            $errors[] = 'Email muito longo';
        }

        if (!empty($data['data_nascimento'])) {
            try {
                $birthDateTime = new DateTime($data['data_nascimento']);
                $today = new DateTime();
                $age = $today->diff($birthDateTime)->y;
                
                if ($age < 16) {
                    $errors[] = 'Você deve ter pelo menos 16 anos';
                }
            } catch (Exception $e) {
                $errors[] = 'Data de nascimento inválida';
            }
        }

        return $errors;
    }
    
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
            $gender = $_POST['gender'] ?? null;
            $terms = isset($_POST['terms']);
            
            // Dados de endereço (opcionais)
            $cep = trim($_POST['cep'] ?? '');
            $endereco = trim($_POST['endereco'] ?? '');
            $complemento = trim($_POST['complemento'] ?? '');
            $bairro = trim($_POST['bairro'] ?? '');
            $cidade = trim($_POST['cidade'] ?? '');
            $estado = trim($_POST['estado'] ?? '');

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

            // Validar campos de endereço se algum foi preenchido
            $hasAddressData = !empty($cep) || !empty($endereco) || !empty($bairro) || !empty($cidade) || !empty($estado);
            if ($hasAddressData) {
                if (empty($cep)) {
                    $errors[] = 'CEP é obrigatório quando endereço é informado';
                }
                if (empty($endereco)) {
                    $errors[] = 'Endereço é obrigatório quando dados de endereço são informados';
                }
                if (empty($bairro)) {
                    $errors[] = 'Bairro é obrigatório quando endereço é informado';
                }
                if (empty($cidade)) {
                    $errors[] = 'Cidade é obrigatória quando endereço é informado';
                }
                if (empty($estado)) {
                    $errors[] = 'Estado é obrigatório quando endereço é informado';
                }
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
                'genero' => in_array($gender, ['masculino', 'feminino', 'outro']) ? $gender : null,
                'data_nascimento' => $birthDate
            ];

            $validationErrors = $this->validateUserData($userData);
            if (!empty($validationErrors)) {
                echo json_encode(['success' => false, 'message' => implode(', ', $validationErrors)]);
                return;
            }

            if ($this->emailExists($email)) {
                echo json_encode(['success' => false, 'message' => 'Este email já está cadastrado']);
                return;
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO user (nome, email, senha, is_admin, genero, data_nascimento) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $result = $stmt->execute([
                $userData['nome'],
                $userData['email'],
                $userData['senha'],
                $userData['is_admin'] ? 1 : 0,
                $userData['genero'],
                $userData['data_nascimento']
            ]);

            if ($result) {
                $userId = $this->pdo->lastInsertId();
                $userData['id_user'] = $userId;
                
                // Salvar endereço se foi informado
                if ($hasAddressData) {
                    $this->saveUserAddress($userId, $cep, $endereco, $complemento, $bairro, $cidade, $estado);
                }
                
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

    // ================================
    // MÉTODOS UTILITÁRIOS
    // ================================

    public function getAllUsers($limit = null, $offset = 0) {
        try {
            // Tentar diferentes campos de nome para ordenação
            $nameField = 'nome'; // padrão
            
            // Verificar se existe outro campo de nome na tabela
            $checkStmt = $this->pdo->query("SHOW COLUMNS FROM user LIKE '%name%' OR SHOW COLUMNS FROM user LIKE '%nome%' OR SHOW COLUMNS FROM user LIKE '%usuario%'");
            $columns = $checkStmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($columns as $column) {
                if (in_array($column['Field'], ['name', 'usuario'])) {
                    $nameField = $column['Field'];
                    break;
                }
            }
            
            $sql = "SELECT * FROM user ORDER BY " . $nameField;
            
            if ($limit) {
                $sql .= " LIMIT $limit OFFSET $offset";
            }
            
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erro ao listar usuários: " . $e->getMessage());
            // Fallback: tentar sem ordenação específica
            try {
                $sql = "SELECT * FROM user";
                if ($limit) {
                    $sql .= " LIMIT $limit OFFSET $offset";
                }
                $stmt = $this->pdo->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e2) {
                return [];
            }
        }
    }
    
    public function countUsers() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM user");
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar usuários: " . $e->getMessage());
            return 0;
        }
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->findUserById($this->getUserId());
    }
    
    public function validateSession() {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $user = $this->getCurrentUser();
        if (!$user) {
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    // ================================
    // MÉTODO PARA SALVAR ENDEREÇO
    // ================================
    
    private function saveUserAddress($userId, $cep, $endereco, $complemento, $bairro, $cidade, $estado) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO endereco (id_user, endereco, cep, complemento, bairro, cidade, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $userId,
                $endereco,
                $cep,
                $complemento,
                $bairro,
                $cidade,
                $estado
            ]);
            
        } catch (PDOException $e) {
            error_log("Erro ao salvar endereço: " . $e->getMessage());
            return false;
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