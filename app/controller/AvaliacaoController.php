<?php
require_once '../core/helpers.php';
require_once '../model/avaliações.php';

// Iniciar sessão de forma segura
iniciarSessaoSegura();

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/index.php');
    exit;
}

// Verificar se o usuário está logado
if (!usuarioLogado()) {
    definirMensagemErro('Você precisa estar logado para avaliar um produto.');
    redirecionar('../views/login.php');
}

$avaliacaoObj = new Avaliacao();
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'adicionar':
        // Validar dados
        $id_produto = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);
        $nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);
        $id_user = obterIdUsuario();

        if (!$id_produto || !$nota) {
            definirMensagemErro('Dados inválidos fornecidos.');
            break;
        }

        if ($nota < 1 || $nota > 5) {
            definirMensagemErro('A nota deve estar entre 1 e 5.');
            break;
        }

        // Verificar se o usuário já avaliou este produto
        if ($avaliacaoObj->jaAvaliou($id_user, $id_produto)) {
            definirMensagemErro('Você já avaliou este produto.');
            break;
        }

        // Adicionar avaliação
        $resultado = $avaliacaoObj->adicionarAvaliacao($id_user, $id_produto, $nota);

        if ($resultado) {
            definirMensagemSucesso('Avaliação adicionada com sucesso!');
        } else {
            definirMensagemErro('Erro ao adicionar avaliação. Tente novamente.');
        }
        break;

    case 'atualizar':
        $id_avaliacao = filter_input(INPUT_POST, 'id_avaliacao', FILTER_VALIDATE_INT);
        $nota = filter_input(INPUT_POST, 'nota', FILTER_VALIDATE_INT);
        $id_user = obterIdUsuario();

        if (!$id_avaliacao || !$nota) {
            definirMensagemErro('Dados inválidos fornecidos.');
            break;
        }

        if ($nota < 1 || $nota > 5) {
            definirMensagemErro('A nota deve estar entre 1 e 5.');
            break;
        }

        $resultado = $avaliacaoObj->atualizarAvaliacao($id_avaliacao, $id_user, $nota);

        if ($resultado) {
            definirMensagemSucesso('Avaliação atualizada com sucesso!');
        } else {
            definirMensagemErro('Erro ao atualizar avaliação ou você não tem permissão.');
        }
        break;

    case 'remover':
        $id_avaliacao = filter_input(INPUT_POST, 'id_avaliacao', FILTER_VALIDATE_INT);
        $id_user = obterIdUsuario();

        if (!$id_avaliacao) {
            definirMensagemErro('ID da avaliação inválido.');
            break;
        }

        $resultado = $avaliacaoObj->removerAvaliacao($id_avaliacao, $id_user);

        if ($resultado) {
            definirMensagemSucesso('Avaliação removida com sucesso!');
        } else {
            definirMensagemErro('Erro ao remover avaliação ou você não tem permissão.');
        }
        break;

    default:
        definirMensagemErro('Ação não reconhecida.');
        break;
}

// Redirecionar de volta para a página do produto ou página de origem
$redirect_url = $_POST['redirect_url'] ?? '../../public/index.php';

// Se temos o ID do produto, redirecionar para a página do produto
if (isset($_POST['id_produto']) && is_numeric($_POST['id_produto'])) {
    $redirect_url = '../views/itemCompra.php?id=' . $_POST['id_produto'];
}

redirecionar($redirect_url);
?>