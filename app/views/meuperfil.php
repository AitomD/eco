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


// 1. Incluir a classe Pedido
// (Ajuste o caminho conforme a estrutura do seu projeto)

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

                <a class="list-group-item list-group-item-action" id="link-lista-desejo" data-bs-toggle="list" href="#pane-lista-desejo" role="tab" aria-controls="pane-lista-desejo" aria-selected="false">
                    <i class="bi bi-heart fs-5 me-3"></i>
                    Lista de Desejo
                </a>

                <?php if ($userData['is_admin'] == 1): ?>
                    <a class="list-group-item list-group-item-action" id="link-minhas-vendas" data-bs-toggle="list" href="#pane-minhas-vendas" role="tab" aria-controls="pane-minhas-vendas" aria-selected="false">
                        <i class="bi bi-shop fs-5 me-3"></i>
                        Minhas Vendas
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

                <div class="tab-pane fade show active" id="pane-meus-dados" role="tabpanel" aria-labelledby="link-meus-dados" tabindex="0">

                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <!--MEUS DADOS-->
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-person-circle fs-1 me-3 " style="color:var(--pmain);"></i>
                            <div class="lh-1">
                                <h2 class="h4 mb-0 fw-bold">Olá, <?php echo htmlspecialchars($userData['nome']); ?></h2>
                                <span class="text-muted" style="color:var(--black);">Aqui você pode gerenciar suas informações.</span>
                            </div>
                        </div>

                        <hr class="mb-4" style="color:var(--black);">
                        <h3 class="h5 mb-3">Informações da conta</h3>

                        <dl class="row">
                            <dt class="col-sm-3">NOME:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($userData['nome']); ?></dd>

                            <dt class="col-sm-3">EMAIL:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($userData['email']); ?></dd>

                            <dt class="col-sm-3">NASCIMENTO:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($userData['data_nascimento']); ?></dd>

                            <hr class="my-3">
                            <h3 class="h5 mb-3" style="color:var(--pmain);">Meu Endereço</h3>

                            <dt class="col-sm-3 ">ENDEREÇO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['endereco']); ?></dd>

                            <dt class="col-sm-3 ">CEP:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['cep']); ?></dd>

                            <dt class="col-sm-3 ">COMPLEMENTO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['complemento']); ?></dd>

                            <dt class="col-sm-3 ">BAIRRO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['bairro']); ?></dd>

                            <dt class="col-sm-3 ">CIDADE:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['cidade']); ?></dd>

                            <dt class="col-sm-3 ">ESTADO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['estado']); ?></dd>
                        </dl>

                        <a href="#" class="btn-product text-decoration-none mt-3" data-bs-toggle="modal" data-bs-target="#modalEditarDados">
                            <i class="bi bi-pencil-fill me-2"></i>
                            Editar dados
                        </a>

                    </div>
                </div>

                <!--MEUS PEDIDOS-->
                <div class="tab-pane fade" id="pane-meus-pedidos" role="tabpanel" aria-labelledby="link-meus-pedidos" tabindex="0">
                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <h3 class="h5 mb-3">Meus Pedidos</h3>
                        <p class="text-muted">Aqui você poderá visualizar seu histórico de pedidos.</p>
                        <div class="mt-4">

                            <?php if (!$id_usuario_logado): ?>
                                <div class="alert alert-warning" role="alert">
                                    Você precisa estar logado para ver seus pedidos.
                                </div>

                            <?php elseif (empty($meus_pedidos)): ?>
                                <div class="alert alert-info" role="alert">
                                    Você ainda não fez nenhum pedido.
                                </div>

                            <?php else: ?>
                                <div class="list-group">

                                    <?php foreach ($meus_pedidos as $pedido): ?>

                                        <?php
                                        // --- Lógica para definir a cor do status ---
                                        // (Isso agora executa UMA VEZ por pedido, como deve ser)
                                        $status_class = '';
                                        $status_texto = htmlspecialchars(ucfirst($pedido['status']));
                                        $status_comparar = strtolower($pedido['status']);

                                        switch ($status_comparar) {
                                          
                                            case 'enviado':
                                            case 'concluido':
                                            case 'entregue':
                                                $status_class = 'text-success';
                                                break;
                                            case 'confirmado':
                                            case 'processando':
                                                $status_class = 'text-warning';
                                                break;
                                            case 'pendente':
                                            case 'cancelado':
                                                $status_class = 'text-danger';
                                                break;
                                            default:
                                                $status_class = 'text-muted';
                                        }
                                        ?>

                                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border rounded">

                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h5>
                                                <small class="text-muted">
                                                    <?php
                                                    // Formata a data para o padrão brasileiro
                                                    $data = new DateTime($pedido['data_pedido']);
                                                    echo $data->format('d/m/Y H:i');
                                                    ?>
                                                </small>
                                            </div>

                                            <p class="mb-1">
                                                <strong>Status:</strong> <span class="fw-bold <?php echo $status_class; ?>">
                                                    <?php echo $status_texto; ?>
                                                </span>
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong class="h5"> Total: R$ <?php echo number_format($pedido['total_final'], 2, ',', '.'); ?>
                                                </strong>
                                                <a href="detalhes_pedido.php?id=<?php echo $pedido['id_pedido']; ?>" class="btn-product w-25 text-center text-decoration-none btn-sm">
                                                    Ver Detalhes
                                                </a>
                                            </div>

                                        </div> <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!--LISTA DE DESEJO-->
                <div class="tab-pane fade" id="pane-lista-desejo" role="tabpanel" aria-labelledby="link-lista-desejo" tabindex="0">
                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <h3 class="h5 mb-3">Lista de Desejo</h3>
                        <p class="text-muted">Aqui você poderá visualizar seus produtos favoritos.</p>
                    </div>
                </div>

                <!--MINHAS VENDAS (if this user is admin = 1) -->
                <?php if ($userData['is_admin'] == 1): ?>
                    <div class="tab-pane fade" id="pane-minhas-vendas" role="tabpanel" aria-labelledby="link-minhas-vendas" tabindex="0">
                        <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                            <h3 class="h5 mb-3">Minhas Vendas</h3>
                            <p class="text-muted">Área do administrador para gerenciamento de vendas.</p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!--MODAL EDITAR INFORMAÇÕES-->
    </div>
    <div class="modal p-4 fade" id="modalEditarDados" tabindex="-1" aria-labelledby="modalEditarDadosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarDadosLabel">Editar Informações Pessoais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formAtualizarDados">
                    <div class="modal-body">
                        <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>">

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($userData['nome']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($userData['data_nascimento']); ?>">
                        </div>

                        <hr>
                        <p class="text-muted">Deixe os campos de senha vazios se não quiser alterar.</p>
                        <div class="mb-3">
                            <label for="senha_nova" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="senha_nova" name="senha_nova">
                        </div>
                        <div class="mb-3">
                            <label for="senha_confirmar" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="senha_confirmar" name="senha_confirmar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-product">Salvar Alterações</button>
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>

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