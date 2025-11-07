<?php
require_once __DIR__ . '/../core/Database.php';

class Pedidos
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

public function buscarPorUsuario(int $id_user): array
    {
  
        $sql = "SELECT 
                    p.id_pedido,
                    p.id_user,
                    p.id_loja,
                    p.id_cupom,
                    p.data_pedido,
                    p.status,
                    p.total,
                    p.desconto,
                    p.total_final,
                    c.codigo as codigo_cupom  
                FROM 
                    pedido p 
                LEFT JOIN
                    cupom c ON p.id_cupom = c.id_cupom 
                WHERE 
                    p.id_user = :id_user
                ORDER BY 
                    p.data_pedido DESC"; 

        try {

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar pedidos por usuário: " . $e.getMessage());
            return [];
        }
    }

    public function buscarPorId(int $id_pedido)
    {
        $sql = "SELECT 
    p.id_pedido,
    p.id_user,
    p.id_loja,
    p.id_cupom,
    p.data_pedido,
    p.status,
    p.total,
    p.desconto,      
    p.total_final,
    c.id_cupom,
    c.tipo_desconto,
    c.codigo as codigo_cupom  
FROM 
    pedido p
LEFT JOIN 
    cupom c ON p.id_cupom = c.id_cupom -- Juntando as tabelas
WHERE 
    p.id_pedido = :id_pedido";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            
            // Retorna apenas um resultado
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar pedido por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Criar um novo pedido com produtos
     * @param array $dadosPedido
     * @return int|false ID do pedido criado ou false em caso de erro
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
            error_log("Erro ao criar pedido: " . $e->getMessage());
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
}
?>