<?php
// Inclui a conexão com o banco de dados
require_once '../core/Database.php'; // ajuste o caminho se necessário

// 1. Obter o ID do produto da URL e validar
$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_produto) {
    // Se não houver ID ou for inválido, exibe um erro e para a execução
    die("Produto não encontrado ou ID inválido.");
}

$produto = null; // Inicializa a variável do produto

try {
    $pdo = Database::conectar();

    // 2. Modificar a consulta SQL para buscar um único produto
    $sql = "SELECT p.id_produto, p.nome, p.preco, p.quantidade, p.id_categoria,
                   pi.descricao, pi.cor, pi.marca, pi.ram, pi.armazenamento,
                   pi.processador, pi.placa_mae, pi.placa_video, pi.fonte, pi.imagem
            FROM produto p
            JOIN produto_info pi ON p.id_info = pi.id_info
            WHERE p.id_produto = :id_produto"; // <<< Adicionada cláusula WHERE

    $stmt = $pdo->prepare($sql);
    
    // 3. Vincular o ID para segurança (previne SQL Injection)
    $stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);
    $stmt->execute();

    // 4. Usar fetch() em vez de fetchAll() para obter um único resultado
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        // Se a consulta não retornar nenhum produto, exibe um erro
        die("Produto não encontrado.");
    }

} catch (PDOException $e) {
    // Em caso de erro de conexão ou consulta
    die("Erro ao buscar o produto: " . $e->getMessage());
}

// A partir daqui, a variável $produto contém todos os dados do produto específico.
// Agora, podemos usar esta variável no HTML abaixo.
?>

<main class="container py-4">
    <section class="row g-4 bg-white mt-2 py-4">
        
        <div class="col-md-4">
            <div class="galeria-imagens bg-white border rounded p-3 h-100">
                <div class="miniaturas mb-3">
                    <p class="mb-2">img 1</p>
                </div>
                <div class="imagem-principal fw-bold text-center">
                    <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="img-fluid">
                </div>
            </div>
        </div>

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
                
                <div class="caracteristicas-produto mb-4">
                    <?php if (!empty($produto['cor'])): ?>
                        <p class="mb-1"><strong class="fw-semibold">Cor:</strong> <?= htmlspecialchars($produto['cor']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($produto['armazenamento'])): ?>
                        <p class="mb-1"><strong class="fw-semibold">Armazenamento:</strong> <?= htmlspecialchars($produto['armazenamento']) ?></p>
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

        <div class="col-md-3">
            <div class="compra-info-vendedor d-flex flex-column gap-3">
                <div class="card-compra border rounded p-3 bg-white">
                    <p class="fw-semibold mb-2 ">Estoque disponível</p> 
                    <p class="text-muted small">Quantidade: <?= htmlspecialchars($produto['quantidade']) ?> unidade(s)</p>
                    <div class="d-grid gap-2 mt-3">
                        <button class="purple-btn ">Comprar agora</button>
                        <button class="cart-button text-center">adicionar ao carrinho</button>
                    </div>
                    <p class="small text-muted mt-3">Compra Garantida, receba o produto que está esperando ou devolvemos o dinheiro.</p>
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