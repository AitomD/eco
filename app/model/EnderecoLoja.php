<?php
require_once __DIR__ . '/../core/Database.php';

class EnderecoLoja {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::conectar();
    }

    // Método para buscar endereço pelo ID
    public function buscarEnderecoPorId($idEndereco) {
        $sql = "SELECT endereco, cidade, estado 
                FROM endereco 
                WHERE id_endereco = :id_endereco";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_endereco', $idEndereco, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // retorna array associativo
    }
}
?>
