<?php
require_once __DIR__ . '/../core/Database.php';

class Estoque {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::conectar();
    }
    
    public function getResumoUsuario(int $id_user) {
        // Última entrada
        $stmt = $this->pdo->prepare("
            SELECT id_estoque, id_produto, quantidade, total, data_estoque
            FROM estoque
            WHERE id_user = :id_user AND tipo = 'entrada'
            ORDER BY data_estoque DESC, id_estoque DESC
            LIMIT 1
        ");
        $stmt->execute(['id_user' => $id_user]);
        $ultimaEntrada = $stmt->fetch(PDO::FETCH_ASSOC);

        // Última saída
        $stmt = $this->pdo->prepare("
            SELECT id_estoque, id_produto, quantidade, total, data_estoque
            FROM estoque
            WHERE id_user = :id_user AND tipo = 'saida'
            ORDER BY data_estoque DESC, id_estoque DESC
            LIMIT 1
        ");
        $stmt->execute(['id_user' => $id_user]);
        $ultimaSaida = $stmt->fetch(PDO::FETCH_ASSOC);

        // Estoque total
        $stmt = $this->pdo->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE 0 END),0) -
                COALESCE(SUM(CASE WHEN tipo = 'saida' THEN quantidade ELSE 0 END),0) AS estoque_total
            FROM estoque
            WHERE id_user = :id_user
        ");
        $stmt->execute(['id_user' => $id_user]);
        $estoqueTotal = $stmt->fetchColumn();

        return [
            'ultima_entrada' => $ultimaEntrada ?: null,
            'ultima_saida' => $ultimaSaida ?: null,
            'estoque_total' => (int)$estoqueTotal
        ];
    }
}
?>
