

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
        <p class="link-produto">Mostrar Todos</p>
        <p class="link-produto" data-categoria="1">Computadores Desktop</p>
        <p class="link-produto" data-categoria="2">Notebooks</p>
        <p class="link-produto" data-categoria="3">Smartphones</p>
        <br>
        <h5 class="text-light">
          <i class="fa-solid fa-microchip"></i>
          Computadores
        </h5>
        <p class="link-produto" data-marca="12">Intel</p>
        <p class="link-produto" data-marca="11">AMD</p>
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

      </div>

      <!-- Coluna de produtos -->
      <div class="col-sm-10 ">
        <div id="produtos">
          <!-- Produtos aparecem aqui via JS -->
        </div>
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

      // Verificar se vem categoria pela URL (redirecionamento dos cards)
      const urlParams = new URLSearchParams(window.location.search);
      const categoriaURL = urlParams.get('categoria');

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
          const imagemSrc = p.imagem || '../public/img/no-image.png'; // Imagem padrão se não houver
          card.innerHTML = `
  <img src="${imagemSrc}" class="card-img-top object-fit-fill" alt="${p.nome}" >
  <div class="card-body">
    <h5 class="card-title">${p.nome}</h5>
    <p class="card-text card-price">R$ ${p.preco}</p>
    <div class="d-flex justify-content-between mt-3">
      <button class="btn text-light btn-sm mx-3 w-100  btn-add-cart" 
              data-id="${p.id_produto}" 
              data-nome="${p.nome}" 
              data-preco="${p.preco}" 
              data-imagem="${imagemSrc}">
        <i class="bi bi-cart2"></i> Carrinho
      </button>
      <a href="index.php?url=itemCompra&id=${p.id_produto}" class="btn  w-100 btn-sm btn-detalhes">
        <i class="fa-solid fa-clipboard"></i> Ficha Técnica
      </a>
    </div>
  </div>
`;
          row.appendChild(card);
        });

        container.appendChild(row);
      }

      // Inicializa a tela com todos os produtos ou com filtro da URL
      if (categoriaURL) {
        filtroCategoria = categoriaURL;
        buscarProdutos(filtroCategoria);
        
        // Marcar o botão de categoria como ativo
        const botaoCategoria = document.querySelector(`.link-produto[data-categoria="${categoriaURL}"]`);
        if (botaoCategoria) {
          botoes.forEach(b => b.classList.remove('ativo'));
          botaoCategoria.classList.add('ativo');
        }
      } else {
        buscarProdutos();
      }

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