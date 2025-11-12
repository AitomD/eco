<?php
// app/controller/PedidoController.php
// Controlador para gerenciar operações de pedidos

// Iniciar sessão apenas se não estiver ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../controller/CarrinhoController.php';
require_once __DIR__ . '/../controller/cupons-carrinho.php';

class PedidoController {
    
    /**
     * Finalizar compra do carrinho
     * @param array $dadosCompra Dados da compra (forma de pagamento, etc.)
     * @return array Resultado da operação
     */
    public static function finalizarCompra($dadosCompra = []) {
        try {
            // Verificar se usuário está logado
            if (empty($_SESSION['user_id'])) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Usuário não está logado.',
                    'erro' => 'usuario_nao_logado'
                ];
            }

            // Obter dados do carrinho
            $itensCarrinho = CarrinhoController::getItens();
            if (empty($itensCarrinho)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Carrinho está vazio.',
                    'erro' => 'carrinho_vazio'
                ];
            }

            $totalCarrinho = CarrinhoController::calcularTotal();
            $cupomAplicado = CuponsCarrinhoController::getCupomAplicado();
            $valoresCarrinho = CuponsCarrinhoController::calcularValorFinal($totalCarrinho);

            // Instanciar modelo de pedido
            $pedidoModel = new Pedido();

            // Validar produtos do carrinho
            $produtosValidados = $pedidoModel->validarProdutosCarrinho($itensCarrinho);
            if (empty($produtosValidados)) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Não foi possível validar os produtos do carrinho.',
                    'erro' => 'produtos_invalidos'
                ];
            }

            // Determinar loja do pedido (baseado nos produtos)
            $idLoja = $pedidoModel->determinarLojaPedido($itensCarrinho);
            if (!$idLoja) {
                // Se não conseguir determinar a loja, usar a primeira loja encontrada
                $idLoja = $produtosValidados[0]['id_loja'] ?? 1;
            }

            // Aplicar desconto de forma de pagamento se especificado
            $valorFinal = $valoresCarrinho['valor_final'];
            $descontoAdicional = 0;
            
            if (isset($dadosCompra['forma_pagamento'])) {
                $descontos = [
                    'pix' => 0.05,      // 5%
                    'credito' => 0,     // 0%
                    'debito' => 0.03,   // 3%
                    'boleto' => 0.02    // 2%
                ];
                
                $formaPagamento = $dadosCompra['forma_pagamento'];
                if (isset($descontos[$formaPagamento])) {
                    $descontoAdicional = $valorFinal * $descontos[$formaPagamento];
                    $valorFinal = $valorFinal - $descontoAdicional;
                }
            }

            // Preparar dados do pedido
            $dadosPedido = [
                'id_user' => $_SESSION['user_id'],
                'id_loja' => $idLoja,
                'id_cupom' => ($cupomAplicado && isset($cupomAplicado['id_cupom'])) ? $cupomAplicado['id_cupom'] : null,
                'total' => $totalCarrinho,
                'desconto' => ($valoresCarrinho['desconto'] ?? 0) + $descontoAdicional, 
                'total_final' => $valorFinal,
                'produtos' => $produtosValidados
            ];

            // Criar o pedido
            $idPedido = $pedidoModel->criarPedido($dadosPedido);

            if ($idPedido) {
                // Limpar carrinho após pedido criado com sucesso
                CarrinhoController::limparCarrinho();
                
                // Registrar uso do cupom se aplicado
                if ($cupomAplicado && isset($cupomAplicado['id_cupom'])) {
                    CuponsCarrinhoController::registrarUsoCupom($cupomAplicado['id_cupom'], $_SESSION['user_id']);
                }

                return [
                    'sucesso' => true,
                    'mensagem' => 'Pedido realizado com sucesso!',
                    'id_pedido' => $idPedido,
                    'dados' => [
                        'total' => $totalCarrinho,
                        'desconto' => ($valoresCarrinho['desconto'] ?? 0) + $descontoAdicional,
                        'total_final' => $valorFinal,
                        'forma_pagamento' => $dadosCompra['forma_pagamento'] ?? 'não informado',
                        'produtos' => $produtosValidados
                    ]
                ];
            } else {
  
                return [
                    'sucesso' => false,
                    'mensagem' => 'Falha ao criar pedido (retorno "false" do Model). Verifique Pedido.php.',
                    'erro' => 'erro_banco_dados_model'
                ];
            }

        } catch (Exception $e) {
 
            error_log("Erro ao finalizar compra: " . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro: ' . $e->getMessage(),
                'erro' => 'erro_interno'
            ];
        }
    }

    /**
     * Buscar detalhes de um pedido
     * @param int $idPedido
     * @param int $idUser (opcional, para validar se o pedido pertence ao usuário)
     * @return array|false
     */
    public static function buscarDetalhesPedido($idPedido, $idUser = null) {
        try {
            $pedidoModel = new Pedido();
            
            $pedido = $pedidoModel->buscarPorId($idPedido);
            if (!$pedido) {
                return false;
            }

            // Validar se o pedido pertence ao usuário (se especificado)
            if ($idUser && $pedido['id_user'] != $idUser) {
                return false;
            }

            $produtos = $pedidoModel->buscarProdutosPedido($idPedido);

            // Adiciona o nome da loja principal ao pedido se não houver um específico
            if ($pedido && empty($pedido['nome_loja']) && !empty($produtos)) {
                 $pedido['nome_loja'] = $produtos[0]['nome_loja'] ?? 'Loja Padrão';
            }

            return [
                'pedido' => $pedido,
                'produtos' => $produtos
            ];

        } catch (Exception $e) {
            error_log("Erro ao buscar detalhes do pedido: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exibir página de sucesso do pedido
     * @param int $idPedido
     */
    public static function sucesso($idPedido) {
        try {
            // Verificar se usuário está logado
            if (empty($_SESSION['user_id'])) {
                header('Location: index.php?url=login');
                exit;
            }

            // Instanciar modelo de pedido
            $pedidoModel = new Pedido();
            
            // Buscar dados do pedido
            $pedido = $pedidoModel->buscarPorId($idPedido);
            
            // Verificar se pedido existe
            if (!$pedido) {
                $_SESSION['mensagem_erro'] = 'Pedido não encontrado.';
                header('Location: index.php?url=produto');
                exit;
            }
            
            // Verificar se o pedido pertence ao usuário logado
            if ($pedido['id_user'] != $_SESSION['user_id']) {
                $_SESSION['mensagem_erro'] = 'Você não tem permissão para visualizar este pedido.';
                header('Location: index.php?url=produto');
                exit;
            }
            
            // Buscar produtos do pedido
            $produtos = $pedidoModel->buscarProdutosPedido($idPedido);
            
            // Verificar se há produtos
            if (empty($produtos)) {
                $_SESSION['mensagem_erro'] = 'Não foram encontrados produtos neste pedido.';
                header('Location: index.php?url=produto');
                exit;
            }
            
            // Buscar informações do cupom se houver
            if (!empty($pedido['id_cupom'])) {
                require_once __DIR__ . '/../model/Cupom.php';
                $cupomModel = new Cupom();
                $cupom = $cupomModel->buscarPorId($pedido['id_cupom']);
                if ($cupom) {
                    $pedido['codigo_cupom'] = $cupom['codigo'];
                }
            }
            
            // Passar variáveis para a view
            require_once __DIR__ . '/../views/pedido-sucesso.php';
            
        } catch (Exception $e) {
            error_log("Erro ao exibir página de sucesso: " . $e->getMessage());
            $_SESSION['mensagem_erro'] = 'Erro ao carregar informações do pedido.';
            header('Location: index.php?url=produto');
            exit;
        }
    }

    /**
     * Buscar pedidos de um usuário
     * @param int $idUser
     * @return array
     */
    public static function buscarPedidosUsuario($idUser) {
        try {
            $pedidoModel = new Pedido();
            return $pedidoModel->buscarPorUsuario($idUser);

        } catch (Exception $e) {
            error_log("Erro ao buscar pedidos do usuário: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Processar dados da requisição de finalização de compra
     * @return array
     */
    public static function processarFinalizacaoCompra() {
        $dadosCompra = [];

        // Capturar forma de pagamento
        if (isset($_POST['payment-option'])) {
            $dadosCompra['forma_pagamento'] = $_POST['payment-option'];
        }

        // Capturar dados do cartão (se for cartão de crédito)
        if (isset($_POST['payment-option']) && $_POST['payment-option'] === 'credito') {
            $dadosCompra['cartao'] = [
                'numero' => $_POST['card-number'] ?? '',
                'nome' => $_POST['card-name'] ?? '',
                'validade' => $_POST['card-expiry'] ?? '',
                'cvv' => $_POST['card-cvv'] ?? '',
                'parcelas' => $_POST['installments'] ?? 1
            ];
        }

        return self::finalizarCompra($dadosCompra);
    }

    /**
     * Gerar resumo da compra para confirmação
     * @return array|false
     */
    public static function gerarResumoCompra() {
        try {
            if (!isset($_SESSION['user_id'])) {
                return false;
            }

            $itensCarrinho = CarrinhoController::getItens();
            if (empty($itensCarrinho)) {
                return false;
            }

            $totalCarrinho = CarrinhoController::calcularTotal();
            $cupomAplicado = CuponsCarrinhoController::getCupomAplicado();
            $valoresCarrinho = CuponsCarrinhoController::calcularValorFinal($totalCarrinho);

            $pedidoModel = new Pedido();
            $produtosValidados = $pedidoModel->validarProdutosCarrinho($itensCarrinho);

            // Agrupar produtos por loja
            $produtosPorLoja = [];
            foreach ($produtosValidados as $produto) {
                $idLoja = $produto['id_loja'];
                if (!isset($produtosPorLoja[$idLoja])) {
                    $produtosPorLoja[$idLoja] = [
                        'nome_loja' => $produto['nome_loja'],
                        'produtos' => [],
                        'subtotal' => 0
                    ];
                }
                $produtosPorLoja[$idLoja]['produtos'][] = $produto;
                $produtosPorLoja[$idLoja]['subtotal'] += $produto['subtotal'];
            }

            return [
                'itens' => $itensCarrinho,
                'produtos_validados' => $produtosValidados,
                'produtos_por_loja' => $produtosPorLoja,
                'total' => $totalCarrinho,
                'cupom_aplicado' => $cupomAplicado,
                'valores' => $valoresCarrinho,
                'quantidade_total' => CarrinhoController::contarItens()
            ];

        } catch (Exception $e) {
            error_log("Erro ao gerar resumo da compra: " . $e->getMessage());
            return false;
        }
    }
}