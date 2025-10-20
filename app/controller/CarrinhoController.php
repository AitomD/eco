<?php
// app/controller/CarrinhoController.php
// Controlador para gerenciar operações do carrinho
class CarrinhoController {
    
    public static function init() {
        // Inicializar o carrinho se não existir
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }
    
    public static function adicionarAoCarrinho($id, $nome, $preco, $imagem, $quantidade = 1) {
        self::init();
        
        if (isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
        } else {
            $_SESSION['carrinho'][$id] = [
                'id' => $id,
                'nome' => $nome,
                'preco' => floatval($preco),
                'imagem' => $imagem,
                'quantidade' => intval($quantidade)
            ];
        }
    }

    public static function removerDoCarrinho($id) {
        self::init();
        
        if (isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }
    }
    
    public static function atualizarQuantidade($id, $quantidade) {
        self::init();
        
        if (isset($_SESSION['carrinho'][$id])) {
            $quantidade = intval($quantidade);
            if ($quantidade <= 0) {
                self::removerDoCarrinho($id);
            } else {
                $_SESSION['carrinho'][$id]['quantidade'] = $quantidade;
            }
        }
    }
    
    public static function limparCarrinho() {
        $_SESSION['carrinho'] = [];
    }
    
    public static function calcularTotal() {
        self::init();
        
        $total = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
        return $total;
    }
    
    public static function contarItens() {
        self::init();
        
        $count = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $count += $item['quantidade'];
        }
        return $count;
    }
    
    public static function getItens() {
        self::init();
        return $_SESSION['carrinho'];
    }
    
    public static function processarAcao() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
            $acao = $_POST['acao'];
            
            switch ($acao) {
                case 'adicionar':
                    $id = $_POST['id'] ?? '';
                    $nome = $_POST['nome'] ?? '';
                    $preco = $_POST['preco'] ?? 0;
                    $imagem = $_POST['imagem'] ?? '';
                    $quantidade = $_POST['quantidade'] ?? 1;
                    self::adicionarAoCarrinho($id, $nome, $preco, $imagem, $quantidade);
                    break;
                    
                case 'remover':
                    $id = $_POST['id'] ?? '';
                    self::removerDoCarrinho($id);
                    break;
                    
                case 'atualizar':
                    $id = $_POST['id'] ?? '';
                    $quantidade = $_POST['quantidade'] ?? 0;
                    self::atualizarQuantidade($id, $quantidade);
                    break;
                    
                case 'limpar':
                    self::limparCarrinho();
                    break;
            }
            
            // Redirecionar para evitar resubmissão
            header('Location: index.php?url=carrinho');
            exit;
        }
    }
}
?>