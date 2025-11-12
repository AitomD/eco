<?php
require_once __DIR__ . '/../core/Database.php';

class Cupom
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    /**
     * Buscar cupom por ID
     * @param int $idCupom
     * @return array|false
     */
    public function buscarPorId($idCupom)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    id_cupom,
                    codigo,
                    descricao,
                    tipo_desconto,
                    valor_desconto,
                    ativo,
                    data_inicio,
                    data_fim
                FROM cupons
                WHERE id_cupom = ?
            ");
            
            $stmt->execute([$idCupom]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar cupom: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar cupom por código
     * @param string $codigo
     * @return array|false
     */
    public function buscarPorCodigo($codigo)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    id_cupom,
                    codigo,
                    descricao,
                    tipo_desconto,
                    valor_desconto,
                    ativo,
                    data_inicio,
                    data_fim
                FROM cupons
                WHERE codigo = ?
            ");
            
            $stmt->execute([$codigo]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro ao buscar cupom por código: " . $e->getMessage());
            return false;
        }
    }
}
