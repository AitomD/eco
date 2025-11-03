<?php
require_once __DIR__ . '/../model/NovoProduto.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Lê o JSON enviado
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true); // true = array associativo

    if (!$dados) {
        echo json_encode(['status' => 'error', 'mensagem' => 'Dados inválidos.']);
        exit;
    }

    // Captura os dados do array
    $dadosProduto = [
        'nome' => $dados['nome'] ?? '',
        'cor' => $dados['cor'] ?? '',
        'preco' => $dados['preco'] ?? 0,
        'categoria' => $dados['categoria'] ?? 0,
        'marca' => $dados['marca'] ?? 0,
        'processador' => $dados['processador'] ?? '',
        'memoria' => $dados['memoria'] ?? '',
        'armazenamento' => $dados['armazenamento'] ?? '',
        'placa_video' => $dados['placa_video'] ?? '',
        'placa_mae' => $dados['placa_mae'] ?? '',
        'fonte' => $dados['fonte'] ?? '',
        'url_imagem' => $dados['url_imagem'] ?? [],
        'id_loja' => $dados['id_loja'] ?? 1
    ];

    $novoProduto = new NovoProduto();
    $sucesso = $novoProduto->cadastrarProduto($dadosProduto);

    if ($sucesso) {
        echo json_encode(['status' => 'success', 'mensagem' => 'Produto cadastrado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'mensagem' => 'Erro ao cadastrar produto.']);
    }

} else {
    echo json_encode(['status' => 'error', 'mensagem' => 'Requisição inválida.']);
}
