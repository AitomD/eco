<?php

require_once __DIR__ . '/../core/Database.php';
/**
 * Classe modelo para representar e buscar dados da Loja.
 */
class Loja
{
    /**
     * @var PDO A instância de conexão com o banco de dados.
     */
    private $pdo;

    /**
     * Construtor da classe.
     *
     * @param PDO $conexao Uma instância de conexão PDO ativa.
     */
    public function __construct(PDO $conexao)
    {
        $this->pdo = $conexao;
    }

    /**
     * Busca o nome da loja e sua localização (cidade) com base no ID da loja.
     *
     * @param int $id_loja O ID da loja que você quer buscar.
     * @return array|false Retorna um array associativo com 'nome_loja' e 'cidade'
     * ou 'false' se a loja não for encontrada.
     */
    public function buscarInfoVendedor($id_loja)
    {
        // Query SQL que junta 'loja' (l) e 'endereco' (e)
        // e seleciona APENAS os campos necessários para o seu HTML.
        $sql = "SELECT
                    l.nome AS nome_loja,
                    e.cidade
                FROM
                    loja AS l
                JOIN
                    endereco AS e ON l.id_endereco = e.id_endereco
                WHERE
                    l.id_loja = :id_loja";

        try {
            // Prepara e executa a query de forma segura
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_loja', $id_loja, PDO::PARAM_INT);
            $stmt->execute();

            // Retorna a linha de resultado
            return $stmt->fetch();

        } catch (PDOException $e) {
            // Em um aplicativo real, você deve logar este erro
            error_log("Erro ao buscar informações do vendedor: " . $e->getMessage());
            return false;
        }
    }
}