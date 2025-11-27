<?php
require_once __DIR__ . '/../model/Produto.php';

header('Content-Type: application/json');

$produto = new Produto();

$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
$marca     = isset($_GET['marca']) ? intval($_GET['marca']) : null;

$resultado = $produto->filtrar($categoria, $marca);

// Validar imagem (link externo ou inválido)
foreach ($resultado as &$p) {

    $imagem = isset($p['imagem']) ? trim($p['imagem']) : '';

    if (!$imagem || strlen($imagem) < 10 || 
        !(str_starts_with($imagem, 'http://') || str_starts_with($imagem, 'https://'))) {

        $p['imagem'] = '/eco/public/util/NaoEncontrado.jpg';

    } else {
        // Link válido → mantém
        $p['imagem'] = $imagem;
    }
}

echo json_encode($resultado);
exit;
