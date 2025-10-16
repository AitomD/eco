<?php
require_once __DIR__ . '/../model/Produto.php';

header('Content-Type: application/json');

$produto = new Produto();

$categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
$marca     = isset($_GET['marca']) ? intval($_GET['marca']) : null;

$resultado = $produto->filtrar($categoria, $marca);

echo json_encode($resultado);
?>
