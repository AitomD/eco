<div class="container-fluid d-flex justify-content-center align-items-center">

    <!-- ETAPA 1 -->
    <div id="etapa1" class="container text-center" style="max-width: 400px;">
        <h4 class="mb-4">Adicionar Produto</h4>

        <form id="form-etapa1" novalidate>
            <input type="text" name="nome" class="form-control mb-3" placeholder="Nome do produto" required>
            <input type="number" name="preco" class="form-control mb-3" placeholder="Preço (R$)" required>

            <select class="form-select mb-3" name="categoria" id="categoria" required>
                <option disabled selected>Selecione uma categoria</option>
                <option value="1">Computador</option>
                <option value="2">Notebook</option>
                <option value="3">Celular</option>
            </select>
        </form>

        <button class="btn btn-primary w-100" id="btn-avancar-etapa1" type="button">Próximo</button>
    </div>

    <!-- ETAPA 2A – Para categoria 1 ou 2 -->
    <div id="etapa2A" class="container text-center d-none" style="max-width: 400px;">
        <h4 class="mb-4">Informações do Produto</h4>

        <form id="form-etapa2A" novalidate>
            <input type="text" class="form-control mb-3" name="descricao" placeholder="Descrição" required>
            <input type="text" class="form-control mb-3" name="ram" placeholder="RAM" required>
            <input type="text" class="form-control mb-3" name="armazenamento" placeholder="Armazenamento" required>
            <input type="text" class="form-control mb-3" name="processador" placeholder="Processador" required>
            <input type="text" class="form-control mb-3" name="placa_mae" placeholder="Placa-mãe" required>
            <input type="text" class="form-control mb-3" name="placa_video" placeholder="Placa de vídeo" required>
            <input type="text" class="form-control mb-3" name="fonte" placeholder="Fonte" required>
            <input type="text" class="form-control mb-3" name="cor" placeholder="Cor" required>
        </form>

        <div class="d-flex justify-content-between">
            <button class="btn btn-secondary" id="btn-voltar-A" type="button">Voltar</button>
            <button class="btn btn-primary" id="btn-avancar-etapa3A" type="button">Próximo</button>
        </div>
    </div>

    <!-- ETAPA 2B – Para categoria 3 -->
    <div id="etapa2B" class="container text-center d-none" style="max-width: 400px;">
        <h4 class="mb-4">Informações do Produto</h4>

        <form id="form-etapa2B" novalidate>
            <input type="text" class="form-control mb-3" name="armazenamento" placeholder="Armazenamento" required>
            <input type="text" class="form-control mb-3" name="ram" placeholder="RAM" required>
            <input type="text" class="form-control mb-3" name="cor" placeholder="Cor" required>
            <input type="text" class="form-control mb-3" name="tamanho_tela" placeholder="Tamanho da tela" required>
            <input type="text" class="form-control mb-3" name="processador" placeholder="Processador" required>
            <input type="text" class="form-control mb-3" name="camera_traseira" placeholder="Câmera traseira" required>
            <input type="text" class="form-control mb-3" name="camera_frontal" placeholder="Câmera frontal" required>
            <input type="text" class="form-control mb-3" name="bateria" placeholder="Bateria (mAh)" required>
        </form>

        <div class="d-flex justify-content-between">
            <button class="btn btn-secondary" id="btn-voltar-B" type="button">Voltar</button>
            <button class="btn btn-primary" id="btn-avancar-etapa3B" type="button">Próximo</button>
        </div>
    </div>

    <!-- ETAPA 3 – Links de Imagens -->
<!-- ETAPA 3 – Links de Imagens -->
<div id="etapa3" class="container text-center d-none" style="max-width: 400px;">
    <h4 class="mb-4">Imagens do Produto</h4>

    <form id="form-etapa3" novalidate>

        <div id="container-imagens">

            <!-- Campo inicial -->
            <div class="input-img mb-3">
                <input type="url" class="form-control" name="img[]" placeholder="URL da imagem" required>
            </div>

        </div>

        <button type="button" id="btn-add-img" class="btn btn-outline-primary w-100 mb-3">
            + Adicionar outra imagem
        </button>

    </form>

    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" id="btn-voltar-etapa2" type="button">Voltar</button>
        <button class="btn btn-success" id="btn-finalizar" type="button">Finalizar</button>
    </div>
</div>


</div>

<script>
const etapa1 = document.getElementById("etapa1");
const etapa2A = document.getElementById("etapa2A");
const etapa2B = document.getElementById("etapa2B");

const btnAvancarEtapa1 = document.getElementById("btn-avancar-etapa1");

const categoriaSelect = document.getElementById("categoria");

function validarFormulario(form) {
    if (!form.checkValidity()) {
        form.reportValidity();
        return false;
    }
    return true;
}

// ETAPA 1 → ETAPA 2A ou 2B
btnAvancarEtapa1.addEventListener("click", () => {
    const form1 = document.getElementById("form-etapa1");
    if (!validarFormulario(form1)) return;

    const categoria = categoriaSelect.value;

    etapa1.classList.add("d-none");

    if (categoria === "1" || categoria === "2") {
        etapa2A.classList.remove("d-none");
    } else if (categoria === "3") {
        etapa2B.classList.remove("d-none");
    }
});

// VOLTAR
document.getElementById("btn-voltar-A").addEventListener("click", () => {
    etapa2A.classList.add("d-none");
    etapa1.classList.remove("d-none");
});

document.getElementById("btn-voltar-B").addEventListener("click", () => {
    etapa2B.classList.add("d-none");
    etapa1.classList.remove("d-none");
});

// ETAPA 2A → ETAPA 3
document.getElementById("btn-avancar-etapa3A").addEventListener("click", () => {
    const formA = document.getElementById("form-etapa2A");
    if (!validarFormulario(formA)) return;

    etapa2A.classList.add("d-none");
    etapa3.classList.remove("d-none");
});

// ETAPA 2B → ETAPA 3
document.getElementById("btn-avancar-etapa3B").addEventListener("click", () => {
    const formB = document.getElementById("form-etapa2B");
    if (!validarFormulario(formB)) return;

    etapa2B.classList.add("d-none");
    etapa3.classList.remove("d-none");
});

// VOLTAR PARA ETAPA 2
document.getElementById("btn-voltar-etapa2").addEventListener("click", () => {

    const categoria = categoriaSelect.value;

    etapa3.classList.add("d-none");

    if (categoria === "1" || categoria === "2") {
        etapa2A.classList.remove("d-none");
    } else {
        etapa2B.classList.remove("d-none");
    }
});

document.getElementById("btn-finalizar").addEventListener("click", () => {
    const form3 = document.getElementById("form-etapa3");
    if (!validarFormulario(form3)) return;

    const categoria = categoriaSelect.value;

    // Coletar URLs
    const imagens = [
        { url: form3.img1.value, ordem: 1 },
        { url: form3.img2.value, ordem: 2 },
        { url: form3.img3.value, ordem: 3 }
    ];

    let payload = {
        etapa1: Object.fromEntries(new FormData(document.getElementById("form-etapa1"))),
        etapa2: {},
        imagens: []
    };

    // Dados específicos da etapa 2
    if (categoria === "1" || categoria === "2") {
        payload.etapa2 = Object.fromEntries(new FormData(document.getElementById("form-etapa2A")));
        payload.tipo = "pc";
    } else {
        payload.etapa2 = Object.fromEntries(new FormData(document.getElementById("form-etapa2B")));
        payload.tipo = "celular";
    }

    // Montar imagens conforme especificação
    payload.imagens = imagens.map(img => {
        return {
            url: img.url,
            ordem: img.ordem,
            id_info: (categoria === "1" || categoria === "2") ? "PREENCHER NO PHP" : null,
            id_celular: (categoria === "3") ? "PREENCHER NO PHP" : null
        };
    });

    console.log("OBJETO FINAL PARA ENVIAR AO PHP:");
    console.log(payload);

    alert("Pronto! Os dados foram reunidos e estão no console do navegador.");
});

const containerImagens = document.getElementById("container-imagens");
const btnAddImg = document.getElementById("btn-add-img");

btnAddImg.addEventListener("click", () => {

    const quantidadeAtual = containerImagens.querySelectorAll(".input-img").length;
    if (quantidadeAtual >= 3) {
        alert("Máximo de 3 imagens permitido.");
        return;
    }

    const div = document.createElement("div");
    div.classList.add("input-img", "mb-3");

    div.innerHTML = `
        <div class="input-group">
            <input type="url" class="form-control" name="img[]" placeholder="URL da imagem" required>
            <button class="btn btn-danger btn-remover-img" type="button">X</button>
        </div>
    `;

    containerImagens.appendChild(div);

    // Evento de remover
    div.querySelector(".btn-remover-img").addEventListener("click", () => {
        div.remove();
    });

});

document.getElementById("btn-finalizar").addEventListener("click", () => {
    const form3 = document.getElementById("form-etapa3");
    if (!validarFormulario(form3)) return;

    const categoria = categoriaSelect.value;

    // Coletar somente as imagens inseridas
    const urls = [...document.querySelectorAll("input[name='img[]']")]
                 .map((input, index) => ({
                    url: input.value,
                    ordem: index + 1
                 }));

    let payload = {
        etapa1: Object.fromEntries(new FormData(document.getElementById("form-etapa1"))),
        etapa2: {},
        imagens: []
    };

    // Dados específicos da etapa 2
    if (categoria === "1" || categoria === "2") {
        payload.etapa2 = Object.fromEntries(new FormData(document.getElementById("form-etapa2A")));
        payload.tipo = "pc";
    } else {
        payload.etapa2 = Object.fromEntries(new FormData(document.getElementById("form-etapa2B")));
        payload.tipo = "celular";
    }

    // Montar imagens conforme especificação
    payload.imagens = urls.map(img => ({
        url: img.url,
        ordem: img.ordem,
        id_info: (categoria === "1" || categoria === "2") ? "PREENCHER NO PHP" : null,
        id_celular: (categoria === "3") ? "PREENCHER NO PHP" : null
    }));

    console.log("OBJETO FINAL PARA O PHP:");
    console.log(payload);

    alert("Imagens e dados reunidos! Veja no console.");
});

</script>
