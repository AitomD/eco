<?php
// app/controller/cupons-carrinho.php
// Controlador para gerenciar cupons no carrinho e na página de cupons

require_once __DIR__ . '/../core/Database.php';

class CuponsCarrinhoController {
    
    /**
     * Busca todos os cupons ativos e válidos
     * @return array Array de cupons disponíveis
     */
    public static function getCuponsDisponiveis() {
        try {
            $pdo = Database::conectar();
            
         
            $query = "
                SELECT id_cupom, codigo, descricao, data_inicio, data_fim, tipo_desconto, valor_desconto, uso_total, uso_user
                FROM cupons
                WHERE ativo = 1
                  AND (data_fim IS NULL OR DATE(data_fim) >= CURDATE())
                  AND (data_inicio IS NULL OR DATE(data_inicio) <= CURDATE())
                ORDER BY valor_desconto DESC
            ";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar cupons: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Valida se um cupom é válido
     * @param string $codigo Código do cupom
     * @return array|false Dados do cupom se válido, false caso contrário
     */
    public static function validarCupom($codigo) {
        try {
            $pdo = Database::conectar();
            
            $query = "
                SELECT id_cupom, codigo, descricao, tipo_desconto, valor_desconto, uso_total, uso_user, data_fim
                FROM cupons
                WHERE codigo = :codigo
                  AND ativo = 1
                  AND (data_fim IS NULL OR DATE(data_fim) >= CURDATE())
                  AND (data_inicio IS NULL OR DATE(data_inicio) <= CURDATE())
            ";
            
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':codigo', $codigo);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao validar cupom: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Aplica um cupom ao carrinho
     * @param string $codigo Código do cupom
     * @param float $valorCarrinho Valor total do carrinho
     * @return array Resultado da aplicação [sucesso, mensagem, desconto]
     */
    public static function aplicarCupom($codigo, $valorCarrinho) {
        // Iniciar sessão se não estiver ativa
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $cupom = self::validarCupom($codigo);
        
        if (!$cupom) {
            return [
                'sucesso' => false,
                'mensagem' => 'Cupom inválido ou expirado.',
                'desconto' => 0
            ];
        }
        
        // Calcular desconto
        $desconto = 0;
        if ($cupom['tipo_desconto'] === 'porcentagem') {
            $desconto = ($valorCarrinho * $cupom['valor_desconto']) / 100;
        } elseif ($cupom['tipo_desconto'] === 'valor') {
            $desconto = $cupom['valor_desconto'];
        }
        
        // Garantir que o desconto não seja maior que o valor do carrinho
        $desconto = min($desconto, $valorCarrinho);
        
        // Salvar cupom aplicado na sessão (sem o valor do desconto calculado)
        $_SESSION['cupom_aplicado'] = [
            'id_cupom' => $cupom['id_cupom'],
            'codigo' => $cupom['codigo'],
            'descricao' => $cupom['descricao'],
            'tipo_desconto' => $cupom['tipo_desconto'],
            'valor_desconto' => $cupom['valor_desconto'],
            'uso_total' => $cupom['uso_total'],
            'uso_user' => $cupom['uso_user']
        ];
        
        return [
            'sucesso' => true,
            'mensagem' => 'Cupom aplicado com sucesso!',
            'desconto' => $desconto
        ];
    }
    
    /**
     * Remove o cupom aplicado
     */
    public static function removerCupom() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['cupom_aplicado']);
    }
    
    /**
     * Obtém o cupom aplicado atualmente
     * @return array|null Dados do cupom aplicado ou null
     */
    public static function getCupomAplicado() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['cupom_aplicado'] ?? null;
    }
    
    /**
     * Calcula o valor final do carrinho com desconto
     * @param float $valorCarrinho Valor original do carrinho
     * @return array [valor_original, desconto, valor_final, cupom_aplicado]
     */
    public static function calcularValorFinal($valorCarrinho) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $cupomAplicado = self::getCupomAplicado();
        $desconto = 0;
        
        if ($cupomAplicado) {
            // Verificar se o cupom ainda é válido
            $cupomValidado = self::validarCupom($cupomAplicado['codigo']);
            
            if ($cupomValidado) {
                // Recalcular o desconto baseado no valor atual do carrinho
                if ($cupomAplicado['tipo_desconto'] === 'porcentagem') {
                    $desconto = ($valorCarrinho * $cupomAplicado['valor_desconto']) / 100;
                } elseif ($cupomAplicado['tipo_desconto'] === 'valor') {
                    $desconto = $cupomAplicado['valor_desconto'];
                }
                
                // Garantir que o desconto não seja maior que o valor do carrinho
                $desconto = min($desconto, $valorCarrinho);
                
                // Atualizar o cupom na sessão com o novo valor calculado
                $_SESSION['cupom_aplicado']['desconto_calculado'] = $desconto;
            } else {
                // Cupom não é mais válido, remover da sessão
                self::removerCupom();
                $cupomAplicado = null;
            }
        }
        
        return [
            'valor_original' => $valorCarrinho,
            'desconto' => $desconto,
            'valor_final' => max(0, $valorCarrinho - $desconto),
            'cupom_aplicado' => $cupomAplicado
        ];
    }
    
    /**
     * Processa ações relacionadas aos cupons via POST
     */
    public static function processarAcao() {
        // Verificar se há uma ação de cupom para processar
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_cupom'])) {
            
            // Iniciar sessão se não estiver ativa
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $acao = $_POST['acao_cupom'];
            $processou = false;
            
            switch ($acao) {
                case 'aplicar':
                    $codigo = trim($_POST['codigo_cupom'] ?? '');
                    $valorCarrinho = floatval($_POST['valor_carrinho'] ?? 0);
                    
                    if (!empty($codigo) && $valorCarrinho > 0) {
                        $resultado = self::aplicarCupom($codigo, $valorCarrinho);
                        $processou = true;
                    }
                    break;
                    
                case 'remover':
                    self::removerCupom();
                    $processou = true;
                    break;
            }
            
            // Só redirecionar se processou alguma ação e não estamos já no carrinho
            if ($processou && !headers_sent()) {
                $paginaAtual = $_GET['url'] ?? 'home';
                if ($paginaAtual !== 'carrinho') {
                    header('Location: index.php?url=carrinho');
                    exit;
                }
            }
        }
    }
    
    /**
     * Obtém e limpa mensagem de cupom da sessão
     * @return array|null Mensagem ou null
     */
    public static function getMensagemCupom() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $mensagem = $_SESSION['mensagem_cupom'] ?? null;
        unset($_SESSION['mensagem_cupom']);
        
        return $mensagem;
    }
    
    /**
     * Revalida e recalcula cupom quando carrinho é modificado
     * @param float $novoValorCarrinho Novo valor do carrinho
     */
    public static function atualizarCupomCarrinho($novoValorCarrinho) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $cupomAplicado = self::getCupomAplicado();
        
        if ($cupomAplicado) {
            // Recalcular desconto automaticamente
            self::calcularValorFinal($novoValorCarrinho);
        }
    }
    
    /**
     * Endpoint AJAX para aplicar cupom
     */
    public static function aplicarCupomAjax() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_cupom'])) {
            header('Content-Type: application/json');
            
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $codigo = trim($_POST['codigo_cupom'] ?? '');
            
            // Verificar se há itens no carrinho
            require_once __DIR__ . '/CarrinhoController.php';
            $totalCarrinho = CarrinhoController::calcularTotal();
            $totalItens = CarrinhoController::contarItens();
            
            if ($totalItens === 0) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Adicione produtos ao carrinho antes de aplicar um cupom!'
                ]);
                exit;
            }
            
            if (empty($codigo)) {
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Digite o código do cupom!'
                ]);
                exit;
            }
            
            $resultado = self::aplicarCupom($codigo, $totalCarrinho);
            echo json_encode($resultado);
            exit;
        }
    }

    /**
     * Registrar uso de cupom por um usuário
     * @param int $idCupom ID do cupom
     * @param int $idUser ID do usuário
     * @return bool Sucesso da operação
     */
    public static function registrarUsoCupom($idCupom, $idUser) {
        try {
            $pdo = Database::conectar();
            
            // Verificar se o cupom existe
            $stmt = $pdo->prepare("SELECT id_cupom FROM cupons WHERE id_cupom = ?");
            $stmt->execute([$idCupom]);
            
            if (!$stmt->fetch()) {
                return false;
            }
            
            // Verificar se já existe registro para este cupom e usuário
            $stmt = $pdo->prepare("SELECT id_cupom_user, usos FROM cupom_user WHERE id_cupom = ? AND id_user = ?");
            $stmt->execute([$idCupom, $idUser]);
            $registro = $stmt->fetch();
            
            if ($registro) {
                // Incrementar uso existente
                $stmt = $pdo->prepare("UPDATE cupom_user SET usos = usos + 1 WHERE id_cupom_user = ?");
                return $stmt->execute([$registro['id_cupom_user']]);
            } else {
                // Criar novo registro
                $stmt = $pdo->prepare("INSERT INTO cupom_user (id_cupom, id_user, usos) VALUES (?, ?, 1)");
                return $stmt->execute([$idCupom, $idUser]);
            }
            
        } catch (PDOException $e) {
            error_log("Erro ao registrar uso do cupom: " . $e->getMessage());
            return false;
        }
    }
}