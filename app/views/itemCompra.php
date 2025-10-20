<?php
require_once '../app/core/Database.php';

// 1. Obter o ID do produto da URL e validar
$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_produto) {
    die("Produto não encontrado ou ID inválido.");
}

try {
    $pdo = Database::conectar();

    // Consulta SQL completa
    $sql = "
        SELECT 
            p.id_produto,
            p.nome,
            p.cor,
            p.preco,
            pi.descricao,
            pi.ram,
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
        JOIN produto_info pi ON p.id_info = pi.id_info
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

    // Dados do produto (iguais em todas as linhas)
    $produto = $resultados[0];

    // Todas as imagens em um array
    $imagens = array_column($resultados, 'imagem');

} catch (PDOException $e) {
    die("Erro ao buscar o produto: " . $e->getMessage());
}
?>

<main class="container py-4">
    <section class="row g-4 bg-white mt-2 py-4">
        
        <!-- GALERIA DE IMAGENS -->
        <div class="col-md-4">
            <div class="galeria-imagens bg-white border rounded p-3 h-100">
                <div class="miniaturas mb-3 d-flex gap-2 flex-wrap">
                    <?php foreach ($imagens as $img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" 
                             alt="<?= htmlspecialchars($produto['nome']) ?>" 
                             class="img-thumbnail" 
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                    <?php endforeach; ?>
                </div>

                <div class="imagem-principal text-center">
                    <img src="<?= htmlspecialchars($produto['imagem_principal']) ?>" 
                         alt="<?= htmlspecialchars($produto['nome']) ?>" 
                         class="img-fluid rounded">
                </div>
            </div>
        </div>

        <!-- INFORMAÇÕES DO PRODUTO -->
        <div class="col-md-5">
            <div class="info-produto bg-white border rounded p-4 h-100">
                <p class="text-muted small mb-2">Novo | +1000 vendidos</p>
                <h1 class="h3 fw-bold" style="color:var(--black);"><?= htmlspecialchars($produto['nome']) ?></h1>
                <p class="text-muted small">Marca: <?= htmlspecialchars($produto['marca']) ?> | Categoria: <?= htmlspecialchars($produto['categoria']) ?></p>

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

                <div class="caracteristicas-produto mb-4">
                    <?php if (!empty($produto['cor'])): ?>
                        <p><strong>Cor:</strong> <?= htmlspecialchars($produto['cor']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($produto['armazenamento'])): ?>
                        <p><strong>Armazenamento:</strong> <?= htmlspecialchars($produto['armazenamento']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="sobre-o-produto border-top pt-3">
                    <h3 class="h5">O que você precisa saber sobre este produto</h3>
                    <ul class="list-unstyled mt-2 text-muted">
                        <?php if (!empty($produto['processador'])): ?>
                            <li>• Processador: <?= htmlspecialchars($produto['processador']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['ram'])): ?>
                            <li>• Memória RAM: <?= htmlspecialchars($produto['ram']) ?></li>
                        <?php endif; ?>
                        <?php if (!empty($produto['placa_video'])): ?>
                            <li>• Placa de Vídeo: <?= htmlspecialchars($produto['placa_video']) ?></li>
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
                        <button class="purple-btn">Comprar agora</button>
                        <button class="cart-button text-center">Adicionar ao carrinho</button>
                    </div>
                    <p class="small text-muted mt-3">
                        Compra Garantida — receba o produto que está esperando ou devolvemos o dinheiro.
                    </p>
                </div>

                <div class="card-vendedor border rounded p-3 bg-white">
                    <h3 class="h6 fw-semibold">Informações sobre o vendedor</h3>
                    <p class="small my-1">Localização: São Paulo</p>
                    <p class="small text-muted">MercadoLíder | +5mil Vendas</p> 
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
