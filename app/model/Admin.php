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
        $sql = "SELECT id_admin,cargo FROM admin WHERE id_user = :id_user LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
        $stmt->execute();

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            return $admin['id_admin'];
        }
        

        return null;
    }


    public function isAdminDesenvolvedor($idUser)
    {
        if (empty($idUser)) {
            return false;
        }

        try {
            $sql = "SELECT id_admin, cargo FROM admin WHERE id_user = :id_user LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($admin !== false && !empty($admin['cargo']) && strtolower(trim($admin['cargo'])) === strtolower('Desenvolvedor'));
        } catch (Exception $e) {
            error_log("Erro ao verificar cargo desenvolvedor: " . $e->getMessage());
            return false;
        }
    }
}

