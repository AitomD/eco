<?php
require_once __DIR__ . '/../core/Database.php';

class NovoProduto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

public function inserir($nome, $preco, $id_loja, $id_celular = null, $id_info = null)
{
    $sql = "INSERT INTO produto (nome, preco, id_loja, id_celular, id_info)
            VALUES (:nome, :preco, :id_loja, :id_celular, :id_info)";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':nome'    => $nome,
        ':preco'   => $preco,
        ':id_loja' => $id_loja,
        ':id_celular' => $id_celular, // Vai salvar NULL se não for celular
        ':id_info'    => $id_info     // Vai salvar NULL se não for PC
    ]);

    return $this->pdo->lastInsertId();
}
}