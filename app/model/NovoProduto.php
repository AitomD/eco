<?php
require_once __DIR__ . '/../core/Database.php';

class NovoProduto
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function cadastrarProduto($dados)
    {
        try {
            $this->pdo->beginTransaction();

            // 1️⃣ Inserir na tabela produto_info
            $sqlInfo = "INSERT INTO produto_info 
                (descricao, id_marca, id_categoria, processador, ram, armazenamento, placa_video, placa_mae, fonte)
                VALUES (:descricao, :id_marca, :id_categoria, :processador, :ram, :armazenamento, :placa_video, :placa_mae, :fonte)";
            $stmtInfo = $this->pdo->prepare($sqlInfo);
            $stmtInfo->execute([
                ':descricao' => $dados['descricao'] ?? '',
                ':id_marca' => $dados['marca'],
                ':id_categoria' => $dados['categoria'],
                ':processador' => $dados['processador'] ?? '',
                ':ram' => $dados['memoria'] ?? '',
                ':armazenamento' => $dados['armazenamento'] ?? '',
                ':placa_video' => $dados['placa_video'] ?? '',
                ':placa_mae' => $dados['placa_mae'] ?? '',
                ':fonte' => $dados['fonte'] ?? ''
            ]);
            $idInfo = $this->pdo->lastInsertId();

            // 2️⃣ Inserir na tabela produto
            $sqlProduto = "INSERT INTO produto (nome, cor, preco, id_info, id_loja)
                           VALUES (:nome, :cor, :preco, :id_info, :id_loja)";
            $stmtProduto = $this->pdo->prepare($sqlProduto);
            $stmtProduto->execute([
                ':nome' => $dados['nome'],
                ':cor' => $dados['cor'],
                ':preco' => $dados['preco'],
                ':id_info' => $idInfo,
                ':id_loja' => $dados['id_loja']
            ]);

            // 3️⃣ Inserir imagens na tabela imagem (até 3)
            if (!empty($dados['url_imagem']) && is_array($dados['url_imagem'])) {
                $sqlImagem = "INSERT INTO imagem (id_info, url, ordem) VALUES (:id_info, :url, :ordem)";
                $stmtImagem = $this->pdo->prepare($sqlImagem);

                $ordem = 1;
                foreach ($dados['url_imagem'] as $url) {
                    $url = trim($url);
                    if (!empty($url) && $ordem <= 3) {
                        $stmtImagem->execute([
                            ':id_info' => $idInfo,
                            ':url' => $url,
                            ':ordem' => $ordem
                        ]);
                        $ordem++;
                    }
                }
            }

            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Erro ao cadastrar produto: ' . $e->getMessage());
            return false;
        }
    }
}
?>
