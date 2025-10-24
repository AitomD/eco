<?php
// 1. **VERIFICAR SESSÃO** (Obrigatório para sistemas logados)
// session_start() deve estar no topo do seu script principal (ex: index.php)
// Exemplo:
// session_start(); 

// 2. **INCLUSÃO DA CLASSE**
require_once "../app/model/Dadosuser.php";

// 3. **OBTER O ID DO USUÁRIO**
// Em um sistema logado, o ID do usuário deve vir da sessão.
// Se você está usando uma variável diferente na sessão, ajuste aqui.
if (isset($_SESSION['user_id'])) {
    $id_user = (int) $_SESSION['user_id'];
} else {
    // Se o usuário não está logado, interrompa a execução e redirecione
    die("Acesso negado. Por favor, faça login.");
    // Em produção: header("Location: /login.php"); exit;
}

// 4. **INSTANCIAR A CLASSE**
$dadosUser = new Dadosuser();

// 5. **CHAMAR A FUNÇÃO DE BUSCA**
$userDataList = $dadosUser->buscarUsuarioEEnderecos($id_user);

// 6. **VALIDAR OS RESULTADOS E PREPARAR AS VARIÁVEIS**
if (!$userDataList || empty($userDataList)) {
    die("Erro ao carregar o perfil ou usuário não encontrado.");
}

// O primeiro elemento da lista contém os dados do usuário
$userData = $userDataList[0];

// 7. Função para exibir mensagens na UI (substitui o alert)
function showMessage($type, $text) {
    echo "<div id='statusMessage' class='alert alert-{$type} alert-dismissible fade show' role='alert'>";
    echo $text;
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
}
?>

<style>
    /* Estilos existentes */
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

    .list-group-item-action.active-ml {
        background-color: #fff;
        color: var(--pmain);
        font-weight: 700;
        border-left: 3px solid var(--pmain);
        box-shadow: 3px 0 5px -5px rgba(0, 0, 0, 0.1);
    }
    
    /* Novo estilo para a área de mensagem */
    #messageContainer {
        min-height: 50px; /* Garante espaço para a mensagem */
    }
</style>

<main class="container py-4">

    <!-- Container para exibir mensagens de status (sucesso/erro) via PHP ou AJAX -->
    <div id="messageContainer" class="mb-4">
        <?php
        // Exibe mensagem de sucesso
        if (isset($_SESSION['mensagem_sucesso'])) {
            showMessage('success', $_SESSION['mensagem_sucesso']);
            unset($_SESSION['mensagem_sucesso']);
        }

        // Exibe mensagem de erro
        if (isset($_SESSION['mensagem_erro'])) {
            showMessage('danger', "<strong>Erro:</strong> " . $_SESSION['mensagem_erro']);
            unset($_SESSION['mensagem_erro']);
        }
        ?>
    </div>


    <div class="row g-4">

        <div class="col-lg-3">
            <div class="list-group shadow-sm rounded">
                <a href="?url=meuperfil"
                    class="list-group-item list-group-item-action active-ml d-flex align-items-center">
                    <i class="bi bi-person-fill fs-5 me-3"></i>
                    Meus dados
                </a>

                <a href="?url=meusPedidos" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-box-seam fs-5 me-3"></i>
                    Meus Pedidos
                </a>

                <?php if ($userData['is_admin'] == 1): ?>
                    <a href="?url=minhasvendas" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-shop fs-5 me-3"></i>
                        Minhas vendas
                    </a>
                <?php endif; ?>

                <a href="?url=favoritos" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-heart fs-5 me-3"></i>
                    Lista de Desejo
                </a>

                <a href="?url=logout"
                    class="list-group-item list-group-item-action d-flex align-items-center text-danger">
                    <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                    Sair
                </a>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm">

                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-person-circle fs-1 me-3 " style="color:var(--pmain);"></i>
                    <div class="lh-1">
                        <h2 class="h4 mb-0 fw-bold">Olá, <?php echo htmlspecialchars($userData['nome']); ?></h2>
                        <span class="text-muted">Aqui você pode gerenciar suas informações.</span>
                    </div>
                </div>

                <hr class="mb-4">

                <h3 class="h5 mb-3">Informações da conta</h3>

                <dl class="row">
                    <dt class="col-sm-3">NOME:</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($userData['nome']); ?></dd>

                    <dt class="col-sm-3">EMAIL:</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($userData['email']); ?></dd>

                    <dt class="col-sm-3">NASCIMENTO:</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($userData['data_nascimento']); ?></dd>

                    <dt class="col-sm-3 ">ENDEREÇO:</dt>
                    <dd class="col-sm-3 "><?php echo htmlspecialchars($userData['endereco']); ?></dd>

                    <dt class="col-sm-3 ">CEP:</dt>
                    <dd class="col-sm-3 "><?php echo htmlspecialchars($userData['cep']); ?></dd>

                    <dt class="col-sm-3 ">COMPLEMENTO:</dt>
                    <dd class="col-sm-3 "><?php echo htmlspecialchars($userData['complemento']); ?></dd>

                    <dt class="col-sm-3 ">BAIRRO:</dt>
                    <dd class="col-sm-3 "><?php echo htmlspecialchars($userData['bairro']); ?></dd>

                    <dt class="col-sm-3 ">CIDADE:</dt>
                    <dd class="col-sm-3 "><?php echo htmlspecialchars($userData['cidade']); ?></dd>

                    <dt class="col-sm-3 ">ESTADO:</dt>
                    <dd class="col-sm-3 "><?php echo htmlspecialchars($userData['estado']); ?></dd>
                </dl>

                <a href="#" class="btn-product text-decoration-none mt-3" data-bs-toggle="modal"
                    data-bs-target="#modalEditarDados">
                    <i class="bi bi-pencil-fill me-2"></i>
                    Editar dados
                </a>

            </div>
        </div>
        <!-- INÍCIO DO MODAL -->
        <div class="modal p-4 fade" id="modalEditarDados" tabindex="-1" aria-labelledby="modalEditarDadosLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarDadosLabel">Editar Informações Pessoais</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="formAtualizarDados">
                        <div class="modal-body">
                            <!-- CAMPO OCULTO CRUCIAL ADICIONADO AQUI -->
                            <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>">

                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                    value="<?php echo htmlspecialchars($userData['nome']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento"
                                    value="<?php echo htmlspecialchars($userData['data_nascimento']); ?>">
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
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- FIM DO MODAL -->
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Intercepta o evento de submit do formulário
    $('#formAtualizarDados').on('submit', function(e) {
        
        // Impede o comportamento padrão do formulário (que recarregaria a página)
        e.preventDefault(); 

        // Coleta todos os dados do formulário
        var formData = $(this).serialize();

        // Envia os dados para o script PHP usando AJAX
        $.ajax({
            type: 'POST',
            url: '../app/model/atualizar_perfil.php', // O arquivo PHP que processará os dados
            data: formData,
            dataType: 'json', // Espera uma resposta em formato JSON do servidor
            
            success: function(response) {
                if (response.success) {
                    // Se deu certo
                    alert('Dados atualizados com sucesso!');
                    // Fecha o modal
                    $('#modalEditarDados').modal('hide');
                    // Recarrega a página para exibir os dados novos
                    location.reload(); 
                } else {
                    // Se deu errado, exibe a mensagem de erro
                    alert('Erro: ' + response.message);
                }
            },
            error: function() {
                // Em caso de erro de conexão ou no servidor
                alert('Ocorreu um erro no servidor. Tente novamente.');
            }
        });
    });
});
</script>
