<?php
require_once __DIR__ . '/../core/Database.php';

class Celular
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    // Inserir novo celular
    public function inserirCelular($armazenamento, $ram, $cor, $tamanhoTela, $processador, $cameraTraseira, $cameraFrontal, $bateria)
    {
        try {
            $sql = "INSERT INTO celular 
                    (armazenamento, ram, cor, tamanho_tela, processador, camera_traseira, camera_frontal, bateria)
                    VALUES 
                    (:armazenamento, :ram, :cor, :tamanho_tela, :processador, :camera_traseira, :camera_frontal, :bateria)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':armazenamento', $armazenamento);
            $stmt->bindParam(':ram', $ram);
            $stmt->bindParam(':cor', $cor);
            $stmt->bindParam(':tamanho_tela', $tamanhoTela);
            $stmt->bindParam(':processador', $processador);
            $stmt->bindParam(':camera_traseira', $cameraTraseira);
            $stmt->bindParam(':camera_frontal', $cameraFrontal);
            $stmt->bindParam(':bateria', $bateria);

            $stmt->execute();

            // Retorna o ID do produto inserido
            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {
            return false;
        }
    }
}
