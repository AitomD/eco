<?php
require_once __DIR__ . '/../core/Database.php';

class Loja {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::conectar();
    }

    // Busca os dados básicos da loja pelo ID da loja
    public function buscarLojaPorId($idLoja) {
        $sql = "SELECT id_loja, nome, cnpj, id_endereco, id_admin
                FROM loja
                WHERE id_loja = :idLoja";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idLoja', $idLoja, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // retorna array com dados da loja
    }

    public function buscarLojaPorAdmin($idAdmin) {
        $sql = "SELECT id_loja, nome, cnpj, id_endereco, id_admin
                FROM loja
                WHERE id_admin = :id_admin";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_admin', $idAdmin, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC); // retorna array com dados da loja
    }

    // Opcional: listar todas as lojas
    public function listarLojas() {
        $sql = "SELECT id_loja, nome, cnpj, id_endereco, id_admin FROM loja";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
