<?php
require_once __DIR__ . '/../core/Database.php';

class Pedido
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    /**
     * Criar um novo pedido
     * @param array $dadosPedido Array com os dados do pedido
     * @return int ID do pedido criado
     * @throws Exception Se ocorrer um erro de banco de dados
     */
    public function criarPedido($dadosPedido)
    {
        try {
            $this->pdo->beginTransaction();

            // Inserir o pedido principal
            $stmt = $this->pdo->prepare("
                INSERT INTO pedido (id_user, id_loja, id_cupom, data_pedido, status, total, desconto, total_final) 
                VALUES (?, ?, ?, NOW(), 'pendente', ?, ?, ?)
            ");
            
            $stmt->execute([
                $dadosPedido['id_user'],
                $dadosPedido['id_loja'],
                $dadosPedido['id_cupom'],
                $dadosPedido['total'],
                $dadosPedido['desconto'],
                $dadosPedido['total_final']
            ]);

            $idPedido = $this->pdo->lastInsertId();

            // Inserir os produtos do pedido
            if (!empty($dadosPedido['produtos'])) {
                $stmt = $this->pdo->prepare("
                    INSERT INTO pedido_produto (id_pedido, id_produto, quantidade, preco_unitario) 
                    VALUES (?, ?, ?, ?)
                ");

                foreach ($dadosPedido['produtos'] as $produto) {
                    $stmt->execute([
                        $idPedido,
                        $produto['id_produto'],
                        $produto['quantidade'],
                        $produto['preco_unitario']
                    ]);
                }
            }

            $this->pdo->commit();
            return $idPedido;

        } catch (PDOException $e) {
            $this->pdo->rollback();
            error_log("Erro de PDO ao criar pedido: " . $e->getMessage());
            
            throw new Exception("Erro de banco de dados: " . $e->getMessage());
            // =================================================================
        }
    }

    /**
     * Buscar pedido por ID
     * @param int $idPedido
     * @return array|false
     */
    public function buscarPorId($idPedido)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    p.id_pedido,
                    p.id_user,
                    p.id_loja,
                    p.id_cupom,
                    p.data_pedido,
                    p.status,
                    p.total,
                    p.desconto,
                    p.total_final,
                    l.nome as nome_loja,
                    u.nome as nome_usuario,
                    c.codigo as codigo_cupom -- <<< ADICIONADO PARA PEGAR O CÓDIGO DO CUPOM
                FROM pedido p
                LEFT JOIN loja l ON l.id_loja = p.id_loja
                LEFT JOIN user u ON u.id_user = p.id_user
                LEFT JOIN cupom c ON c.id_cupom = p.id_cupom -- <<< ADICIONADO JOIN DO CUPOM
                WHERE p.id_pedido = ?
            ");
            
            $stmt->execute([$idPedido]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar produtos de um pedido
     * @param int $idPedido
     * @return array
     */
    public function buscarProdutosPedido($idPedido)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    pp.id_pedido_produto,
                    pp.id_produto,
                    pp.quantidade,
                    pp.preco_unitario,
                    pr.nome as nome_produto,
                    pr.cor,
                    pr.id_loja,
                    l.nome as nome_loja,
                    (pp.quantidade * pp.preco_unitario) as subtotal
                FROM pedido_produto pp
                INNER JOIN produto pr ON pr.id_produto = pp.id_produto
                LEFT JOIN loja l ON l.id_loja = pr.id_loja
                WHERE pp.id_pedido = ?
                ORDER BY pp.id_pedido_produto
            ");
            
            $stmt->execute([$idPedido]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar produtos do pedido: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar todos os pedidos de um usuário
     * @param int $idUser
     * @return array
     */
    public function buscarPorUsuario($idUser)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    p.id_pedido,
                    p.id_loja,
                    p.data_pedido,
                    p.status,
                    p.total,
                    p.desconto,
                    p.total_final,
                    l.nome as nome_loja,
                    COUNT(pp.id_produto) as quantidade_itens
                FROM pedido p
                LEFT JOIN loja l ON l.id_loja = p.id_loja
                LEFT JOIN pedido_produto pp ON pp.id_pedido = p.id_pedido
                WHERE p.id_user = ?
                
                GROUP BY 
                    p.id_pedido, 
                    p.id_loja, 
                    p.data_pedido, 
                    p.status, 
                    p.total, 
                    p.desconto, 
                    p.total_final, 
                    l.nome
                    
                ORDER BY p.data_pedido DESC
            ");
            
            $stmt->execute([$idUser]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar pedidos do usuário: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualizar status do pedido
     * @param int $idPedido
     * @param string $novoStatus
     * @return bool
     */
    public function atualizarStatus($idPedido, $novoStatus)
    {
        $statusValidos = ['pendente', 'confirmado', 'enviado', 'entregue', 'cancelado'];
        if (!in_array($novoStatus, $statusValidos)) {
            return false;
        }

        try {
            $stmt = $this->pdo->prepare("
                UPDATE pedido 
                SET status = ? 
                WHERE id_pedido = ?
            ");
            
            return $stmt->execute([$novoStatus, $idPedido]);

        } catch (PDOException $e) {
            error_log("Erro ao atualizar status do pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar loja predominante (com maior subtotal) nos itens do carrinho.
     * @param array $itensCarrinho
     * @return int|null ID da loja predominante
     */
    public function determinarLojaPedido($itensCarrinho)
    {
        if (empty($itensCarrinho)) {
            return null;
        }

        try {
            $produtosValidados = $this->validarProdutosCarrinho($itensCarrinho);

            if (empty($produtosValidados)) {
                return null;
            }

            $subtotalPorLoja = [];
            foreach ($produtosValidados as $produto) {
                $idLoja = $produto['id_loja'];
                if (!isset($subtotalPorLoja[$idLoja])) {
                    $subtotalPorLoja[$idLoja] = 0;
                }
                $subtotalPorLoja[$idLoja] += $produto['subtotal'];
            }

            if (empty($subtotalPorLoja)) {
                return null;
            }

            arsort($subtotalPorLoja);
            return key($subtotalPorLoja);

        } catch (Exception $e) { 
            error_log("Erro ao determinar loja do pedido: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Validar produtos do carrinho e obter informações atualizadas
     * @param array $itensCarrinho
     * @return array Array com produtos validados
     */
    public function validarProdutosCarrinho($itensCarrinho)
    {
        if (empty($itensCarrinho)) {
            return [];
        }

        try {
            $produtoIds = array_keys($itensCarrinho);
            $placeholders = str_repeat('?,', count($produtoIds) - 1) . '?';

            $stmt = $this->pdo->prepare("
                SELECT 
                    pr.id_produto,
                    pr.nome,
                    pr.preco,
                    pr.id_loja,
                    l.nome as nome_loja
                FROM produto pr
                LEFT JOIN loja l ON l.id_loja = pr.id_loja
                WHERE pr.id_produto IN ($placeholders)
            ");

            $stmt->execute($produtoIds);
            $produtosBanco = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $produtosValidados = [];
            foreach ($produtosBanco as $produto) {
                $idProduto = $produto['id_produto'];
                
                if (isset($itensCarrinho[$idProduto])) {
                    $quantidade = (int)$itensCarrinho[$idProduto]['quantidade'];
                    $preco = (float)$produto['preco'];
                    
                    $produtosValidados[] = [
                        'id_produto' => $idProduto,
                        'nome' => $produto['nome'],
                        'preco_unitario' => $preco,
                        'quantidade' => $quantidade,
                        'id_loja' => $produto['id_loja'],
                        'nome_loja' => $produto['nome_loja'],
                        'subtotal' => $preco * $quantidade 
                    ];
                }
            }

            return $produtosValidados;

        } catch (PDOException $e) {
            error_log("Erro ao validar produtos do carrinho: " . $e->getMessage());
            return [];
        }
    }
}
?>