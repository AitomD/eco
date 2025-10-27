<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../public/css/produto.css">
</head>

<body>

    <div style="height: 50px;"></div>

    <div class="container-fluid text-center cont-produto">
    <div class="row">
        <!-- Coluna lateral -->
        <div class="col-sm-2 text-start cont-filtro">
            <br>
            <h5 class="text-light">
                <i class="fa-solid fa-clipboard "></i>
                Categorias
            </h5>
            <p class="link-produto" data-categoria="1">Computadores Desktop</p>
            <p class="link-produto" data-categoria="2">Notebooks</p>
            <p class="link-produto" data-categoria="3">SmartTV</p>
            <p class="link-produto" data-categoria="4">Smartphones</p>
            <br>
            <h5 class="text-light">
                <i class="fa-solid fa-microchip"></i>
                Computadores
            </h5>
            <p class="link-produto" data-marca="16">Intel</p>
            <p class="link-produto" data-marca="15">AMD</p>
            <br>
            <h5 class="text-light">
                <i class="fa-solid fa-laptop"></i>
                Notebooks
            </h5>
            <p class="link-produto" data-marca="1">Acer</p>
            <p class="link-produto" data-marca="2">Asus</p>
            <p class="link-produto" data-marca="3">Dell</p>
            <p class="link-produto" data-marca="4">Lenovo</p>
            <p class="link-produto" data-marca="5">HP</p>
            <br>
            <h5 class="text-light">
                <i class="fa-solid fa-mobile-screen"></i>
                Smartphones
            </h5>
            <p class="link-produto" data-marca="6">Apple</p>
            <p class="link-produto" data-marca="7">Motorola</p>
            <p class="link-produto" data-marca="8">Oppo</p>
            <p class="link-produto" data-marca="9">Samsung</p>
            <p class="link-produto" data-marca="10">Xiaomi</p>
            <br>
            <h5 class="text-light">
                <i class="fa-solid fa-tv"></i>
                SmartTV
            </h5>
            <p class="link-produto" data-marca="11">AOC</p>
            <p class="link-produto" data-marca="12">LG</p>
            <p class="link-produto" data-marca="13">Philco</p>
            <p class="link-produto" data-marca="9">Samsung</p>
            <p class="link-produto" data-marca="14">Sony</p>
        </div>

        <!-- Coluna de produtos -->
        <div class="col-sm-10 ">
            <div id="produtos">
                <!-- Produtos aparecem aqui via JS -->
            </div>
        </div>
    </div>
</div>

    <div style="height: 50px;"></div>

   <script>
document.addEventListener('DOMContentLoaded', () => {
  const botoes = document.querySelectorAll('.link-produto');
  let filtroCategoria = null;
  let filtroMarca = null;

  // Função para buscar produtos via AJAX
  function buscarProdutos(categoria = null, marca = null) {
    let url = `../app/controller/FiltroController.php?`;
    if (categoria) url += `categoria=${categoria}&`;
    if (marca) url += `marca=${marca}&`;

    fetch(url)
      .then(res => res.json())
      .then(data => atualizarProdutosNaTela(data))
      .catch(err => console.error(err));
  }

  // Função para atualizar a tela
  function atualizarProdutosNaTela(produtos) {
    const container = document.querySelector('#produtos');
    if (!container) return;
    container.innerHTML = '';

    // Cria a row que vai conter os cards
    const row = document.createElement('div');
    row.classList.add('row');

    produtos.forEach(p => {
      const card = document.createElement('div');
      card.classList.add('card', 'm-6', 'col-sm-3'); // 4 cards por linha
      card.dataset.id = p.id_produto;
      card.innerHTML = `
  <img src="${p.imagem}" class="card-img-top" alt="${p.nome}">
  <div class="card-body">
    <h5 class="card-title">${p.nome}</h5>
    <p class="card-text">Marca: ${p.marca}</p>
    <p class="card-text">Categoria: ${p.categoria}</p>
    <p class="card-text card-price">R$ ${p.preco}</p>
    <p class="card-text">Estoque: ${p.quantidade_disponivel}</p>
    <div class="d-flex justify-content-between mt-3">
      <button class="btn btn-primary btn-sm btn-add-cart" 
              data-id="${p.id_produto}" 
              data-nome="${p.nome}" 
              data-preco="${p.preco}" 
              data-imagem="${p.imagem}">
        <i class="bi bi-cart2"></i> Carrinho
      </button>
      <a href="index.php?url=itemCompra&id=${p.id_produto}" class="btn btn-outline-secondary btn-sm btn-detalhes">
        <i class="fa-solid fa-clipboard"></i> Ficha Técnica
      </a>
    </div>
  </div>
`;
      row.appendChild(card);
    });

    container.appendChild(row);
}

  // Inicializa a tela com todos os produtos
  buscarProdutos();

  // Configura eventos dos botões de filtro
  botoes.forEach(botao => {
    botao.addEventListener('click', () => {
      // Remove estado ativo dos botões
      botoes.forEach(b => b.classList.remove('ativo'));
      botao.classList.add('ativo');

      // Atualiza filtros
      filtroCategoria = botao.dataset.categoria || null;
      filtroMarca = botao.dataset.marca || null;

      // Busca produtos filtrados
      buscarProdutos(filtroCategoria, filtroMarca);
    });
  });
});
</script>

<script src="../public/js/carrinho.js"></script>

</body>

</html>