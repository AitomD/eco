<?php
// Carregar produtos para o carrossel (apenas PCs e Notebooks)
require_once __DIR__ . '/../model/Produto.php';

$produto = new Produto();
$produtos = $produto->filtrar(); // Buscar todos os produtos

// Filtrar apenas produtos que tenham estoque disponível para exibir primeiro
usort($produtos, function($a, $b) {
    // Produtos com estoque aparecem primeiro
    if ($a['quantidade_disponivel'] === 'Sem Estoque' && $b['quantidade_disponivel'] !== 'Sem Estoque') {
        return 1;
    }
    if ($a['quantidade_disponivel'] !== 'Sem Estoque' && $b['quantidade_disponivel'] === 'Sem Estoque') {
        return -1;
    }
    return 0;
});

// Limitar a 12 produtos para não sobrecarregar a página inicial
$produtos = array_slice($produtos, 0, 12);
?>

<main>

  <!-- Banner principal -->

  <div class="cont-banner" data-aos="fade-right" data-aos-duration="2000">
    <div id="carouselExample" class="carousel slide">
      <div class="carousel-inner">

        <div class="carousel-item active">
          <img src="img/banner1.png" class="d-block w-100" style="height: 500px; object-fit: cover;"
            alt="Descrição do Banner 1">
        </div>

        <div class="carousel-item">
          <img src="img/banner2.png" class="d-block w-100" style="height: 500px; object-fit: cover;"
            alt="Descrição do Banner 2">
        </div>

        <div class="carousel-item">
          <img src="img/banner3.png" class="d-block w-100" style="height: 500px; object-fit: cover;"
            alt="Descrição do Banner 3">
        </div>

      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>

    </div>
  </div>
  <div style="height: 99px;"></div>

  <!-- Cards -->
  <div class="container text-center" data-aos="fade-left" data-aos-duration="2000">

    <div class="row">
      <div class="col">

        <div class="card text-bg-dark">
          <img src="../public/img/card1.png" class="card-img" alt="">
          <div class="card-img-overlay">
            <p class="card-text"></p>
            <p class="card-text"><small></small></p>
          </div>

        </div>
      </div>


        <div class="col">
          <div class="card text-bg-dark">
            <img src="../public/img/card2.png" class="card-img" alt="oferta">
            <div class="card-img-overlay">
              <p class="card-text"></p>
              <p class="card-text"><small></small></p>
            </div>
        </div>
      </div>

      <div class="col">
        <div class="card text-bg-dark">
          <img src="../public/img/card3.png" class="card-img" alt="...">
          <div class="card-img-overlay">
            <p class="card-text"></p>
            <p class="card-text"><small></small></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="height: 100px;"></div>

  <div id="carouselCards" class="carousel slide product-carousel" data-bs-ride="carousel"
    style="max-width: 1200px; margin: auto;">
    <div class="carousel-inner">

      <?php 
      $produtosPorSlide = 4; // 4 produtos por slide
      $totalProdutos = count($produtos);
      $totalSlides = ceil($totalProdutos / $produtosPorSlide);
      
      for ($slide = 0; $slide < $totalSlides; $slide++): 
        $inicio = $slide * $produtosPorSlide;
        $fim = min($inicio + $produtosPorSlide, $totalProdutos);
        $isActive = $slide === 0 ? 'active' : '';
      ?>
        <div class="carousel-item <?= $isActive ?>">
          <div class="row">
            <?php for ($i = $inicio; $i < $fim; $i++): 
              $prod = $produtos[$i];
              
              // Array de imagens locais de fallback
              $imagensLocais = [
                  'img/note.jpg',
                  'img/computador1.webp',
                  'img/computador2.webp',
                  'img/computador3.webp',
                  'img/computador4.webp',
                  'img/computador5.webp',
                  'img/computador6.webp',
                  'img/computador7.webp'
              ];
              
              // Definir imagem a usar
              if (!empty($prod['imagem'])) {
                  // Se a URL da imagem é externa (http/https), usar diretamente
                  if (strpos($prod['imagem'], 'http') === 0) {
                      $imagemUrl = $prod['imagem'];
                  } else {
                      // Se é um caminho local, processar
                      $imagemUrl = strpos($prod['imagem'], 'img/') === 0 ? $prod['imagem'] : 'img/' . $prod['imagem'];
                  }
              } else {
                  // Usar uma imagem local baseada no ID do produto
                  $indiceImagem = ($prod['id_produto'] - 1) % count($imagensLocais);
                  $imagemUrl = $imagensLocais[$indiceImagem];
              }
              
              $preco = number_format($prod['preco'], 2, ',', '.');
            ?>
              <div class="col-md-3">
                <div class="card product-card" data-id="<?= htmlspecialchars($prod['id_produto']) ?>" data-price="<?= $prod['preco'] ?>">
                  <a href="index.php?url=produto&id=<?= htmlspecialchars($prod['id_produto']) ?>" style="text-decoration: none;">
                    <img src="<?= htmlspecialchars($imagemUrl) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($prod['nome']) ?>"
                         onerror="this.src='img/ImgNotFound.png';">
                  </a>
                  <div class="card-body text-center">
                    <h6 class="card-title">
                      <a href="index.php?url=produto&id=<?= htmlspecialchars($prod['id_produto']) ?>" 
                         style="text-decoration: none; color: inherit;">
                        <?= htmlspecialchars($prod['nome']) ?>
                      </a>
                    </h6>
                    <p class="card-price">R$ <?= $preco ?></p>
                    <p class="card-stock">
                      <?= $prod['quantidade_disponivel'] === 'Sem Estoque' ? 
                          '<span class="text-danger">Sem Estoque</span>' : 
                          'Estoque: ' . $prod['quantidade_disponivel'] ?>
                    </p>
                    <?php if ($prod['quantidade_disponivel'] !== 'Sem Estoque' && $prod['quantidade_disponivel'] > 0): ?>
                      <button class="btn btn-product btn-add-cart">Adicionar ao Carrinho</button>
                    <?php else: ?>
                      <button class="btn btn-secondary" disabled>Indisponível</button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            <?php endfor; ?>
            
            <?php 
            // Preencher colunas vazias se necessário para manter layout
            $produtosNoSlide = $fim - $inicio;
            for ($j = $produtosNoSlide; $j < $produtosPorSlide; $j++): 
            ?>
              <div class="col-md-3">
                <div class="card product-card" style="visibility: hidden;">
                  <div class="card-body text-center">
                    <h6 class="card-title">&nbsp;</h6>
                    <p class="card-price">&nbsp;</p>
                  </div>
                </div>
              </div>
            <?php endfor; ?>
          </div>
        </div>
      <?php endfor; ?>
      
      <?php if (empty($produtos)): ?>
        <div class="carousel-item active">
          <div class="row">
            <div class="col-12 text-center py-5">
              <h4>Nenhum produto encontrado</h4>
              <p class="text-muted">Estamos trabalhando para adicionar novos produtos em breve.</p>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Controles do carrossel -->
    <?php if ($totalSlides > 1): ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselCards" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselCards" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Próximo</span>
      </button>
    <?php endif; ?>
  </div>

  <div style="height: 100px;"></div>

</main>