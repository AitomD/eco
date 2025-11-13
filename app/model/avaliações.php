<?php
require_once __DIR__ . '/../core/Database.php';

class Avaliacao {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::conectar();
    }
    
    /**
     * Adiciona uma nova avaliação para um produto
     * @param int $id_user ID do usuário
     * @param int $id_produto ID do produto
     * @param int $nota Nota de 1 a 5
     * @param string $comentario Comentário da avaliação (opcional)
     * @return bool|int ID da avaliação criada ou false em caso de erro
     */
    public function adicionarAvaliacao($id_user, $id_produto, $nota) {
        try {
            // Verificar se o usuário já avaliou este produto
            if ($this->jaAvaliou($id_user, $id_produto)) {
                return false; // Usuário já avaliou este produto
            }
            
            // Validar nota (deve estar entre 1 e 5)
            if ($nota < 1 || $nota > 5) {
                return false;
            }
            
            $sql = "INSERT INTO avaliacao (id_user, id_produto, nota, data_avaliacao) 
                    VALUES (:id_user, :id_produto, :nota, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
            $stmt->bindParam(':nota', $nota, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return $this->pdo->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erro ao adicionar avaliação: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verifica se um usuário já avaliou um produto específico
     * @param int $id_user ID do usuário
     * @param int $id_produto ID do produto
     * @return bool
     */
    public function jaAvaliou($id_user, $id_produto) {
        try {
            $sql = "SELECT COUNT(*) FROM avaliacao WHERE id_user = :id_user AND id_produto = :id_produto";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar avaliação: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca todas as avaliações de um produto específico
     * @param int $id_produto ID do produto
     * @param int $limite Limite de resultados (opcional)
     * @param int $offset Offset para paginação (opcional)
     * @return array|false
     */
    public function obterAvaliacoesProduto($id_produto, $limite = null, $offset = 0) {
        try {
            $sql = "SELECT 
                        a.id_avaliacao,
                        a.nota,
                        a.data_avaliacao,
                        u.nome AS nome_usuario,
                        u.email AS email_usuario
                    FROM avaliacao a
                    JOIN user u ON a.id_user = u.id_user
                    WHERE a.id_produto = :id_produto
                    ORDER BY a.data_avaliacao DESC";
            
            if ($limite !== null) {
                $sql .= " LIMIT :limite OFFSET :offset";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
            
            if ($limite !== null) {
                $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar avaliações do produto: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Calcula a média das avaliações de um produto
     * @param int $id_produto ID do produto
     * @return array|false Array com média e total de avaliações
     */
    public function calcularMediaAvaliacoes($id_produto) {
        try {
            $sql = "SELECT 
                        COUNT(*) as total_avaliacoes,
                        AVG(nota) as media_notas,
                        SUM(CASE WHEN nota = 5 THEN 1 ELSE 0 END) as nota_5,
                        SUM(CASE WHEN nota = 4 THEN 1 ELSE 0 END) as nota_4,
                        SUM(CASE WHEN nota = 3 THEN 1 ELSE 0 END) as nota_3,
                        SUM(CASE WHEN nota = 2 THEN 1 ELSE 0 END) as nota_2,
                        SUM(CASE WHEN nota = 1 THEN 1 ELSE 0 END) as nota_1
                    FROM avaliacao 
                    WHERE id_produto = :id_produto";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && $resultado['total_avaliacoes'] > 0) {
                return [
                    'media' => round($resultado['media_notas'], 1),
                    'total' => $resultado['total_avaliacoes'],
                    'distribuicao' => [
                        5 => $resultado['nota_5'],
                        4 => $resultado['nota_4'],
                        3 => $resultado['nota_3'],
                        2 => $resultado['nota_2'],
                        1 => $resultado['nota_1']
                    ]
                ];
            }
            
            return [
                'media' => 0,
                'total' => 0,
                'distribuicao' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]
            ];
        } catch (PDOException $e) {
            error_log("Erro ao calcular média de avaliações: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Busca avaliações de um usuário específico
     * @param int $id_user ID do usuário
     * @return array|false
     */
    public function obterAvaliacoesUsuario($id_user) {
        try {
            $sql = "SELECT 
                        a.id_avaliacao,
                        a.nota,
                        a.data_avaliacao,
                        p.nome AS nome_produto,
                        p.id_produto
                    FROM avaliacao a
                    JOIN produto p ON a.id_produto = p.id_produto
                    WHERE a.id_user = :id_user
                    ORDER BY a.data_avaliacao DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar avaliações do usuário: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualiza uma avaliação existente
     * @param int $id_avaliacao ID da avaliação
     * @param int $id_user ID do usuário (para verificar se é o dono da avaliação)
     * @param int $nota Nova nota
     * @param string $comentario Novo comentário (opcional)
     * @return bool
     */
    public function atualizarAvaliacao($id_avaliacao, $id_user, $nota) {
        try {
            // Verificar se a avaliação pertence ao usuário
            $sql_verificar = "SELECT id_avaliacao FROM avaliacao WHERE id_avaliacao = :id_avaliacao AND id_user = :id_user";
            $stmt_verificar = $this->pdo->prepare($sql_verificar);
            $stmt_verificar->bindParam(':id_avaliacao', $id_avaliacao, PDO::PARAM_INT);
            $stmt_verificar->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt_verificar->execute();
            
            if ($stmt_verificar->rowCount() === 0) {
                return false; // Avaliação não encontrada ou não pertence ao usuário
            }
            
            // Validar nota
            if ($nota < 1 || $nota > 5) {
                return false;
            }
            
            $sql = "UPDATE avaliacao 
                    SET nota = :nota, data_avaliacao = NOW() 
                    WHERE id_avaliacao = :id_avaliacao AND id_user = :id_user";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nota', $nota, PDO::PARAM_INT);
            $stmt->bindParam(':id_avaliacao', $id_avaliacao, PDO::PARAM_INT);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar avaliação: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Remove uma avaliação
     * @param int $id_avaliacao ID da avaliação
     * @param int $id_user ID do usuário (para verificar se é o dono da avaliação)
     * @return bool
     */
    public function removerAvaliacao($id_avaliacao, $id_user) {
        try {
            $sql = "DELETE FROM avaliacao WHERE id_avaliacao = :id_avaliacao AND id_user = :id_user";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_avaliacao', $id_avaliacao, PDO::PARAM_INT);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            
            return $stmt->execute() && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao remover avaliação: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Gera HTML para exibir estrelas com base na nota
     * @param float $nota Nota da avaliação
     * @param string $classe_css Classe CSS para as estrelas (opcional)
     * @return string HTML das estrelas
     */
    public static function gerarEstrelas($nota, $classe_css = '') {
        $html = '<div class="estrelas ' . $classe_css . '">';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($nota)) {
                $html .= '<i class="bi bi-star-fill"></i>';
            } elseif ($i - 0.5 <= $nota) {
                $html .= '<i class="bi bi-star-half"></i>';
            } else {
                $html .= '<i class="bi bi-star"></i>';
            }
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Busca os produtos mais bem avaliados
     * @param int $limite Número máximo de produtos a retornar
     * @return array|false
     */
    public function obterProdutosMaisAvaliados($limite = 10) {
        try {
            $sql = "SELECT 
                        p.id_produto,
                        p.nome,
                        p.preco,
                        COUNT(a.id_avaliacao) as total_avaliacoes,
                        AVG(a.nota) as media_notas
                    FROM produto p
                    JOIN avaliacao a ON p.id_produto = a.id_produto
                    GROUP BY p.id_produto, p.nome, p.preco
                    HAVING total_avaliacoes >= 3
                    ORDER BY media_notas DESC, total_avaliacoes DESC
                    LIMIT :limite";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar produtos mais bem avaliados: " . $e->getMessage());
            return false;
        }
    }
}
?>
