<?php
require_once __DIR__ . '/../core/Database.php';

// Recebe JSON do front
$input = json_decode(file_get_contents('php://input'), true);

$valor_produto = $input['valor_produto'] ?? 0;
$quantidade_meses = $input['quantidade_meses'] ?? 1;

// Calcula valores
$valor_total = $valor_produto;
$valor_parcela = $quantidade_meses > 0 ? $valor_total / $quantidade_meses : 0;

// Aqui você pode inserir no banco, se quiser
/*
$pdo = Database::conectar();
$stmt = $pdo->prepare("INSERT INTO seguro (valor_produto, quantidade_meses, valor_total, valor_parcela) VALUES (?, ?, ?, ?)");
$stmt->execute([$valor_produto, $quantidade_meses, $valor_total, $valor_parcela]);
*/

header('Content-Type: application/json');
echo json_encode([
    'valor_total' => $valor_total,
    'valor_parcela' => $valor_parcela
]);
