<?php
require_once __DIR__ . '/../core/Database.php';

class EstoqueLoja {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::conectar();
    }

    // Busca todos os produtos e estoque de uma loja específica
    public function buscarEstoquePorLoja($idLoja) {
        $sql = "SELECT 
                    e.id_estoque,
                    e.id_produto,
                    e.quantidade,
                    e.total,
                    e.data_estoque,
                    e.tipo AS tipo_estoque,
                    p.nome AS nome_produto,
                    p.cor,
                    p.preco,
                    p.data_att AS data_atualizacao_produto
                FROM estoque e
                JOIN produto p ON e.id_produto = p.id_produto
                WHERE e.id_loja = :id_loja";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_loja', $idLoja, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // retorna array de produtos com estoque
    }
}
?>
