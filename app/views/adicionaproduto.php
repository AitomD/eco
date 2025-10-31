<div class="container-fluid d-flex justify-content-center align-items-center">
    <div class="container text-center" style="max-width: 400px;">
        <h4 class="mb-4">Adicionar Produto</h4>

        <!-- ETAPA 1 -->
        <form id="form-etapa1">
            <input type="text" name="nome" class="form-control mb-3" placeholder="Nome do produto" required>
            <input type="text" name="cor" class="form-control mb-3" placeholder="Cor" required>
            <input type="number" name="preco" class="form-control mb-3" placeholder="Preço (R$)" required>

            <select class="form-select mb-3" name="categoria" required>
                <option selected disabled>Selecione uma categoria</option>
                <option value="1">Computador</option>
                <option value="2">Notebook</option>
                <option value="4">Celular</option>
            </select>

            <select class="form-select mb-3" name="marca" required>
                <option selected disabled>Selecione uma marca</option>
                <option value="1">Acer</option>
                <option value="2">Asus</option>
                <option value="3">Dell</option>
                <option value="4">Lenovo</option>
                <option value="5">HP</option>
                <option value="6">Apple</option>
                <option value="7">Motorola</option>
                <option value="8">Oppo</option>
                <option value="9">Samsung</option>
                <option value="10">Xiaomi</option>
                <option value="15">AMD</option>
                <option value="16">Intel</option>
            </select>

            <button type="button" id="btn-proximo1" class="btn btn-primary w-100">Próximo</button>
        </form>

        <!-- ETAPA 2 -->
        <form id="form-etapa2" class="d-none">
            <h5 class="mb-3">Especificações do Produto</h5>
            <input type="text" name="processador" class="form-control mb-3" placeholder="Processador">
            <input type="text" name="memoria" class="form-control mb-3" placeholder="Memória RAM">
            <input type="text" name="armazenamento" class="form-control mb-3" placeholder="Armazenamento">
            <input type="text" name="placa_video" class="form-control mb-3" placeholder="Placa de Vídeo">
            <input type="text" name="placa_mae" class="form-control mb-3" placeholder="Placa mãe">
            <input type="text" name="fonte" class="form-control mb-3" placeholder="Fonte">

            <button type="button" id="btn-voltar1" class="btn btn-secondary w-100 mb-2">Voltar</button>
            <button type="button" id="btn-proximo2" class="btn btn-primary w-100">Próximo</button>
        </form>

        <!-- ETAPA 3 -->
        <form id="form-etapa3" class="d-none">
            <h5 class="mb-3">Imagens do Produto</h5>

            <div id="imagens-container">
                <input type="text" name="url_imagem[]" class="form-control mb-3" placeholder="URL da imagem 1" required>
            </div>

            <button type="button" id="btn-add-imagem" class="btn btn-outline-primary w-100 mb-3">+ Adicionar imagem</button>

            <button type="button" id="btn-voltar2" class="btn btn-secondary w-100 mb-2">Voltar</button>
            <button type="submit" class="btn btn-success w-100">Finalizar Cadastro</button>
        </form>
    </div>
</div>

<script>
const form1 = document.getElementById('form-etapa1');
const form2 = document.getElementById('form-etapa2');
const form3 = document.getElementById('form-etapa3');

const btnProximo1 = document.getElementById('btn-proximo1');
const btnProximo2 = document.getElementById('btn-proximo2');
const btnVoltar1 = document.getElementById('btn-voltar1');
const btnVoltar2 = document.getElementById('btn-voltar2');
const btnAddImagem = document.getElementById('btn-add-imagem');
const imagensContainer = document.getElementById('imagens-container');

let contadorImagens = 1;
const limiteImagens = 3;

// Objetivo: armazenar temporariamente todos os dados
let dadosProduto = {};

// Avançar Etapa 1 → 2
btnProximo1.addEventListener('click', () => {
    if (form1.checkValidity()) {
        dadosProduto.nome = form1.nome.value.trim();
        dadosProduto.cor = form1.cor.value.trim();
        dadosProduto.preco = parseFloat(form1.preco.value);
        dadosProduto.categoria = parseInt(form1.categoria.value);
        dadosProduto.marca = parseInt(form1.marca.value);

        form1.classList.add('d-none');
        form2.classList.remove('d-none');
    } else {
        form1.reportValidity();
    }
});

// Avançar Etapa 2 → 3
btnProximo2.addEventListener('click', () => {
    dadosProduto.processador = form2.processador.value.trim();
    dadosProduto.memoria = form2.memoria.value.trim();
    dadosProduto.armazenamento = form2.armazenamento.value.trim();
    dadosProduto.placa_video = form2.placa_video.value.trim();
    dadosProduto.placa_mae = form2.placa_mae.value.trim();
    dadosProduto.fonte = form2.fonte.value.trim();

    form2.classList.add('d-none');
    form3.classList.remove('d-none');
});

// Voltar Etapa 2 → 1
btnVoltar1.addEventListener('click', () => {
    form2.classList.add('d-none');
    form1.classList.remove('d-none');
});

// Voltar Etapa 3 → 2
btnVoltar2.addEventListener('click', () => {
    form3.classList.add('d-none');
    form2.classList.remove('d-none');
});

// Adicionar campo de imagem (máximo 3)
btnAddImagem.addEventListener('click', () => {
    if (contadorImagens < limiteImagens) {
        contadorImagens++;
        const novoCampo = document.createElement('input');
        novoCampo.type = 'text';
        novoCampo.name = 'url_imagem[]';
        novoCampo.classList.add('form-control', 'mb-3');
        novoCampo.placeholder = `URL da imagem ${contadorImagens}`;
        imagensContainer.appendChild(novoCampo);

        if (contadorImagens === limiteImagens) {
            btnAddImagem.disabled = true;
            btnAddImagem.textContent = "Limite de 3 imagens atingido";
        }
    }
});

// Enviar todas as etapas via AJAX
form3.addEventListener('submit', (e) => {
    e.preventDefault();

    // Pega URLs de imagens
    const urls = Array.from(document.querySelectorAll('input[name="url_imagem[]"]'))
                      .map(input => input.value.trim())
                      .filter(url => url !== '');
    dadosProduto.url_imagem = urls;

    // Adiciona id_loja se tiver (ou outra lógica de sessão)
    dadosProduto.id_loja = <?= $_SESSION['id_loja'] ?? 1 ?>;

    // Envia via fetch
    fetch('../app/controller/NovoProdutoController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dadosProduto)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.mensagem);
            // opcional: resetar formulário ou voltar para etapa 1
            form1.reset();
            form2.reset();
            form3.reset();
            form3.classList.add('d-none');
            form1.classList.remove('d-none');
            contadorImagens = 1;
            imagensContainer.innerHTML = `<input type="text" name="url_imagem[]" class="form-control mb-3" placeholder="URL da imagem 1" required>`;
            btnAddImagem.disabled = false;
            btnAddImagem.textContent = '+ Adicionar imagem';
        } else {
            alert(data.mensagem);
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erro ao enviar os dados.');
    });
});
</script>

