<?php
// <-- MODIFICAÇÃO: Inicia o buffer de saída
// Isso armazena todo o HTML em memória e só o envia no final.
// Isso permite que a função header() funcione mesmo estando no meio do arquivo.
ob_start();

require_once '../app/core/Database.php';
require_once '../app/model/Loja.php'; // Classe Loja

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
                    <div class="estrelas" style="color:var(--pmain);">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>
                    <span class="ms-2">(1.258 avaliações)</span>
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
    </section>
</main>

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
</style>

