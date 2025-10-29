<?php
require_once __DIR__ . '/../core/Database.php';

class Dadosuser
{
    private $pdo;

    public function __construct()
    {
        // Assume-se que Database::conectar() retorna uma instância de PDO
        $this->pdo = Database::conectar();
    }

    /**
     * Busca todos os usuários da tabela 'user'.
     * @return array|false Um array de objetos/arrays representando os usuários ou false em caso de erro.
     */
    public function buscarTodosUsuarios()
    {
        $sql = "SELECT id_user, nome, email, is_admin, data_nascimento FROM user";
        try {
            $stmt = $this->pdo->query($sql);
            // Pode retornar como array associativo, objeto, etc. O FETCH_ASSOC é comum.
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Em ambiente de produção, logue o erro em vez de exibi-lo
            // echo "Erro ao buscar usuários: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca um usuário específico pelo seu ID.
     * @param int $id O ID do usuário.
     * @return array|false Um array associativo do usuário ou false se não encontrado/erro.
     */
    public function buscarUsuarioPorId($id)
    {
        $sql = "SELECT  nome, email, data_nascimento FROM user WHERE id_user = :id";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // echo "Erro ao buscar usuário: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca todos os endereços de um usuário específico.
     * @param int $id_user O ID do usuário.
     * @return array|false Um array de objetos/arrays representando os endereços ou false em caso de erro.
     */
    public function buscarEnderecosPorUsuario($id_user)
    {
        $sql = "SELECT id_endereco, id_user, endereco, cep, complemento, bairro, cidade, estado 
                FROM endereco 
                WHERE id_user = :id_user";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // echo "Erro ao buscar endereços: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Busca um usuário e todos os seus endereços (usando um JOIN opcionalmente ou chamando os métodos acima).
     * Esta é uma forma mais direta de obter os dados combinados em uma única query (pode retornar várias linhas por usuário).
     * @param int $id_user O ID do usuário.
     * @return array|false Um array de arrays associativos com dados combinados ou false em caso de erro.
     */
    public function buscarUsuarioEEnderecos($id_user)
    {
        // No seu arquivo Dadosuser.php
        $sql = "SELECT 
                u.id_user AS id_user,
                u.nome AS nome,        /* AGORA CHAMA-SE APENAS 'nome' */
                u.email AS email,      /* AGORA CHAMA-SE APENAS 'email' */
                u.is_admin AS is_admin,
                u.data_nascimento AS data_nascimento, /* AGORA CHAMA-SE APENAS 'data_nascimento' */
                
                e.id_endereco AS id_endereco,
                e.endereco AS endereco,
                e.cep AS cep,
                e.complemento AS complemento,
                e.bairro AS bairro,
                e.cidade AS cidade,
                e.estado AS estado
            FROM 
                user u
            LEFT JOIN 
                endereco e ON u.id_user = e.id_user
            WHERE 
                u.id_user = :id_user";
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // ...
            return false;
        }
    }
  
}

?>