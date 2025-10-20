<?php
require_once '../app/core/Database.php';

// 1. Obter o ID do produto da URL e validar
$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_produto) {
    die("Produto não encontrado ou ID inválido.");
}

try {
    $pdo = Database::conectar();

    // Consulta SQL para trazer o produto e todas as imagens
    $sql = "
        SELECT 
            p.id_produto,
            p.nome,
            p.cor,
            p.preco,
            p.quantidade,
            pi.descricao,
            pi.id_marca,
            pi.id_categoria,
            pi.ram,
            pi.armazenamento,
            pi.processador,
            pi.placa_mae,
            pi.placa_video,
            pi.fonte,
            i.url AS imagem
        FROM produto p
        JOIN produto_info pi ON p.id_info = pi.id_info
        LEFT JOIN imagem i ON pi.id_info = i.id_info
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
        
        <div class="col-md-4">
            <div class="galeria-imagens bg-white border rounded p-3 h-100">
                
                <!-- Miniaturas -->
                <div class="miniaturas mb-3 d-flex gap-2 flex-wrap">
                    <?php foreach ($imagens as $img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" 
                             alt="<?= htmlspecialchars($produto['nome']) ?>" 
                             class="img-thumbnail" 
                             style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;">
                    <?php endforeach; ?>
                </div>

                <!-- Imagem principal -->
                <div class="imagem-principal text-center">
                    <img src="<?= htmlspecialchars($imagens[0]) ?>" 
                         alt="<?= htmlspecialchars($produto['nome']) ?>" 
                         class="img-fluid rounded">
                </div>
            </div>
        </div>

        <!-- Resto do seu HTML permanece igual -->
        <div class="col-md-5">
            <div class="info-produto bg-white border rounded p-4 h-100">
                <p class="text-muted small mb-2">Novo | +1000 vendidos</p>
                <h1 class="h3 fw-bold " style="color:var(--black);"><?= htmlspecialchars($produto['nome']) ?></h1>
                <div class="avaliacoes d-flex align-items-center small text-muted my-3">
                    <div class="estrelas " style="color:var(--pmain);">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <span class="ms-2">(1.258 avaliações)</span>
                </div>
                <div class="preco-produto my-4">
                    <h2 class="display-5 mb-1" style="color:var(--black);">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></h2>
                    <p class="text-primary">em 10x R$ <?= number_format($produto['preco'] / 10, 2, ',', '.') ?> sem juros</p>
                </div>
        <div class="sobre-o-produto border-top pt-3">
            <h5 style="color:var(--black);" ><strong>Informarções do Produto:</strong></h5>
            <ul class="list-unstyled mt-2 text-muted">
                
                <?php if (!empty($produto['processador'])): ?>
                    
                        <li style="color:var(--black);">• Processador: <?= htmlspecialchars($produto['processador']) ?></li>
                    
                <?php endif; ?>
                
                <?php if (!empty($produto['ram'])): ?>
                   
                        <li style="color:var(--black);">• Memória RAM: <?= htmlspecialchars($produto['ram']) ?></li>
                    
                <?php endif; ?>
                
                <?php if (!empty($produto['placa_video'])): ?>
                    
                        <li  style="color:var(--black);">• Placa de Vídeo: <?= htmlspecialchars($produto['placa_video']) ?></li>
                    
                <?php endif; ?>
                
                
                <?php if (!empty($produto['armazenamento'])): ?>
                    
                        <li  style="color:var(--black);">• Armazenamento: <?= htmlspecialchars($produto['armazenamento']) ?></li>
                    
                <?php endif; ?>
                    
                <?php if (!empty($produto['fonte'])): ?>
                    
                        <li  style="color:var(--black);">• Fonte: <?= htmlspecialchars($produto['fonte']) ?></li>
                    
                <?php endif; ?>

                <?php if (!empty($produto['placa_mae'])): ?>
                    
                        <li  style="color:var(--black);">• Placa Mãe: <?= htmlspecialchars($produto['placa_mae']) ?></li>
                    
                <?php endif; ?>
        
                </ul>
        </div>
        </div>
</div>
       

        <!-- Coluna de compra e vendedor permanece igual -->
        <div class="col-md-3">
            <div class="compra-info-vendedor d-flex flex-column gap-3">
                <div class="card-compra border rounded p-3 bg-white">
                    <p class="fw-semibold mb-2 ">Estoque disponível</p> 
                    <p class="text-muted small">Quantidade: <?= htmlspecialchars($produto['quantidade']) ?> unidade(s)</p>
                    <div class="d-grid gap-3 mt-3">
                        <button class="btn-product ">Comprar agora</button>
                        <button class="btn-add-cart btn-product text-center">adicionar ao carrinho</button>
                    </div>
                    <p class="small fs-6 text-muted mt-3"><i class="bi bi-arrow-return-left mr-1 text-primary"></i><span class="text-primary"> Devolução grátis</span>. Você tem 30 dias a partir da data de recebimento.</p>
                    <p class="small fs-6 text-muted mt-3"><i class="bi bi-shield-check mr-1 text-primary"></i><span  class="text-primary"> Compra Garantida</span>, receba o produto que está esperando ou devolvemos o dinheiro.</p>
                    <p class="small fs-6 text-muted mt-3"><i class="bi bi-award mr-1 text-primary"></i> 12 meses de garantia de fábrica.</p>
                    
                </div>
                <div class="card-vendedor border rounded p-3 bg-white">
                    <h3 class="h6 fw-semibold">Informações sobre o vendedor</h3>
                    <p class="small my-1">Localização: São Paulo</p>
                    <p class="small text-muted">MercadoLíder | +5mil Vendas</p> 
                </div>
            </div>
        </div>

        <div class="col-12">
             <div class="descricao-completa bg-white border rounded p-4 mt-2">
                <h2 class="h4">Descrição</h2>
                <p class="text-muted mt-3"><?= nl2br(htmlspecialchars($produto['descricao'])) ?></p>
            </div>
        </div>
    </section>
</main>
