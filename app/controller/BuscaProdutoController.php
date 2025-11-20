<?php
require_once __DIR__ . '/../core/Database.php';

if (!isset($_GET['id'])) {
    echo "ID não fornecido.";
    exit;
}

$id = $_GET['id'];
$pdo = Database::conectar();

// 1. Buscar dados do Produto PAI
$stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// 2. Verificar se é PC (Info) ou Celular
$dadosEspecificos = [];
$tipo = '';

if (!empty($produto['id_info'])) {
    // É Computador/Notebook
    $tipo = 'info';
    $sqlInfo = "SELECT * FROM produto_info WHERE id_info = ?";
    $stmtInfo = $pdo->prepare($sqlInfo);
    $stmtInfo->execute([$produto['id_info']]);
    $dadosEspecificos = $stmtInfo->fetch(PDO::FETCH_ASSOC);
    
} elseif (!empty($produto['id_celular'])) {
    // É Celular
    $tipo = 'celular';
    $sqlCel = "SELECT * FROM celular WHERE id_celular = ?";
    $stmtCel = $pdo->prepare($sqlCel);
    $stmtCel->execute([$produto['id_celular']]);
    $dadosEspecificos = $stmtCel->fetch(PDO::FETCH_ASSOC);
}
?>

<form id="form-editar-produto">
    <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">
    <input type="hidden" name="tipo" value="<?= $tipo ?>">
    
    <?php if($tipo == 'info'): ?>
        <input type="hidden" name="id_especifico" value="<?= $produto['id_info'] ?>">
    <?php elseif($tipo == 'celular'): ?>
        <input type="hidden" name="id_especifico" value="<?= $produto['id_celular'] ?>">
    <?php endif; ?>

    <h6 class="text-primary border-bottom pb-2">Dados Gerais</h6>
    <div class="mb-3">
        <label class="form-label">Nome do Produto</label>
        <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>">
    </div>
    <div class="mb-3">
        <label class="form-label">Preço (R$)</label>
        <input type="number" step="0.01" class="form-control" name="preco" value="<?= $produto['preco'] ?>">
    </div>

    <h6 class="text-primary border-bottom pb-2 mt-4">Especificações Técnicas</h6>

    <?php if ($tipo == 'info'): ?>
        
        <div class="row">
            <div class="col-6 mb-2">
                <label class="form-label">Processador</label>
                <input type="text" class="form-control" name="processador" value="<?= htmlspecialchars($dadosEspecificos['processador'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">RAM</label>
                <input type="text" class="form-control" name="ram" value="<?= htmlspecialchars($dadosEspecificos['ram'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Armazenamento</label>
                <input type="text" class="form-control" name="armazenamento" value="<?= htmlspecialchars($dadosEspecificos['armazenamento'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Placa de Vídeo</label>
                <input type="text" class="form-control" name="placa_video" value="<?= htmlspecialchars($dadosEspecificos['placa_video'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Placa Mãe</label>
                <input type="text" class="form-control" name="placa_mae" value="<?= htmlspecialchars($dadosEspecificos['placa_mae'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Fonte</label>
                <input type="text" class="form-control" name="fonte" value="<?= htmlspecialchars($dadosEspecificos['fonte'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Cor</label>
                <input type="text" class="form-control" name="cor" value="<?= htmlspecialchars($dadosEspecificos['cor'] ?? '') ?>">
            </div>
            <div class="col-12 mb-2">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao"><?= htmlspecialchars($dadosEspecificos['descricao'] ?? '') ?></textarea>
            </div>
        </div>

    <?php elseif ($tipo == 'celular'): ?>

        <div class="row">
            <div class="col-6 mb-2">
                <label class="form-label">Processador</label>
                <input type="text" class="form-control" name="processador" value="<?= htmlspecialchars($dadosEspecificos['processador'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">RAM</label>
                <input type="text" class="form-control" name="ram" value="<?= htmlspecialchars($dadosEspecificos['ram'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Armazenamento</label>
                <input type="text" class="form-control" name="armazenamento" value="<?= htmlspecialchars($dadosEspecificos['armazenamento'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Tela</label>
                <input type="text" class="form-control" name="tamanho_tela" value="<?= htmlspecialchars($dadosEspecificos['tamanho_tela'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Cor</label>
                <input type="text" class="form-control" name="cor" value="<?= htmlspecialchars($dadosEspecificos['cor'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Bateria</label>
                <input type="text" class="form-control" name="bateria" value="<?= htmlspecialchars($dadosEspecificos['bateria'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Câm. Traseira</label>
                <input type="text" class="form-control" name="camera_traseira" value="<?= htmlspecialchars($dadosEspecificos['camera_traseira'] ?? '') ?>">
            </div>
            <div class="col-6 mb-2">
                <label class="form-label">Câm. Frontal</label>
                <input type="text" class="form-control" name="camera_frontal" value="<?= htmlspecialchars($dadosEspecificos['camera_frontal'] ?? '') ?>">
            </div>
        </div>

    <?php endif; ?>

    <div class="mt-4 d-grid gap-2">
        <button type="button" onclick="salvarAlteracoes()" class="btn btn-success">Salvar Alterações</button>
    </div>
</form>