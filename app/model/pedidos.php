<?php
require_once __DIR__ . '/../core/Database.php';

class Pedidos
{
    private $pdo;

      public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function buscarPorUsuario(int $id_user): array
    {
        // SQL para selecionar todos os campos da tabela 'pedido'
        $sql = "SELECT 
                    id_pedido,
                    id_user,
                    id_loja,
                    id_cupom,
                    data_pedido,
                    status,
                    total,
                    desconto,
                    total_final
                FROM 
                    pedido  -- Confirme se o nome da tabela é 'pedido'
                WHERE 
                    id_user = :id_user
                ORDER BY 
                    data_pedido DESC"; // Ordena pelos mais recentes primeiro

        try {
            $stmt = $this->pdo->prepare($sql);
            
            // Associa o parâmetro :id_user com a variável $id_user
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Retorna todos os pedidos encontrados como um array associativo
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Em uma aplicação real, seria ideal logar este erro
            // error_log($e->getMessage());
            return []; // Retorna um array vazio em caso de falha
        }
    }

    public function buscarPorId(int $id_pedido)
    {
        $sql = "SELECT 
                    id_pedido,
                    id_user,
                    id_loja,
                    id_cupom,
                    data_pedido,
                    status,
                    total,
                    desconto,
                    total_final
                FROM 
                    pedido
                WHERE 
                    id_pedido = :id_pedido";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            
            // Retorna apenas um resultado
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // error_log($e->getMessage());
            return false;
        }
    }
}










?>