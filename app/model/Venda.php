<?php
require_once __DIR__ . '/../core/Database.php';

class Venda
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function buscarVendasPorAdmin($idAdmin)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.id_pedido, u.nome AS nome_cliente, p.data_pedido, p.status,
                       p.total, p.desconto, p.total_final
                FROM pedido p
                INNER JOIN loja l ON l.id_loja = p.id_loja
                INNER JOIN user u ON u.id_user = p.id_user
                WHERE l.id_admin = ?
                ORDER BY p.data_pedido DESC
            ");
            $stmt->execute([$idAdmin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar vendas: " . $e->getMessage());
            return [];
        }
    }

    public function atualizarStatus($idPedido, $idAdmin, $novoStatus)
    {
        $statusValidos = ['pendente', 'confirmado', 'enviado', 'entregue'];
        if (!in_array($novoStatus, $statusValidos)) {
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            // Atualiza o status atual do pedido
            $stmt = $this->pdo->prepare("
                UPDATE pedido 
                SET status = ? 
                WHERE id_pedido = ?
                AND id_loja = (SELECT id_loja FROM loja WHERE id_admin = ?)
            ");
            $stmt->execute([$novoStatus, $idPedido, $idAdmin]);

            // Insere o histórico de mudança
            $stmtHist = $this->pdo->prepare("
                INSERT INTO pedido_status (id_pedido, id_admin, status, data_alteracao)
                VALUES (?, ?, ?, NOW())
            ");
            $stmtHist->execute([$idPedido, $idAdmin, $novoStatus]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Erro ao atualizar status: " . $e->getMessage());
            return false;
        }
    }
}
