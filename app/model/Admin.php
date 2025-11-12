<?php
require_once __DIR__ . '/../core/Database.php';

class Admin
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function getIdAdminByUser($idUser)
    {
        $sql = "SELECT id_admin FROM admin WHERE id_user = :id_user LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            return $admin['id_admin'];
        }

        return null;
    }
}

