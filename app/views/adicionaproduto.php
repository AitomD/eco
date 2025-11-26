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
            <select class="form-select mb-3" name="id_marca" id="id_marca_pc" required>
                <option disabled selected>Selecione uma marca</option>
                <option value="1">Acer</option>
                <option value="2">Asus</option>
                <option value="3">Dell</option>
                <option value="4">Lenovo</option>
                <option value="5">HP</option>
                <option value="11">AMD</option>
                <option value="12">Intel</option>
            </select>
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
            <select class="form-select mb-3" name="id_marca" id="id_marca_celular" required>
                <option disabled selected>Selecione uma marca</option>
                <option value="6">Apple</option>
                <option value="7">Motorola</option>
                <option value="8">Oppo</option>
                <option value="9">Samsung</option>
                <option value="10">Xiaomi</option>
            </select>
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
    const etapa3 = document.getElementById("etapa3");

    const btnAvancarEtapa1 = document.getElementById("btn-avancar-etapa1");
    const categoriaSelect = document.getElementById("categoria");

    function validarFormulario(form) {
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        return true;
    }

    // === ETAPA 1 → ETAPA 2A ou 2B ===
    btnAvancarEtapa1.addEventListener("click", () => {
        const form1 = document.getElementById("form-etapa1");
        if (!validarFormulario(form1)) return;

        const categoria = categoriaSelect.value;
        etapa1.classList.add("d-none");

        if (categoria === "1" || categoria === "2") {
            etapa2A.classList.remove("d-none");
        } else {
            etapa2B.classList.remove("d-none");
        }
    });

    // === VOLTAR ETAPA A ===
    document.getElementById("btn-voltar-A").addEventListener("click", () => {
        etapa2A.classList.add("d-none");
        etapa1.classList.remove("d-none");
    });

    // === VOLTAR ETAPA B ===
    document.getElementById("btn-voltar-B").addEventListener("click", () => {
        etapa2B.classList.add("d-none");
        etapa1.classList.remove("d-none");
    });

    // === ETAPA 2A → ETAPA 3 ===
    document.getElementById("btn-avancar-etapa3A").addEventListener("click", () => {
        const formA = document.getElementById("form-etapa2A");
        if (!validarFormulario(formA)) return;

        etapa2A.classList.add("d-none");
        etapa3.classList.remove("d-none");
    });

    // === ETAPA 2B → ETAPA 3 ===
    document.getElementById("btn-avancar-etapa3B").addEventListener("click", () => {
        const formB = document.getElementById("form-etapa2B");
        if (!validarFormulario(formB)) return;

        etapa2B.classList.add("d-none");
        etapa3.classList.remove("d-none");
    });

    // === VOLTAR ETAPA 3 → 2 ===
    document.getElementById("btn-voltar-etapa2").addEventListener("click", () => {
        const categoria = categoriaSelect.value;
        etapa3.classList.add("d-none");

        if (categoria === "1" || categoria === "2") {
            etapa2A.classList.remove("d-none");
        } else {
            etapa2B.classList.remove("d-none");
        }
    });

    // === BOTÃO ADICIONAR IMAGEM (máximo 3) ===
    document.getElementById("btn-add-img").addEventListener("click", () => {
        const container = document.getElementById("container-imagens");
        const totalInputs = container.querySelectorAll(".input-img").length;

        if (totalInputs >= 3) {
            alert("Máximo de 3 imagens.");
            return;
        }

        const novo = document.createElement("div");
        novo.classList.add("input-img", "mb-3");

        novo.innerHTML = `
            <input type="url" class="form-control" name="img[]" placeholder="URL da imagem" required>
        `;

        container.appendChild(novo);
    });

    // === FINALIZAR / ENVIAR CONTROLLER ===
   // === FINALIZAR / ENVIAR CONTROLLER ===
document.getElementById("btn-finalizar").addEventListener("click", async () => {

    const formData = new FormData();

    // --- ETAPA 1 ---
    const f1 = new FormData(document.getElementById("form-etapa1"));
    f1.forEach((v, k) => formData.append(k, v));

    const categoria = categoriaSelect.value;
    formData.append("categoria", categoria);

    // --- ETAPA 2 ---
    const form2 = categoria === "3"
        ? document.getElementById("form-etapa2B")
        : document.getElementById("form-etapa2A");

    const f2 = new FormData(form2);

    // Marca
    if (categoria === "3") {
        formData.append("id_marca", document.getElementById("id_marca_celular").value);
    } else {
        formData.append("id_marca", document.getElementById("id_marca_pc").value);
    }

    // Adicionando os campos da etapa 2 (menos marca)
    f2.forEach((v, k) => {
        if (k !== "id_marca") formData.append(k, v);
    });

    // --- ETAPA 3 (URLs das imagens) ---
    const imagens = document.querySelectorAll("input[name='img[]']");
    let total = 0;

    imagens.forEach(input => {
        if (input.value.trim() !== "" && total < 3) {
            formData.append("imagens[]", input.value.trim());
            total++;
        }
    });

    if (total === 0) {
        alert("Insira ao menos 1 URL de imagem.");
        return;
    }

    // --- FETCH CORRIGIDO ---
    try {
        const response = await fetch("../app/controller/NovoProdutoController.php", {
            method: "POST",
            body: formData
        });

        const texto = await response.text();
        console.log("RETORNO BRUTO DO PHP:", texto);

        const data = JSON.parse(texto); // agora converte

        console.log("JSON FINAL:", data);

        if (data.sucesso) {
            alert("Produto cadastrado com sucesso!");
        } else {
            alert("Erro: " + data.erro);
        }

    } catch (err) {
        console.error("ERRO NO FETCH:", err);
        alert("Erro no envio ao servidor.");
    }
});

document.addEventListener('DOMContentLoaded', (event) => {
    const inputPreco = document.querySelector('input[name="preco"]');
    const MAX_DIGITOS_INTEIROS = 5;

    if (inputPreco) {
        inputPreco.addEventListener('input', function (e) {
            let value = e.target.value.replace(',', '.');
            
            if (parseFloat(value) < 0) {
                e.target.value = '0.01';
                return;
            }

            const parts = value.split('.');
            let integerPart = parts[0];

            if (integerPart.length > MAX_DIGITOS_INTEIROS) {
                e.target.value = integerPart.substring(0, MAX_DIGITOS_INTEIROS) + (parts.length > 1 ? '.' + parts[1] : '');
            }

            if (parts.length > 1 && parts[1].length > 2) {
                e.target.value = parts[0] + '.' + parts[1].substring(0, 2);
            }
        });
    }
});
</script>