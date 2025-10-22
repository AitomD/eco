<?php
// Iniciar sessão
session_start();

// Incluir sistema de autenticação
require_once __DIR__ . '/../app/core/user.php';

// Incluir controlador do carrinho
require_once __DIR__ . '/../app/controller/CarrinhoController.php';

// Processar ações do carrinho antes de qualquer output
CarrinhoController::processarAcao();

// Lista de páginas permitidas
$paginasPermitidas = [
    'home',
    'login',
    'cadastro',
    'ofertas',
    'produto',
    'cupons',
    'carrinho',
    '404',
    'itemCompra',
    'paginaSeguro',
    'paginaRetirada',
    'paginaCompra',
    'meuperfil'
];

// Página padrão
$pagina = $_GET['url'] ?? 'home';

// Sanitiza a URL para evitar path traversal (ex: ../)
$pagina = basename($pagina);

// Caminho base das views (corrigido para sair da pasta public)
$viewFile = __DIR__ . "/../app/views/{$pagina}.php";

// Configura classes do body e CSS condicional para páginas de autenticação
$isAuthPage = in_array($pagina, ['login', 'cadastro']);
$bodyClass = '';
if ($isAuthPage) {
    $bodyClass = 'auth-page ' . ($pagina === 'login' ? 'login-page' : 'register-page');
}

// Verifica se o arquivo existe e é permitido
if (!in_array($pagina, $paginasPermitidas) || !file_exists($viewFile)) {
    $viewFile = __DIR__ . "/../app/views/404.php";
}

// Verificar se o usuário está logado
$isLoggedIn = Auth::isUserLoggedIn();
$userData = null;
if ($isLoggedIn) {
    $userData = Auth::getCurrentUserData();
}

// Contar itens no carrinho
$cartCount = CarrinhoController::contarItens();
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAFTECH</title>
    <link rel="shortcut icon" href="img/logoMain.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <?php if ($isAuthPage): ?>
        <link rel="stylesheet" href="css/logincadastro.css">
    <?php endif; ?>
    <link rel="stylesheet" href="css/foot.css">
    <link rel="stylesheet" href="css/ofertas.css">
    <link rel="stylesheet" href="css/produto.css">
    <link rel="stylesheet" href="css/cupomcss.css">
    <link rel="stylesheet" href="css/itemCompra.css">

    <link rel="stylesheet" href="css/carrinho.css">
</head>

<body<?= $bodyClass ? ' class="' . htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') . '"' : '' ?> data-is-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>">

    <?php if (!$isAuthPage): ?>
        <nav class="navbar navbar-expand-lg position-relative z-3 " style="background-color: #09090A;">
            <div class="container-fluid">

                <!-- LOGO À ESQUERDA -->
                <a class="navbar-brand " href="index.php?url=home">
                    <img src="img/logoMain.png" alt="logo">
                </a>

                <!-- BOTÃO TOGGLER -->
                <button id="hamburger-button" class="navbar-toggler" type="button" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- DIV CENTRALIZADA -->
                <div class="central-nav-content text-center">

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mb-2 mb-lg-0 ">
                            <li class="nav-item">
                                <a class="nav-link p-btn mx-1" aria-current="page" href="index.php?url=home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-btn mx-1" href="index.php?url=ofertas">Ofertas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-btn mx-1" href="index.php?url=cupons">Cupons</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle p-btn mx-1" href="#">Categorias</a>

                                <ul class="dropdown-menu" style="background-color: #09090A;">
                                    <li><a class="dropdown-item p-btn" href="index.php?url=produto">Computadores</a></li>
                                    <li><a class="dropdown-item p-btn" href="index.php?url=produto">Notebooks</a></li>
                                    <li><a class="dropdown-item p-btn" href="index.php?url=produto">Smartphones</a></li>
                                    <li><a class="dropdown-item p-btn" href="index.php?url=produto">SmartTV</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- User Section - Posicionada à direita -->
                <div class="user-section ms-auto">
                    <?php if ($isLoggedIn): ?>
                    <!-- Usuário logado -->
                    <div class="d-flex align-items-center">
                        <!-- Carrinho de compras -->
                        <a href="index.php?url=carrinho" class="text-light fs-4 me-3 position-relative" title="Carrinho de compras">
                            <i class="bi bi-cart2"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                    <?= $cartCount > 99 ? '99+' : $cartCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        
                        <!-- Dropdown do usuário -->
                        <div class="dropdown">
                            <a class="dropdown-toggle text-light text-decoration-none d-flex align-items-center" 
                               href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5 me-2"></i>
                                <span class="d-none d-md-inline">Olá, <?= htmlspecialchars(explode(' ', $userData['name'])[0] ?? 'Usuário', ENT_QUOTES, 'UTF-8') ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="background-color: #09090A; border: 1px solid #3F0071;">
                                <li>
                                    <a class="dropdown-item text-light" href="index.php?url=meuperfil" style="border-bottom: 1px solid #3F0071;">
                                        <i class="bi bi-person me-2"></i>Meu Perfil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-light" href="#" style="border-bottom: 1px solid #3F0071;">
                                        <i class="bi bi-bag me-2"></i>Meus Pedidos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-light" href="#" style="border-bottom: 1px solid #3F0071;">
                                        <i class="bi bi-heart me-2"></i>Lista de Desejos
                                    </a>
                                </li>
                                <?php if ($userData['is_admin']): ?>
                                <li>
                                    <a class="dropdown-item text-warning" href="#" style="border-bottom: 1px solid #3F0071;">
                                        <i class="bi bi-gear me-2"></i>Administração
                                    </a>
                                </li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" id="logoutBtn">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sair
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Usuário não logado -->
                    <div class="d-flex align-items-center">
                        <!-- Carrinho de compras -->
                        <a href="index.php?url=carrinho" class="text-light fs-4 me-4 position-relative" title="Carrinho de compras">
                            <i class="bi bi-cart2"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                    <?= $cartCount > 99 ? '99+' : $cartCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        
                        <!-- Botões de autenticação -->
                        <div class="d-flex">
                            <a href="index.php?url=login" class="p-btn mx-2">Entrar</a>
                            <a href="index.php?url=cadastro" class="p-btn mx-2">Cadastrar</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    <?php endif; ?>
    <main>

        <?php require $viewFile; ?>


    </main>
    <?php if (!$isAuthPage): ?>
        <footer class="z-3 ">
            <div class="footer-container d-flex mx-4">
                <div class="footer-column mx-5">
                    <h4 class="text-light">Sobre Nós</h4>
                    <ul class="dev-list">
                        <li>
                            <span class="text-light text-dimmed">Fernando Consolin Rosa</span>
                            <a href="#" target="_blank"><i class="bi bi-instagram"></i></a>
                            <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                        </li>
                        <li>
                            <span class="text-light text-dimmed">Aitom Henrique Donatoni</span>
                            <a href="#" target="_blank"><i class="bi bi-instagram"></i></a>
                            <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                        </li>
                        <li>
                            <span class="text-light text-dimmed">Hiago Nascimento</span>
                            <a href="#" target="_blank"><i class="bi bi-instagram"></i></a>
                            <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="footer-column mx-3">
                    <h4 class="text-light">Novidades e Promoções</h4>
                    <div class="d-flex">
                        <ul class="dev-list">
                            <li>Dia Das Crianças</li>
                            <li>Black Friday</li>
                        </ul>
                        <ul class="dev-list mx-3">
                            <li>Oferta Tech</li>
                            <li>Gift Card HAFTECH!</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-column mx-5">
                    <h4 class="text-light">Atendimento</h4>
                    <ul class="dev-list">
                        <li><a href="#">Entre em Contato</a></li>
                    </ul>
                </div>
                <div class="footer-column mx-5">
                    <h4 class="text-light">Outros</h4>
                    <ul class="dev-list">
                        <li><a href="#">Termos e Condições</a></li>
                        <li><a href="#">Política de Privacidade</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init(); // Inicializa as animações
    </script>
    <script src="../public/js/dropinteracao.js"></script>
    <script src="../public/js/activebtn.js"></script>
    <script src="../public/js/cupomalerta.js"></script>
    <script src="../public/js/burger.js"></script>
    </body>
    <script src="js/dropinteracao.js"></script>
    <script src="js/auth.js"></script>
    
    <!-- Script para efeito do ícone do usuário -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userDropdown = document.getElementById('userDropdown');
            const userIcon = document.querySelector('#userDropdown .bi-person-circle');
            
            if (userDropdown && userIcon) {
                // Detectar quando o dropdown é mostrado
                userDropdown.addEventListener('show.bs.dropdown', function() {
                    userIcon.style.color = '#610094';
                    userIcon.style.transform = 'scale(1.1)';
                });
                
                // Detectar quando o dropdown é escondido
                userDropdown.addEventListener('hide.bs.dropdown', function() {
                    userIcon.style.color = '';
                    userIcon.style.transform = '';
                });
                
                // Efeito adicional no clique
                userDropdown.addEventListener('click', function() {
                    userIcon.style.color = '#3F0071';
                    
                    // Voltar à cor normal após um tempo se o dropdown não abrir
                    setTimeout(() => {
                        if (!userDropdown.classList.contains('show')) {
                            userIcon.style.color = '';
                        }
                    }, 300);
                });
            }
        });
    </script>
    
    <?php if ($isLoggedIn): ?>
    
    <style>
        .spin {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .user-section .dropdown-menu {
            min-width: 200px;
        }
        
        .user-section .dropdown-item:hover {
            background-color: #3F0071 !important;
            color: #fff !important;
        }
        
        /* Efeito de mudança de cor no ícone do usuário */
        #userDropdown {
            transition: all 0.3s ease;
        }
        
        #userDropdown:hover .bi-person-circle {
            color: #610094 !important;
            transform: scale(1.1);
        }
        
        #userDropdown:active .bi-person-circle,
        #userDropdown.show .bi-person-circle {
            color: #3F0071 !important;
            transform: scale(1.05);
        }
        
        /* Efeito quando o dropdown está aberto */
        .dropdown.show #userDropdown .bi-person-circle {
            color: #610094 !important;
        }
        
        /* Animação suave para o ícone */
        .bi-person-circle {
            transition: all 0.3s ease;
        }
    </style>
    <?php endif; ?>
    <script src="js/cupomalerta.js"></script>
    <script src="js/carrinho.js"></script>
    <?php if ($pagina === 'paginaRetirada'): ?>
    <script src="js/modalEndereco.js"></script>
    <?php endif; ?>
</body>

</html>