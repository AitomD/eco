<?php
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../core/user.php';
require_once __DIR__ . '/../model/Loja.php';
require_once __DIR__ . '/../model/NovoProduto.php';
require_once __DIR__ . '/../model/NovoProdutoInfo.php';
require_once __DIR__ . '/../model/NovoCelular.php';
require_once __DIR__ . '/../model/NovaImagem.php';

$response = ['sucesso' => false, 'erro' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método inválido.");
    }

    // === AUTENTICAÇÃO ===
    if (!Auth::isUserLoggedIn()) {
        throw new Exception("Faça login novamente.");
    }
    $id_admin = Auth::getAdminId();
    if (!$id_admin) throw new Exception("Sem permissão de admin.");
    
    $lojaModel = new Loja();
    $dadosLoja = $lojaModel->buscarPorAdminId($id_admin);
    if (!$dadosLoja) throw new Exception("Loja não encontrada.");
    $id_loja = $dadosLoja['id_loja'];

    // === DADOS COMUNS ===
    $nome      = $_POST['nome'] ?? null;
    $preco     = $_POST['preco'] ?? null;
    $categoria = $_POST['categoria'] ?? null;
    $id_marca  = $_POST['id_marca'] ?? null;

    if (!$nome || !$preco || !$categoria) {
        throw new Exception("Dados obrigatórios faltando.");
    }

    // Variáveis para IDs (iniciam nulas)
    $id_celular_gerado = null;
    $id_info_gerado = null;

    // === ETAPA 1: CRIAR O FILHO (ESPECIFICIDADES) PRIMEIRO ===

    if ($categoria == "3") {
        // --- É CELULAR ---
        $celularModel = new NovoCelular();
        
        // Salva o celular e pega o ID (ex: 15)
        $id_celular_gerado = $celularModel->inserir(
            $id_marca,
            $_POST['armazenamento'] ?? '',
            $_POST['ram'] ?? '',
            $_POST['cor'] ?? '',
            $_POST['tamanho_tela'] ?? '',
            $_POST['processador'] ?? '',
            $_POST['camera_traseira'] ?? '',
            $_POST['camera_frontal'] ?? '',
            $_POST['bateria'] ?? ''
        );

    } else {
        // --- É PC OU NOTEBOOK ---
        $infoModel = new NovoProdutoInfo();

        // Salva o info e pega o ID (ex: 20)
        $id_info_gerado = $infoModel->inserir(
            $_POST['descricao'] ?? '',
            $id_marca,
            $categoria,
            $_POST['ram'] ?? '',
            $_POST['armazenamento'] ?? '',
            $_POST['processador'] ?? '',
            $_POST['placa_mae'] ?? '',
            $_POST['placa_video'] ?? '',
            $_POST['fonte'] ?? '',
            $_POST['cor'] ?? ''
        );
    }

    // === ETAPA 2: CRIAR O PAI (PRODUTO) USANDO O ID DO FILHO ===
    
    $produtoModel = new NovoProduto();
    
    // Passamos os IDs gerados. Um terá número, o outro será NULL.
    $id_produto = $produtoModel->inserir(
        $nome, 
        $preco, 
        $id_loja, 
        $id_celular_gerado, 
        $id_info_gerado
    );

    if (!$id_produto) {
        throw new Exception("Erro ao salvar o produto principal.");
    }

    // === ETAPA 3: SALVAR IMAGENS (Vinculadas ao filho, conforme sua classe NovaImagem) ===
    
    if (isset($_POST['imagens']) && is_array($_POST['imagens'])) {
        $imagemModel = new NovaImagem();
        $ordem = 1;

        foreach ($_POST['imagens'] as $url) {
            if (!empty($url)) {
                $imagemModel->inserir(
                    $id_info_gerado,    // Se for PC, vai o ID. Se Celular, vai NULL
                    $url,
                    $ordem,
                    $id_celular_gerado  // Se for Celular, vai o ID. Se PC, vai NULL
                );
                $ordem++;
            }
        }
    }

    $response['sucesso'] = true;
    $response['message'] = "Cadastrado com sucesso!";

} catch (Exception $e) {
    $response['erro'] = $e->getMessage();
}

echo json_encode($response);