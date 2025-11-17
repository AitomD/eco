<?php
require_once __DIR__ . '/../core/Database.php';

class Produto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

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
    -- Imagens de produtos normais (através de produto_info)
    CASE 
        WHEN p.id_info IS NOT NULL THEN 
            (SELECT i.url 
             FROM imagem i 
             WHERE i.id_info = pi.id_info 
             ORDER BY i.ordem ASC 
             LIMIT 1)
        WHEN p.id_celular IS NOT NULL THEN
            (SELECT i.url 
             FROM imagem i 
             WHERE i.id_celular = p.id_celular 
             ORDER BY i.ordem ASC 
             LIMIT 1)
        ELSE NULL
    END AS imagem,
    -- quantidade atual do estoque
    (SELECT COALESCE(MAX(e.total), 'Sem Estoque')
 FROM estoque e
 WHERE e.id_produto = p.id_produto
) AS quantidade_disponivel
FROM produto p
LEFT JOIN produto_info pi ON p.id_info = pi.id_info
LEFT JOIN celular cel ON p.id_celular = cel.id_celular
LEFT JOIN marca m ON COALESCE(pi.id_marca, cel.id_marca) = m.id_marca
LEFT JOIN categoria c ON COALESCE(pi.id_categoria, cel.id_categoria) = c.id_categoria
WHERE 1=1";

        $params = [];

        if ($id_categoria) {
            $sql .= " AND COALESCE(pi.id_categoria, cel.id_categoria) = :categoria";
            $params[':categoria'] = $id_categoria;
        }

        if ($id_marca) {
            $sql .= " AND COALESCE(pi.id_marca, cel.id_marca) = :marca";
            $params[':marca'] = $id_marca;
        }

        $sql .= " ORDER BY p.nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>