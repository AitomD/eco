<?php
// Assumindo que este arquivo está na mesma pasta que sua classe Produto
require_once __DIR__ . '/../core/Database.php';

/**
 * Classe Model para interagir com a tabela 'endereco'.
 */
class Endereco
{
    private $pdo;

    /**
     * Conecta ao banco de dados ao instanciar a classe.
     */
    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    /**
     * Busca um endereço específico pelo seu ID.
     *
     * @param int $id_endereco O ID do endereço.
     * @return array|false Retorna um array associativo com os dados do endereço ou false se não for encontrado.
     */
    public function getById($id_endereco)
    {
        try {
            $sql = "SELECT * FROM endereco WHERE id_endereco = :id_endereco";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_endereco' => $id_endereco]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os endereços de um usuário específico.
     *
     * @param int $id_user O ID do usuário.
     * @return array Retorna um array de arrays associativos, um para cada endereço.
     */
    public function getEnderecosPorUsuario($id_user)
    {
        try {
            $sql = "SELECT * FROM endereco WHERE id_user = :id_user ORDER BY id_endereco";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_user' => $id_user]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return []; // Retorna array vazio em caso de erro
        }
    }

    /**
     * Cria um novo endereço no banco de dados.
     *
     * @param array $dados Um array associativo contendo os dados do endereço.
     * Ex: [
     * 'id_user' => 1,
     * 'endereco' => 'Rua Exemplo, 123',
     * 'cep' => '87000-000',
     * 'complemento' => 'Apto 101', // Opcional
     * 'bairro' => 'Centro',
     * 'cidade' => 'Maringá',
     * 'estado' => 'PR'
     * ]
     * @return string|false Retorna o ID do novo endereço criado ou false em caso de falha.
     */
    public function criar($dados)
    {
        // Define complemento como null se não for enviado ou estiver vazio
        $dados['complemento'] = !empty($dados['complemento']) ? $dados['complemento'] : null;

        $sql = "INSERT INTO endereco (id_user, endereco, cep, complemento, bairro, cidade, estado) 
                VALUES (:id_user, :endereco, :cep, :complemento, :bairro, :cidade, :estado)";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($dados);
            
            return $this->pdo->lastInsertId(); // Retorna o ID do registro inserido

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um endereço existente.
     *
     * @param int $id_endereco O ID do endereço a ser atualizado.
     * @param array $dados Um array associativo com os dados a serem atualizados.
     * @return bool Retorna true em sucesso ou false em caso de falha.
     */
    public function atualizar($id_endereco, $dados)
    {
        // Adiciona o id_endereco ao array de dados para o bind
        $dados['id_endereco'] = $id_endereco;
        $dados['complemento'] = !empty($dados['complemento']) ? $dados['complemento'] : null;

        $sql = "UPDATE endereco SET 
                    id_user = :id_user, 
                    endereco = :endereco, 
                    cep = :cep, 
                    complemento = :complemento, 
                    bairro = :bairro, 
                    cidade = :cidade, 
                    estado = :estado 
                WHERE id_endereco = :id_endereco";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($dados); // Retorna true ou false

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um endereço do banco de dados.
     *
     * @param int $id_endereco O ID do endereço a ser excluído.
     * @return bool Retorna true em sucesso ou false em caso de falha.
     */
    public function excluir($id_endereco)
    {
        $sql = "DELETE FROM endereco WHERE id_endereco = :id_endereco";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id_endereco' => $id_endereco]);

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // --- NOTA PARA O CHECKOUT ---
    
    /**
     * Busca o "endereço principal" de um usuário.
     * * NOTA: Sua tabela 'endereco' não possui uma coluna 'principal' (ex: principal = 1).
     * Se você adicionar essa coluna, este método será muito útil.
     * Por enquanto, ele apenas pega o *primeiro* endereço encontrado.
     *
     * @param int $id_user O ID do usuário.
     * @return array|null Retorna o primeiro endereço encontrado ou null.
     */
    public function getEnderecoPrincipal($id_user)
    {
  

        // Como não há coluna 'principal', pegamos o primeiro da lista
        $sql = "SELECT * FROM endereco WHERE id_user = :id_user LIMIT 1";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id_user' => $id_user]);
            $endereco = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $endereco ? $endereco : null;

        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }
}
?>