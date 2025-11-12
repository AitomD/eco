<?php
require_once __DIR__ . '/../core/Database.php';

class Loja
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    /**
     * Busca as informações do produto, incluindo:
     * - nome do produto, cor e preço
     * - nome da loja responsável
     * - endereço e cidade da loja
     *
     * @param int $idProduto O ID do produto.
     * @return array|null Retorna um array associativo com os dados combinados
     *                    ou null se não for encontrado.
     */
    public function buscarPorProdutoId($idProduto)
    {
        try {
            $sql = "
                SELECT 
                    p.id_produto,
                    p.nome AS nome_produto,
                    p.cor,
                    p.preco,
                    l.id_loja,
                    l.nome AS nome_loja,
                    e.endereco,
                    e.cidade,
                    e.estado
                FROM produto AS p
                INNER JOIN loja AS l ON p.id_loja = l.id_loja
                INNER JOIN endereco AS e ON l.id_endereco = e.id_endereco
                WHERE p.id_produto = ?
                LIMIT 1
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idProduto]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            return $dados ?: null;

        } catch (PDOException $e) {
            error_log("Erro ao buscar loja e endereço pelo produto: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Filtra lojas por ID do administrador
     *
     * @param int $idAdmin O ID do administrador.
     * @return array|null Retorna um array com os dados ou null se não encontrado.
     */
    public function buscarPorAdminId($idAdmin)
    {
        try {
            $sql = "
                SELECT 
                    l.id_loja,
                    l.nome AS nome_loja,
                    e.endereco,
                    e.cidade,
                    e.estado
                FROM loja AS l
                INNER JOIN endereco AS e ON l.id_endereco = e.id_endereco
                WHERE l.id_admin = ?
            ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idAdmin]);
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $dados ?: null;

        } catch (PDOException $e) {
            error_log("Erro ao buscar lojas por admin: " . $e->getMessage());
            return null;
        }
    }
}
