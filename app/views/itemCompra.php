<?php
require_once '../app/core/helpers.php';
require_once '../app/core/Database.php';
require_once '../app/model/avaliações.php';
require_once '../app/model/Loja.php'; // Classe Loja
// Iniciar sessão de forma segura
iniciarSessaoSegura();
// <-- MODIFICAÇÃO: Inicia o buffer de saída
// Isso armazena todo o HTML em memória e só o envia no final.
// Isso permite que a função header() funcione mesmo estando no meio do arquivo.
ob_start();

// 1️⃣ Obter o ID do produto da URL e validar
$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_produto) {
    die("Produto não encontrado ou ID inválido.");
}

try {
    $pdo = Database::conectar();

    // 🔍 Consulta principal do produto
    $sql = "
        SELECT 
            p.id_produto,
            p.nome,
            p.preco,
            pi.descricao,
            pi.ram,
            pi.cor,
            pi.armazenamento,
            pi.processador,
            pi.placa_mae,
            pi.placa_video,
            pi.fonte,
            m.nome AS marca,
            c.nome AS categoria,

            -- Imagem principal (primeira imagem da ordem)
            (SELECT i.url 
             FROM imagem i 
             WHERE i.id_info = pi.id_info 
             ORDER BY i.ordem ASC 
             LIMIT 1) AS imagem_principal,

            -- Todas as imagens relacionadas
            i.url AS imagem,

            -- Quantidade disponível (último total do estoque)
            (SELECT 
                CASE 
                    WHEN MAX(e.total) IS NULL THEN 'Sem Estoque'
                    ELSE MAX(e.total)
                END
             FROM estoque e
             WHERE e.id_produto = p.id_produto
            ) AS quantidade_disponivel

        FROM produto p
        LEFT JOIN produto_info pi ON p.id_info = pi.id_info
        LEFT JOIN imagem i ON pi.id_info = i.id_info
        LEFT JOIN marca m ON pi.id_marca = m.id_marca
        LEFT JOIN categoria c ON pi.id_categoria = c.id_categoria
        WHERE p.id_produto = :id_produto
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
    $stmt->execute();

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$resultados) {
        die("Produto não encontrado.");
    }

    $produto = $resultados[0];

    $imagens = array_column($resultados, 'imagem');

    // Buscar avaliações do produto
    $avaliacaoObj = new Avaliacao();
    $mediaAvaliacoes = $avaliacaoObj->calcularMediaAvaliacoes($id_produto);
    $avaliacoesProduto = $avaliacaoObj->obterAvaliacoesProduto($id_produto, 5); // Buscar até 5 avaliações

    $lojaModel = new Loja();
    $loja_endereco = $lojaModel->buscarPorProdutoId($id_produto);

    $condicao = false;

    if ($condicao) {
        // Agora isso funciona, pois o buffer está segurando o HTML
        header('Location: paginaRetirada.php');
        exit; // <-- IMPORTANTE: Sempre use exit/die após um redirecionamento.
    }
} catch (PDOException $e) {
    die("Erro ao buscar o produto: " . $e->getMessage());
}

?>

<style>
    li {
        color: var(--black);
    }
</style>
<main class="container py-4">
    <section class="row g-4 bg-white mt-2 py-4">

        <!-- GALERIA DE IMAGENS -->
        <div class="col-md-4">
            <div class="galeria-imagens bg-white border rounded p-3 h-100">
                <div class="miniaturas mb-3 d-flex gap-2 flex-wrap">
                    <?php foreach ($imagens as $index => $img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>"
                            class="img-thumbnail miniatura-img <?= $index === 0 ? 'active' : '' ?>"
                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                            onclick="trocarImagemPrincipal('<?= htmlspecialchars($img) ?>', this)">
                    <?php endforeach; ?>
                </div>

                <div class="imagem-principal text-center">
                    <img id="imagem-principal" src="<?= htmlspecialchars($produto['imagem_principal']) ?>"
                        alt="<?= htmlspecialchars($produto['nome']) ?>" class="img-fluid rounded">
                </div>
            </div>
        </div>

        <!-- INFORMAÇÕES DO PRODUTO -->
        <div class="col-md-5">
            <div class="info-produto bg-white border rounded p-4 h-100">
                <h1 class="h3 fw-bold" style="color:var(--black);"><?= htmlspecialchars($produto['nome']) ?></h1>
                <p class="text-muted small">Marca: <?= htmlspecialchars($produto['marca']) ?> | Categoria:
                    <?= htmlspecialchars($produto['categoria']) ?>
                </p>

                <div class="avaliacoes d-flex align-items-center small text-muted my-3">
                    <?php if ($mediaAvaliacoes && $mediaAvaliacoes['total'] > 0): ?>
                        <?= Avaliacao::gerarEstrelas($mediaAvaliacoes['media'], 'text-warning') ?>
                        <span class="ms-2">
                            <?= $mediaAvaliacoes['media'] ?>/5 (<?= $mediaAvaliacoes['total'] ?> avaliações)
                        </span>
                    <?php else: ?>
                        <span class="text-muted">Ainda não há avaliações</span>
                    <?php endif; ?>
                </div>

                <div class="preco-produto my-4">
                    <h2 class="display-5 mb-1" style="color:var(--black);">
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </h2>
                    <p class="text-primary">
                        em 10x R$ <?= number_format($produto['preco'] / 10, 2, ',', '.') ?> sem juros
                    </p>
                </div>

                <div class="sobre-o-produto border-top pt-3">
                    <h5 class="fw-bold">O que você precisa saber sobre este produto</h5>
                    <ul class="list-unstyled mt-2 text-muted">
                        <?php if (!empty($produto['cor'])): ?>
                            <li>• Cor: <?= htmlspecialchars($produto['cor']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['armazenamento'])): ?>
                            <li>• Armazenamento: <?= htmlspecialchars($produto['armazenamento']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['processador'])): ?>
                            <li>• Processador: <?= htmlspecialchars($produto['processador']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['ram'])): ?>
                            <li>• Memória RAM: <?= htmlspecialchars($produto['ram']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['placa_video'])): ?>
                            <li>• Placa de Vídeo: <?= htmlspecialchars($produto['placa_video']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['placa_mae'])): ?>
                            <li>• Placa Mãe: <?= htmlspecialchars($produto['placa_mae']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['fonte'])): ?>
                            <li>• Fonte: <?= htmlspecialchars($produto['fonte']) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        </div>


        <!-- COMPRA E VENDEDOR -->
        <div class="col-md-3">
            <div class="compra-info-vendedor d-flex flex-column gap-3">
                <div class="card-compra border rounded p-3 bg-white">
                    <p class="fw-semibold mb-2">Estoque disponível</p>
                    <p class="text-muted small">
                        Quantidade: <?= htmlspecialchars($produto['quantidade_disponivel']) ?> unidade(s)
                    </p>
                    <div class="d-grid gap-2 mt-3">

                        <?php if ($produto['quantidade_disponivel'] <= 0): ?>

                            <p class="fs-5 fw-bold text-danger">Produto fora de estoque</p>

                        <?php else: ?>

                            <button class="btn btn-product btn-add-cart btn-sm fs-6 fw-bold w-100"
                                data-id="<?= htmlspecialchars($produto['id_produto']) ?>"
                                data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                                data-preco="<?= htmlspecialchars($produto['preco']) ?>"
                                data-imagem="<?= htmlspecialchars($produto['imagem_principal']) ?>">
                                Comprar Agora
                            </button>

                        <?php endif; ?>
                    </div>
                    <p class="small text-muted mt-3">
                        Compra Garantida — receba o produto que está esperando ou devolvemos o dinheiro.
                    </p>
                </div>
                <div class="card-vendedor border rounded p-3 bg-white">
                    <h3 class="h6 fw-semibold">Informações sobre o vendedor</h3>
                    <?php if ($loja_endereco): ?>

                        <p class="small my-1 ">
                            <span class="fs-6" style="  color: var(--black);">Loja:
                                <?php echo htmlspecialchars($loja_endereco['nome_loja']); ?> </span>

                        </p>

                        <p class="small my-1">

                            <?php if (!empty($loja_endereco['endereco'])): ?>
                                <span class="fs-6" style="  color: var(--black);">Endereço:
                                    <?php echo htmlspecialchars($loja_endereco['endereco']); ?></span>
                            <?php endif; ?>

                            <!-- Exibe cidade e estado -->
                            <span class="fs-6" style="  color: var(--black);">
                                Localização:
                                <?php
                                if (!empty($loja_endereco['cidade']) && !empty($loja_endereco['estado'])) {
                                    echo htmlspecialchars($loja_endereco['cidade']) . ' - ' . htmlspecialchars($loja_endereco['estado']);
                                } elseif (!empty($loja_endereco['cidade'])) {
                                    echo htmlspecialchars($loja_endereco['cidade']);
                                } else {
                                    echo "Não informada";
                                }
                                ?>
                            </span>
                        </p>

                    <?php else: ?>

                        <p class="small my-1 fw-bold">Vendedor não identificado.</p>
                        <p class="small my-1">Localização: Não informada</p>

                    <?php endif; ?>


                </div>
            </div>
        </div>

        <!-- DESCRIÇÃO COMPLETA -->
        <div class="col-12">
            <div class="descricao-completa bg-white border rounded p-4 mt-2">
                <h2 class="h4">Descrição</h2>
                <p class="text-muted mt-3"><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
            </div>
        </div>

        <!-- SEÇÃO DE AVALIAÇÕES -->
        <div class="col-12">
            <div class="avaliacoes-completas bg-white border rounded p-4 mt-2">
                <h2 class="h4 mb-4">Avaliações dos Clientes</h2>
                
                <?php if ($mediaAvaliacoes && $mediaAvaliacoes['total'] > 0): ?>
                    <!-- Resumo das Avaliações -->
                    <div class="resumo-avaliacoes mb-4 p-3 bg-light rounded">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <h3 class="display-4 mb-0 fw-bold"><?= $mediaAvaliacoes['media'] ?></h3>
                                <?= Avaliacao::gerarEstrelas($mediaAvaliacoes['media'], 'text-warning fs-5') ?>
                                <p class="text-muted mt-1"><?= $mediaAvaliacoes['total'] ?> avaliações</p>
                            </div>
                            <div class="col-md-8">
                                <h5 class="mb-3">Distribuição das notas:</h5>
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <div class="row align-items-center mb-1">
                                        <div class="col-2 text-end">
                                            <?= $i ?> <i class="bi bi-star-fill text-warning"></i>
                                        </div>
                                        <div class="col-8">
                                            <div class="progress">
                                                <?php 
                                                $porcentagem = $mediaAvaliacoes['total'] > 0 
                                                    ? ($mediaAvaliacoes['distribuicao'][$i] / $mediaAvaliacoes['total']) * 100 
                                                    : 0; 
                                                ?>
                                                <div class="progress-bar" style="width: <?= $porcentagem ?>%"></div>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <?= $mediaAvaliacoes['distribuicao'][$i] ?>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Avaliações -->
                    <?php if (!empty($avaliacoesProduto)): ?>
                        <h5 class="mb-3">Avaliações dos Clientes</h5>
                        <div id="lista-avaliacoes">
                            <?php foreach ($avaliacoesProduto as $avaliacao): ?>
                                <div class="avaliacao-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong><?= htmlspecialchars($avaliacao['nome_usuario']) ?></strong>
                                            <?= Avaliacao::gerarEstrelas($avaliacao['nota'], 'text-warning small') ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= date('d/m/Y', strtotime($avaliacao['data_avaliacao'])) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if ($mediaAvaliacoes['total'] > 5): ?>
                            <div class="text-center mt-3">
                                <a href="todasAvaliacoes.php?id=<?= $id_produto ?>" class="btn btn-outline-primary btn-sm">
                                    Ver todas as <?= $mediaAvaliacoes['total'] ?> avaliações
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <!-- Sem Avaliações -->
                    <div class="text-center py-5">
                        <i class="bi bi-chat-quote display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Este produto ainda não foi avaliado</h5>
                        <p class="text-muted">Seja o primeiro a avaliar e ajude outros clientes!</p>
                    </div>
                <?php endif; ?>
                
                <!-- Formulário para Adicionar Avaliação (apenas se usuário estiver logado) -->
                <?php if (usuarioLogado()): ?>
                    <div class="adicionar-avaliacao mt-4 p-3 bg-light rounded">
                        <h5 class="mb-3">Avalie este produto</h5>
                        <form id="form-avaliacao" method="POST" action="../app/controller/AvaliacaoController.php">
                            <input type="hidden" name="id_produto" value="<?= $id_produto ?>">
                            <input type="hidden" name="action" value="adicionar">
                            
                            <div class="mb-3">
                                <label class="form-label">Sua nota:</label>
                                <div class="rating-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star star-rating text-warning" data-nota="<?= $i ?>" style="font-size: 1.5rem; cursor: pointer; margin-right: 3px;"></i>
                                    <?php endfor; ?>
                                </div>
                                <input type="hidden" name="nota" id="nota-selecionada" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" id="btn-enviar-avaliacao" disabled>Enviar Avaliação</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="text-center mt-4 p-3 bg-light rounded">
                        <p class="mb-2">Para avaliar este produto, você precisa estar logado.</p>
                        <a href="login.php" class="btn btn-primary btn-sm">Fazer Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<!-- Exibir mensagens de sucesso/erro -->
<?php 
$mensagemSucesso = obterMensagemSucesso();
$mensagemErro = obterMensagemErro();
?>

<?php if ($mensagemSucesso): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">Sucesso</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                <?= escaparHtml($mensagemSucesso) ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($mensagemErro): ?>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header">
                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                <strong class="me-auto">Erro</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                <?= escaparHtml($mensagemErro) ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
    .miniatura-img {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .miniatura-img:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }

    .miniatura-img.active {
        border: 2px solid var(--pmain, #007bff);
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

#imagem-principal {
    transition: opacity 0.3s ease;
}

/* Estilos para avaliações */
.estrelas {
    color: #ffc107;
}

.rating-stars {
    display: inline-flex;
    align-items: center;
}

.rating-stars .star-rating {
    color: #ddd;
    transition: all 0.2s ease;
    cursor: pointer;
}

.rating-stars .star-rating:hover {
    transform: scale(1.1);
}

.rating-stars .star-rating.filled {
    color: #ffc107;
}

.rating-stars .star-rating.hover {
    color: #ffc107;
}

.progress {
    height: 8px;
}

.avaliacao-item:last-child {
    border-bottom: none !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

#btn-enviar-avaliacao:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
  
      #imagem-principal {
        transition: opacity 0.3s ease;
    }
</style>

<script>
function trocarImagemPrincipal(novaImagem, elemento) {
    // Atualiza a imagem principal
    const imagemPrincipal = document.getElementById('imagem-principal');
    
    // Efeito de fade
    imagemPrincipal.style.opacity = '0.5';
    
    setTimeout(() => {
        imagemPrincipal.src = novaImagem;
        imagemPrincipal.style.opacity = '1';
    }, 150);
    
    // Remove a classe 'active' de todas as miniaturas
    document.querySelectorAll('.miniatura-img').forEach(img => {
        img.classList.remove('active');
    });
    
    // Adiciona a classe 'active' na miniatura clicada
    elemento.classList.add('active');
}

// Sistema de avaliação com estrelas
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating');
    const notaInput = document.getElementById('nota-selecionada');
    const btnEnviar = document.getElementById('btn-enviar-avaliacao');
    let notaSelecionada = 0;
    
    if (stars.length > 0) {
        stars.forEach((star, index) => {
            const nota = parseInt(star.dataset.nota);
            
            // Evento de click para selecionar nota
            star.addEventListener('click', function() {
                notaSelecionada = nota;
                notaInput.value = nota;
                
                // Atualizar visual das estrelas selecionadas
                atualizarEstrelas(nota);
                
                // Habilitar botão de envio
                if (btnEnviar) {
                    btnEnviar.disabled = false;
                }
            });
            
            // Evento de hover
            star.addEventListener('mouseenter', function() {
                // Mostrar preview da nota no hover
                stars.forEach((s, i) => {
                    s.classList.remove('hover');
                    if (i < nota) {
                        s.classList.add('hover');
                    }
                });
            });
        });
        
        // Restaurar estado visual ao sair do container
        const ratingContainer = document.querySelector('.rating-stars');
        if (ratingContainer) {
            ratingContainer.addEventListener('mouseleave', function() {
                // Remover hover e mostrar seleção atual
                stars.forEach(s => s.classList.remove('hover'));
                if (notaSelecionada > 0) {
                    atualizarEstrelas(notaSelecionada);
                }
            });
        }
    }
    
    function atualizarEstrelas(nota) {
        stars.forEach((s, i) => {
            s.classList.remove('filled');
            if (i < nota) {
                s.classList.remove('bi-star');
                s.classList.add('bi-star-fill', 'filled');
            } else {
                s.classList.remove('bi-star-fill');
                s.classList.add('bi-star');
            }
        });
    }
});

// Submissão do formulário de avaliação com AJAX
document.getElementById('form-avaliacao')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nota = document.getElementById('nota-selecionada').value;
    
    if (!nota || nota < 1 || nota > 5) {
        alert('Por favor, selecione uma nota de 1 a 5 estrelas.');
        return;
    }
    
    // Criar FormData
    const formData = new FormData(this);
    
    // Desabilitar botão temporariamente
    const btnEnviar = document.getElementById('btn-enviar-avaliacao');
    btnEnviar.disabled = true;
    btnEnviar.textContent = 'Enviando...';
    
    // Enviar via AJAX
    fetch('../app/controller/AvaliacaoController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            // Recarregar a página para mostrar a nova avaliação
            window.location.reload();
        } else {
            alert('Erro ao enviar avaliação. Tente novamente.');
            btnEnviar.disabled = false;
            btnEnviar.textContent = 'Enviar Avaliação';
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao enviar avaliação. Tente novamente.');
        btnEnviar.disabled = false;
        btnEnviar.textContent = 'Enviar Avaliação';
    });
});
</script>


