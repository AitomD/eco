<?php
require_once __DIR__ . '/../core/Database.php';

class NovoProduto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

}

