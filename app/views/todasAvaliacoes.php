<?php
require_once '../app/core/helpers.php';
require_once '../app/core/Database.php';
require_once '../app/model/avaliações.php';

// Iniciar sessão de forma segura
iniciarSessaoSegura();

// Obter ID do produto
$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_produto) {
    header('Location: ../public/index.php');
    exit;
}

$avaliacaoObj = new Avaliacao();

// Paginação
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?: 1;
$limite = 10;
$offset = ($pagina - 1) * $limite;

// Buscar dados do produto
try {
    $pdo = Database::conectar();
    $sql = "SELECT nome FROM produto WHERE id_produto = :id_produto";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        header('Location: ../public/index.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar produto: " . $e->getMessage());
}

// Buscar avaliações e estatísticas
$mediaAvaliacoes = $avaliacaoObj->calcularMediaAvaliacoes($id_produto);
$avaliacoes = $avaliacaoObj->obterAvaliacoesProduto($id_produto, $limite, $offset);
$totalAvaliacoes = $mediaAvaliacoes['total'] ?? 0;
$totalPaginas = ceil($totalAvaliacoes / $limite);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações - <?= htmlspecialchars($produto['nome']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/css/main.css">
</head>
<body>

<div class="container py-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../public/index.php">Início</a></li>
                    <li class="breadcrumb-item"><a href="itemCompra.php?id=<?= $id_produto ?>">Produto</a></li>
                    <li class="breadcrumb-item active">Avaliações</li>
                </ol>
            </nav>
            <h1 class="h3">Avaliações de <?= htmlspecialchars($produto['nome']) ?></h1>
        </div>
    </div>

    <!-- Resumo das Avaliações -->
    <?php if ($mediaAvaliacoes && $mediaAvaliacoes['total'] > 0): ?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4 text-center">
                        <h2 class="display-3 mb-0 fw-bold"><?= $mediaAvaliacoes['media'] ?></h2>
                        <?= Avaliacao::gerarEstrelas($mediaAvaliacoes['media'], 'text-warning fs-4') ?>
                        <p class="text-muted mt-2"><?= $mediaAvaliacoes['total'] ?> avaliações</p>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-3">Distribuição das notas:</h5>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <div class="row align-items-center mb-2">
                                <div class="col-2 text-end">
                                    <?= $i ?> <i class="bi bi-star-fill text-warning"></i>
                                </div>
                                <div class="col-8">
                                    <div class="progress">
                                        <?php 
                                        $porcentagem = $mediaAvaliacoes['total'] > 0 
                                            ? ($mediaAvaliacoes['distribuicao'][$i] / $mediaAvaliacoes['total']) * 100 
                                            : 0; 
                                        ?>
                                        <div class="progress-bar bg-warning" style="width: <?= $porcentagem ?>%"></div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <?= $mediaAvaliacoes['distribuicao'][$i] ?> (<?= round($porcentagem, 1) ?>%)
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Avaliações -->
        <?php if (!empty($avaliacoes)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Todas as Avaliações</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($avaliacoes as $avaliacao): ?>
                        <div class="avaliacao-item border-bottom pb-4 mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($avaliacao['nome_usuario']) ?></h6>
                                    <?= Avaliacao::gerarEstrelas($avaliacao['nota'], 'text-warning') ?>
                                </div>
                                <small class="text-muted">
                                    <?= date('d/m/Y \à\s H:i', strtotime($avaliacao['data_avaliacao'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Paginação -->
            <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Paginação das avaliações" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagina > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?id=<?= $id_produto ?>&pagina=<?= $pagina - 1 ?>">Anterior</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                                <a class="page-link" href="?id=<?= $id_produto ?>&pagina=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($pagina < $totalPaginas): ?>
                            <li class="page-item">
                                <a class="page-link" href="?id=<?= $id_produto ?>&pagina=<?= $pagina + 1 ?>">Próxima</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Não há avaliações para este produto ainda.
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-quote display-1 text-muted mb-3"></i>
                <h4 class="text-muted">Este produto ainda não foi avaliado</h4>
                <p class="text-muted">Seja o primeiro a avaliar e ajude outros clientes!</p>
                <a href="itemCompra.php?id=<?= $id_produto ?>" class="btn btn-primary">
                    Voltar ao Produto
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Exibir mensagens de sucesso/erro -->
<?php 
$mensagemSucesso = obterMensagemSucesso();
$mensagemErro = obterMensagemErro();
?>

<?php if ($mensagemSucesso): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Sucesso</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                <?= escaparHtml($mensagemSucesso) ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($mensagemErro): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                <strong class="me-auto">Erro</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                <?= escaparHtml($mensagemErro) ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.estrelas {
    color: #ffc107;
}

.progress {
    height: 10px;
}

.avaliacao-item:last-child {
    border-bottom: none !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.toast {
    min-width: 300px;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>