<?php
require_once __DIR__ . '/../core/Database.php';

class Loja
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    // Filtra por admin
    public function buscarPorAdminId($idAdmin)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id_loja, id_endereco, id_admin, nome, cnpj 
                FROM loja 
                WHERE id_admin = ?
                LIMIT 1
            ");
            $stmt->execute([$idAdmin]);
            $loja = $stmt->fetch(PDO::FETCH_ASSOC);

            return $loja ?: null;
        } catch (PDOException $e) {
            error_log("Erro ao buscar loja: " . $e->getMessage());
            return null;
        }
    }

}
?>
