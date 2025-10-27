<?php
require_once __DIR__ . '/../core/Database.php';

class DonoLoja {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::conectar();
    }

    // Busca o nome do proprietário pelo id_admin
    public function buscarDonoPorIdAdmin($idAdmin) {
        $sql = "SELECT u.nome AS nome_dono
                FROM admin a
                JOIN user u ON a.id_user = u.id_user
                WHERE a.id_admin = :id_admin";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_admin', $idAdmin, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // retorna array com 'nome_dono'
    }

    // Novo método: busca dono pelo id_user logado
    public function buscarDonoPorIdUser($idUser) {
        $sql = "SELECT u.nome AS nome_dono, a.id_admin
                FROM admin a
                JOIN user u ON a.id_user = u.id_user
                WHERE a.id_user = :id_user";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // retorna array com 'nome_dono' e 'id_admin'
    }
}
?>
