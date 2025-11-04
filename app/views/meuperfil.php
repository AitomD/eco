<?php
// 1. **VERIFICAR SESSÃO** // session_start(); // (Descomente se não estiver no seu index.php)

// 2. **INCLUSÃO DA CLASSE**
require_once "../app/model/dadosuser.php";
require_once "../app/model/pedidos.php";
// 3. **OBTER O ID DO USUÁRIO**
if (isset($_SESSION['user_id'])) {
    $id_user = (int) $_SESSION['user_id'];
} else {
    die("Acesso negado. Por favor, faça login.");
}

// 4. **INSTANCIAR A CLASSE**
$dadosUser = new Dadosuser();

// 5. **CHAMAR A FUNÇÃO DE BUSCA**
$userDataList = $dadosUser->buscarUsuarioEEnderecos($id_user);

// 6. **VALIDAR OS RESULTADOS E PREPARAR AS VARIÁVEIS**
if (!$userDataList || empty($userDataList)) {
    die("Erro ao carregar o perfil ou usuário não encontrado.");
}
$userData = $userDataList[0];

// 7. Função para exibir mensagens na UI
function showMessage($type, $text)
{
    echo "<div id='statusMessage' class='alert alert-{$type} alert-dismissible fade show' role='alert'>";
    echo $text;
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
}
// 2. Obter o ID do usuário logado (ASSUMINDO que está na sessão)
// Verifique se a sessão já foi iniciada no topo da sua página
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$id_usuario_logado = $_SESSION['user_id'] ?? null; // Pega o ID da sessão
$meus_pedidos = [];

// 3. Buscar os pedidos se o usuário estiver logado
if ($id_usuario_logado) {
    try {
        $pedidoObj = new Pedidos();
        $meus_pedidos = $pedidoObj->buscarPorUsuario($id_usuario_logado);
    } catch (Exception $e) {
        // Tratar erro de conexão ou consulta, se necessário
        error_log($e->getMessage());
    }
}

?>
<style>
    /* Estilos existentes para o menu lateral (list-group) */
    .list-group-item-action {
        color: var(--black);
        font-weight: 500;
        border: none;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .list-group-item-action:hover,
    .list-group-item-action:focus {
        background-color: #f5f5f5;
        color: var(--pmain);
    }

    /* Seu estilo .active-ml é usado para o item de menu ativo.
      Adicionei a classe .active do Bootstrap para garantir a funcionalidade.
    */
    .list-group-item-action.active-ml,
    .list-group-item-action.active {
        background-color: #fff;
        color: var(--pmain);
        font-weight: 700;
        border-left: 3px solid var(--pmain);
        box-shadow: 3px 0 5px -5px rgba(0, 0, 0, 0.1);
        border-color: #fff;
        /* Remove bordas laterais do bootstrap */
    }

    #messageContainer {
        min-height: 50px;
    }

    dl {
        color: var(--black);
    }
</style>

<main class="container py-4">

    <div id="messageContainer" class="mb-4">
        <?php
        if (isset($_SESSION['mensagem_sucesso'])) {
            showMessage('success', $_SESSION['mensagem_sucesso']);
            unset($_SESSION['mensagem_sucesso']);
        }
        if (isset($_SESSION['mensagem_erro'])) {
            showMessage('danger', "<strong>Erro:</strong> " . $_SESSION['mensagem_erro']);
            unset($_SESSION['mensagem_erro']);
        }
        ?>
    </div>

    <div class="row g-4">

        <div class="col-lg-3">
            <div class="list-group shadow-sm rounded">
                <a class="list-group-item list-group-item-action active-ml active" id="link-meus-dados" data-bs-toggle="list" href="#pane-meus-dados" role="tab" aria-controls="pane-meus-dados" aria-selected="true">
                    <i class="bi bi-person-fill fs-5 me-3"></i>
                    Meus Dados
                </a>

                <a class="list-group-item list-group-item-action" id="link-meus-pedidos" data-bs-toggle="list" href="#pane-meus-pedidos" role="tab" aria-controls="pane-meus-pedidos" aria-selected="false">
                    <i class="bi bi-box-seam fs-5 me-3"></i>
                    Meus Pedidos
                </a>


                <!-- Paginas para o vendedor acessar -->
                <?php if ($userData['is_admin'] == 1): ?>
                    <a class="list-group-item list-group-item-action" id="link-minhas-vendas" data-bs-toggle="list" href="#pane-minhas-vendas" role="tab" aria-controls="pane-minhas-vendas" aria-selected="false">
                        <i class="bi bi-shop fs-5 me-3"></i>
                        Minhas Vendas
                    </a>

                    <a class="list-group-item list-group-item-action" id="link-adiciona" data-bs-toggle="list" href="#pane-adiciona" role="tab" aria-controls="pane-adiciona" aria-selected="false">
                        <i class="bi bi-cart-plus me-3"></i>
                        Adicionar Produto
                    </a>
                    <a class="list-group-item list-group-item-action" id="link-meus-produtos" data-bs-toggle="list" href="#pane-meus-produtos" role="tab" aria-controls="pane-adiciona" aria-selected="false">
                        <i class="bi bi-card-list me-3"></i>
                        Meus Produtos
                    </a>
                    <?php endif; ?>
                <a href="?url=logout" class="list-group-item list-group-item-action text-danger mt-3">
                    <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                    Sair
                </a>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content" id="myTabContent">
                <!-- MEUS DADOS --->
                <?php
                include 'meusDados.php';
                ?>

                <!--MEUS PEDIDOS-->
                <?php
                include 'meusPedidos.php';
                ?>


                <!--Paginas que o vendedor acessa -->
                <?php if ($userData['is_admin'] == 1): ?>
                    <div class="tab-pane fade" id="pane-minhas-vendas" role="tabpanel" aria-labelledby="link-minhas-vendas" tabindex="0">
                        <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                            <?php require_once 'venda.php' ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($userData['is_admin'] == 1): ?>
                    <div class="tab-pane fade" id="pane-adiciona" role="tabpanel" aria-labelledby="link-adiciona" tabindex="0">
                        <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                            <?php require_once 'adicionaproduto.php' ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($userData['is_admin'] == 1): ?>
                    <div class="tab-pane fade" id="pane-meus-produtos" role="tabpanel" aria-labelledby="link-meus-produtos" tabindex="0">
                        <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                            <?php require_once 'meusprodutos.php' ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>

</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#formAtualizarDados').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: '../app/model/atualizar_perfil.php', // O arquivo PHP que processará os dados
                data: formData,
                dataType: 'json',

                success: function(response) {
                    if (response.success) {
                        alert('Dados atualizados com sucesso!');
                        $('#modalEditarDados').modal('hide');
                        location.reload();
                    } else {
                        alert('Erro: ' + response.message);
                    }
                },
                error: function() {
                    alert('Ocorreu um erro no servidor. Tente novamente.');
                }
            });
        });
    });
</script>