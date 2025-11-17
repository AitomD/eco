<?php
require_once __DIR__ . '/../core/Database.php';

class Produto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    // -------------------------
    // MÉTODO EXISTENTE (mantido)
    // -------------------------
    public function filtrar($id_categoria = null, $id_marca = null)
    {
        $sql = "SELECT 
            p.id_produto,
            p.nome,
            p.preco,
            m.nome AS marca,
            c.nome AS categoria,
            pi.ram,
            pi.armazenamento,
            pi.processador,
            pi.cor,
            pi.fonte,
            pi.placa_video,
            (SELECT i.url 
             FROM imagem i 
             WHERE i.id_info = pi.id_info 
             ORDER BY i.ordem ASC 
             LIMIT 1) AS imagem,
            (SELECT COALESCE(MAX(e.total), 'Sem Estoque')
             FROM estoque e
             WHERE e.id_produto = p.id_produto
            ) AS quantidade_disponivel
        FROM produto p
        LEFT JOIN produto_info pi ON p.id_info = pi.id_info
        LEFT JOIN marca m ON pi.id_marca = m.id_marca
        LEFT JOIN categoria c ON pi.id_categoria = c.id_categoria
        WHERE 1=1";

        $params = [];

        if ($id_categoria) {
            $sql .= " AND pi.id_categoria = :categoria";
            $params[':categoria'] = $id_categoria;
        }

        if ($id_marca) {
            $sql .= " AND pi.id_marca = :marca";
            $params[':marca'] = $id_marca;
        }

        $sql .= " ORDER BY p.nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorLoja($idLoja)
{
    $sql = "SELECT 
                p.id_produto,
                p.nome,
                p.preco,
                pi.cor,
                p.data_att
            FROM produto p
            LEFT JOIN produto_info pi ON pi.id_info = p.id_info
            WHERE p.id_loja = :idLoja
            ORDER BY p.data_att DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':idLoja', $idLoja, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
