<?php
require_once __DIR__ . '/../core/Database.php';

class NovoProdutoInfo
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
                    descricao,
                    id_marca,
                    id_categoria,
                    ram,
                    armazenamento,
                    processador,
                    placa_mae,
                    placa_video,
                    fonte,
                    cor
                FROM produto_info";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function atualizar(
        $id,
        $descricao,
        $id_marca,
        $id_categoria,
        $ram,
        $armazenamento,
        $processador,
        $placa_mae,
        $placa_video,
        $fonte,
        $cor
    ) {
        $sql = "UPDATE produto_info SET
                    descricao = ?,
                    id_marca = ?,
                    id_categoria = ?,
                    ram = ?,
                    armazenamento = ?,
                    processador = ?,
                    placa_mae = ?,
                    placa_video = ?,
                    fonte = ?,
                    cor = ?
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $descricao,
            $id_marca,
            $id_categoria,
            $ram,
            $armazenamento,
            $processador,
            $placa_mae,
            $placa_video,
            $fonte,
            $cor,
            $id
        ]);
    }

    public function apagar($id)
    {
        $sql = "DELETE FROM produto_info WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function inserir($descricao, $id_marca, $id_categoria, $ram, $armazenamento, 
                        $processador, $placa_mae, $placa_video, $fonte, $cor)
{
    // NÃO TEM id_produto AQUI
    $sql = "INSERT INTO produto_info 
            (descricao, id_marca, id_categoria, ram, armazenamento, 
             processador, placa_mae, placa_video, fonte, cor)
            VALUES 
            (:descricao, :id_marca, :id_categoria, :ram, :armazenamento,
             :processador, :placa_mae, :placa_video, :fonte, :cor)";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':descricao' => $descricao,
        ':id_marca' => $id_marca,
        ':id_categoria' => $id_categoria,
        ':ram' => $ram,
        ':armazenamento' => $armazenamento,
        ':processador' => $processador,
        ':placa_mae' => $placa_mae,
        ':placa_video' => $placa_video,
        ':fonte' => $fonte,
        ':cor' => $cor
    ]);

    return $this->pdo->lastInsertId(); // Retorna o ID do Info gerado
}

}
