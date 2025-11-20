<?php
require_once __DIR__ . '/../core/Database.php';

class NovaImagem
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function puxar()
    {
        $sql = "SELECT 
                    id,
                    id_info,
                    url,
                    ordem,
                    id_celular
                FROM imagem";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $id_info, $url, $ordem, $id_celular)
    {
        $sql = "UPDATE imagem SET
                    id_info = ?,
                    url = ?,
                    ordem = ?,
                    id_celular = ?
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $id_info,
            $url,
            $ordem,
            $id_celular,
            $id
        ]);
    }

    public function apagar($id)
    {
        $sql = "DELETE FROM imagem WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function inserir($id_info, $url, $ordem, $id_produto)
{
    $sql = "INSERT INTO imagem (id_info, url, ordem, id_celular)
            VALUES (:id_info, :url, :ordem, :id_produto)";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        ':id_info' => $id_info,
        ':url' => $url,
        ':ordem' => $ordem,
        ':id_produto' => $id_produto
    ]);
}

}
