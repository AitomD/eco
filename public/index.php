<?php
/*
 * ======================================================================
 * FASE 1: INICIALIZAÃ‡ÃƒO E AÃ‡Ã•ES GERAIS (sempre rodam)
 * ======================================================================
 */

// Iniciar sessÃ£o
session_start();

// Incluir sistema de autenticaÃ§Ã£o
require_once __DIR__ . '/../app/core/user.php';

// Incluir controladores principais
require_once __DIR__ . '/../app/controller/CarrinhoController.php';
require_once __DIR__ . '/../app/controller/cupons-carrinho.php';

// Processar aÃ§Ãµes de formulÃ¡rio (ex: adicionar/remover do carrinho)
CarrinhoController::processarAcao();
CuponsCarrinhoController::processarAcao();

// Processar requisiÃ§Ãµes AJAX de cupons (isso deve ter um 'exit' dentro dele)
CuponsCarrinhoController::aplicarCupomAjax();

// Tratamento simples para AJAX de adicionar cupom vindo do modal
// Se a requisição enviar 'ajax_add_cupom', encaminhar para o handler e encerrar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_add_cupom'])) {
    // Carrega o arquivo que responde em JSON
    require_once __DIR__ . '/../app/controller/salvar_cupom.php';
    // O arquivo 'salvar_cupom.php' envia JSON e deve dar exit ao terminar
    exit;
}


/*
 * ======================================================================
 * FASE 2: DEFINIÃ‡ÃƒO DE ROTA E VARIÃVEIS GERAIS
 * ======================================================================
 */

// Lista de pÃ¡ginas permitidas (whitelist)
$paginasPermitidas = [
    'home',
    'login',
    'cadastro',
    'produto',
    'cupons',
    'carrinho',
    '404',
    'itemCompra',
    'paginaRetirada',
    'metodopagamento',
    'pedido-sucesso',
    'paginaCompra',
    'meuperfil',
    'venda',
    'adicionaproduto',
    'meusprodutos',
    'termosECondicoes'
];

// Obter a pÃ¡gina da URL, com 'home' como padrÃ£o
$pagina = $_GET['url'] ?? 'home';

// Sanitizar a URL para evitar path traversal (ex: ../)
$pagina = basename($pagina);

// Verificar se o usuÃ¡rio estÃ¡ logado (usado por vÃ¡rias lÃ³gicas e pela view)
$isLoggedIn = Auth::isUserLoggedIn();
$userData = null;
if ($isLoggedIn) {
    $userData = Auth::getCurrentUserData();
}

/*
 * ======================================================================
 * FASE 3: LÃ“GICA DE CONTROLADOR (ANTES DO HTML)
 * Aqui ficam todas as verificações que podem causar um redirecionamento.
 * ======================================================================
 */

// --- Lógica para 'metodopagamento' (POST) ---
if ($pagina === 'metodopagamento' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_compra'])) {
    require_once __DIR__ . '/../app/controller/PedidoController.php';

    $resultado = PedidoController::processarFinalizacaoCompra();
    // Registro de depuraÃ§Ã£o: salvar resultado no log do PHP e na sessÃ£o para anÃ¡lise
    error_log('[DEBUG] Resultado finalizar compra: ' . print_r($resultado, true));
    $_SESSION['pedido_finalizacao_result'] = $resultado;

    if ($resultado['sucesso']) {
        // Sucesso: tentar redirecionar para pÃ¡gina de sucesso com o ID
        $redirectUrl = 'index.php?url=pedido-sucesso&id=' . $resultado['id_pedido'];
        // Garantir que a sessÃ£o foi gravada
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        // Sempre tentar redirecionar â€” usar header e tambÃ©m enviar um fallback via JavaScript/meta-refresh no corpo da resposta.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        // Tentar header (padrÃ£o)
        if (!headers_sent($file, $line)) {
            header('Location: ' . $redirectUrl);
        } else {
            error_log("[DEBUG] headers already sent in $file on line $line. Usando fallback de redirecionamento para $redirectUrl");
        }

        // Sempre enviar um fallback no corpo para garantir redirecionamento em navegadores/ambientes onde header nÃ£o funciona
        echo "<html><head><meta charset=\"utf-8\"><title>Redirecionando...</title></head><body>";
        echo "<script>window.location.href='" . htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8') . "';</script>";
        echo "<noscript><meta http-equiv='refresh' content='0;url=" . htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8') . "' /></noscript>";
        echo "</body></html>";
        exit;
    } else {
        // Falha: Armazenar mensagem de erro na sessÃ£o e deixar a pÃ¡gina carregar
        $_SESSION['erro_compra'] = $resultado['mensagem'];
    }
}

// --- LÃ³gica para 'metodopagamento' (GET) ---
// Verificar se estÃ¡ tentando acessar a pÃ¡gina de pagamento com carrinho vazio
if ($pagina === 'metodopagamento' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $itensCarrinho = CarrinhoController::getItens();
    if (empty($itensCarrinho)) {
        header('Location: index.php?url=carrinho');
        exit;
    }
}

// --- LÃ³gica para 'pedido-sucesso' (GET) ---
// Esta Ã© a lÃ³gica que estava causando o erro, agora movida para cÃ¡.
$pedido = null;
$produtos = null;

if ($pagina === 'pedido-sucesso') {

    // 1. Verificar se o usuÃ¡rio estÃ¡ logado
    if (!$isLoggedIn) {
        header('Location: index.php?url=login');
        exit;
    }

    // 2. Obter ID do pedido da URL
    $idPedido = isset($_GET['id']) ? intval($_GET['id']) : null;
    if (!$idPedido) {
        // Se nÃ£o houver ID, nÃ£o hÃ¡ o que mostrar
        header('Location: index.php?url=carrinho');
        exit;
    }

    // 3. Buscar detalhes do pedido
    require_once __DIR__ . '/../app/controller/PedidoController.php';
    $detalhesPedido = PedidoController::buscarDetalhesPedido($idPedido, $_SESSION['user_id']);

    // 4. Extrair variÃ¡veis para a view
    if ($detalhesPedido && is_array($detalhesPedido)) {
        $pedido = $detalhesPedido['pedido'];
        $produtos = $detalhesPedido['produtos'];
    } else {
        // Se nÃ£o conseguir carregar os detalhes, redirecionar
        $_SESSION['mensagem_erro'] = 'Pedido nÃ£o encontrado.';
        header('Location: index.php?url=produto');
        exit;
    }

}


/*
 * ======================================================================
 * FASE 4: PREPARAÃ‡ÃƒO FINAL DA VIEW (DEPOIS DE TODA LÃ“GICA)
 * ======================================================================
 */

// Caminho base das views
$viewFile = __DIR__ . "/../app/views/{$pagina}.php";

// Verificar se o arquivo da view existe e Ã© permitido
if (!in_array($pagina, $paginasPermitidas) || !file_exists($viewFile)) {
    // Se a pÃ¡gina nÃ£o for permitida ou nÃ£o existir, forÃ§ar a pÃ¡gina de 404
    $pagina = '404';
    $viewFile = __DIR__ . "/../app/views/404.php";
}

// Configurar classes do body e CSS condicional para pÃ¡ginas de autenticaÃ§Ã£o
$isAuthPage = in_array($pagina, ['login', 'cadastro']);
$bodyClass = '';
if ($isAuthPage) {
    $bodyClass = 'auth-page ' . ($pagina === 'login' ? 'login-page' : 'register-page');
}

// Contar itens no carrinho (para o Ã­cone do header)
$cartCount = CarrinhoController::contarItens();


// FIM DO SCRIPT PHP. O ARQUIVO HTML COMEÃ‡A AGORA.
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HAFTECH</title>
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
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
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/carrinhoStep.css">
    <link rel="stylesheet" href="css/carrinhoCards.css">
    <link rel="stylesheet" href="css/pedido-sucesso.css">

</head>

<body<?= $bodyClass ? ' class="' . htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') . '"' : '' ?>
    data-is-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>">

    <?php if (!$isAuthPage): ?>
        <nav class="navbar navbar-expand-lg position-relative z-3 " style="background-color: #09090A;">
            <div class="container-fluid">

                <!-- LOGO Ã€ ESQUERDA -->
                <a class="navbar-brand " href="index.php?url=home">
                    <img src="img/logo.png" alt="logo">
                </a>

                <!-- BOTÃƒO TOGGLER -->
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
                                <a class="nav-link p-btn mx-1" href="index.php?url=cupons">Cupons</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link p-btn mx-1" href="index.php?url=produto">Produtos</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- User Section - Posicionada Ã  direita -->
                <div class="user-section ms-auto">
                    <?php if ($isLoggedIn): ?>
                        <!-- UsuÃ¡rio logado -->
                        <div class="d-flex align-items-center">
                            <!-- Carrinho de compras -->
                            <a href="index.php?url=carrinho" class="text-light fs-4 me-3 position-relative"
                                title="Carrinho de compras">
                                <i class="bi bi-cart2 mx-2"></i>
                                <?php if ($cartCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.7rem;">
                                        <?= $cartCount > 99 ? '99+' : $cartCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- Dropdown do usuÃ¡rio -->
                            <div class="dropdown">
                                <a class="dropdown-toggle text-light text-decoration-none d-flex align-items-center" href="#"
                                    role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle fs-5 me-2"></i>
                                    <span class="d-none d-md-inline text-light">Olá,
                                        <?= htmlspecialchars(explode(' ', $userData['name'])[0] ?? 'UsuÃ¡rio', ENT_QUOTES, 'UTF-8') ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end"
                                    style="background-color: #09090A; border: 1px solid #3F0071;">
                                    <li>
                                        <a class="dropdown-item text-light" href="index.php?url=meuperfil"
                                            style="border-bottom: 1px solid #3F0071;">
                                            <i class="bi bi-person me-2"></i>Meu Perfil
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger" href="#" id="logoutBtn">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sair
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Usuário nÃ£o logado -->
                        <div class="d-flex align-items-center">
                            <!-- Carrinho de compras -->
                            <a href="index.php?url=carrinho" class="text-light fs-4 me-4 position-relative"
                                title="Carrinho de compras">
                                <i class="bi bi-cart2"></i>
                                <?php if ($cartCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.7rem;">
                                        <?= $cartCount > 99 ? '99+' : $cartCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <!-- BotÃµes de autenticaÃ§Ã£o -->
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
        <footer class="mt-5 py-4">
            <div class="container-fluid">
                <div class="row g-4 mx-2">
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="footer-column">
                            <h4 class="text-light mb-3">Sobre Nós
                            </h4>
                            <ul class="dev-list">
                                <li>
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="text-light flex-grow-1">Fernando Consolin Rosa</span>
                                        <div class="social-links">
                                            <a href="#" target="_blank" class="text-muted me-2"><i
                                                    class="bi bi-instagram"></i></a>
                                            <a href="https://github.com/FernandoConsolinRosa11" target="_blank"
                                                class="text-muted"><i class="bi bi-github"></i></a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="text-light flex-grow-1">Aitom Henrique Donatoni</span>
                                        <div class="social-links">
                                            <a href="https://www.instagram.com/aitomdonatoni?igsh=aXlsYTAyd2phajIy"
                                                target="_blank" class="text-muted me-2"><i class="bi bi-instagram"></i></a>
                                            <a href="https://github.com/AitomD" target="_blank" class="text-muted"><i
                                                    class="bi bi-github"></i></a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span class="text-light flex-grow-1">Hiago Nascimento</span>
                                        <div class="social-links">
                                            <a href="https://www.instagram.com/haiagos_48?igsh=MXRidG14aHJxYnU3cQ=="
                                                target="_blank" class="text-muted me-2"><i class="bi bi-instagram"></i></a>
                                            <a href="https://github.com/haiagos48" target="_blank" class="text-muted"><i
                                                    class="bi bi-github"></i></a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 mx-4">
                        <div class="footer-column">
                            <h4 class="text-light mb-3">Outros</h4>
                            <ul class="dev-list">
                                <li><a class="text-muted" id="btnAbrirModal">Termos e Condições</a></li>
                                <li><a  class="text-muted"  id="btnAbrirPrivacidade">Polí­tica de Privacidade</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="border-secondary my-4 mx-4">
                <div class="row mx-2">
                    <div class="col-12 text-center">
                        <p class="text-muted mb-0">&copy; 2025 HAFTECH. Todos os direitos reservados.</p>
                    </div>
                </div>
            </div>
        </footer>
        <?php
            include '../app/views/termosECondicoes.php';
            include '../app/views/politicadeprivacidade.php';
        ?>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        AOS.init(); // Inicializa as animaÃ§Ãµes
    </script>
    <script src="../public/js/dropinteracao.js"></script>
    <script src="../public/js/activebtn.js"></script>
    <script src="../public/js/cupomalerta.js"></script>
    <script src="../public/js/burger.js"></script>
    <script src="js/dropinteracao.js"></script>
    <script src="js/auth.js"></script>
    <script src="js/carrinho.js"></script>
    <script src="js/termosecondicoes.js"></script>
    <script src="js/politicadeprivacidade.js"></script>

    <?php if ($pagina === 'paginaRetirada'): ?>
        <script src="js/modalEndereco.js"></script>
    <?php endif; ?>

    <?php if ($pagina === 'itemCompra'): ?>
        <script src="js/trocarImg.js"></script>
    <?php endif; ?>

    <?php if ($pagina === 'cupons'): ?>
        <script src="js/addCupom.js"></script>
        <script src="js/aplicarCupom.js"></script>
        <script src="js/cupomalerta.js"></script>
    <?php endif; ?>

    <!-- Script para efeito do Ã­cone do usuÃ¡rio -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userDropdown = document.getElementById('userDropdown');
            const userIcon = document.querySelector('#userDropdown .bi-person-circle');

            if (userDropdown && userIcon) {
                // Detectar quando o dropdown Ã© mostrado
                userDropdown.addEventListener('show.bs.dropdown', function () {
                    userIcon.style.color = '#610094';
                    userIcon.style.transform = 'scale(1.1)';
                });

                // Detectar quando o dropdown Ã© escondido
                userDropdown.addEventListener('hide.bs.dropdown', function () {
                    userIcon.style.color = '';
                    userIcon.style.transform = '';
                });

                // Efeito adicional no clique
                userDropdown.addEventListener('click', function () {
                    userIcon.style.color = '#3F0071';

                    // Voltar Ã  cor normal apÃ³s um tempo se o dropdown nÃ£o abrir
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
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            .user-section .dropdown-menu {
                min-width: 200px;
            }

            .user-section .dropdown-item:hover {
                background-color: #3F0071 !important;
                color: #fff !important;
            }

            /* Efeito de mudanÃ§a de cor no Ã­cone do usuÃ¡rio */
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

            /* Efeito quando o dropdown estÃ¡ aberto */
            .dropdown.show #userDropdown .bi-person-circle {
                color: #610094 !important;
            }

            /* AnimaÃ§Ã£o suave para o Ã­cone */
            .bi-person-circle {
                transition: all 0.3s ease;
            }
        </style>
    <?php endif; ?>

    </body>

</html>