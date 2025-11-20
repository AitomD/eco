<?php
require_once __DIR__ . '/../core/Database.php';

class NovoCelular
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
                    id_marca,
                    armazenamento,
                    ram,
                    cor,
                    tamanho_tela,
                    processador,
                    camera_traseira,
                    camera_frontal,
                    bateria
                FROM celular";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $id_marca, $armazenamento, $ram, $cor, $tamanho_tela, $processador, $camera_traseira, $camera_frontal, $bateria)
    {
        $sql = "UPDATE celular SET
                    id_marca = ?,
                    armazenamento = ?,
                    ram = ?,
                    cor = ?,
                    tamanho_tela = ?,
                    processador = ?,
                    camera_traseira = ?,
                    camera_frontal = ?,
                    bateria = ?
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $id_marca,
            $armazenamento,
            $ram,
            $cor,
            $tamanho_tela,
            $processador,
            $camera_traseira,
            $camera_frontal,
            $bateria,
            $id
        ]);
    }

    public function apagar($id)
    {
        $sql = "DELETE FROM celular WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function inserir($id_marca, $armazenamento, $ram, $cor, $tamanho_tela,
                        $processador, $camera_traseira, $camera_frontal, $bateria)
{
    // NÃO TEM id_produto AQUI
    $sql = "INSERT INTO celular 
            (id_marca, armazenamento, ram, cor, tamanho_tela,
             processador, camera_traseira, camera_frontal, bateria)
            VALUES 
            (:id_marca, :armazenamento, :ram, :cor, :tamanho_tela,
             :processador, :camera_traseira, :camera_frontal, :bateria)";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_marca' => $id_marca,
        ':armazenamento' => $armazenamento,
        ':ram' => $ram,
        ':cor' => $cor,
        ':tamanho_tela' => $tamanho_tela,
        ':processador' => $processador,
        ':camera_traseira' => $camera_traseira,
        ':camera_frontal' => $camera_frontal,
        ':bateria' => $bateria
    ]);

    return $this->pdo->lastInsertId(); // Retorna o ID do Celular gerado
}

}
