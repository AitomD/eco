// Espera o documento HTML estar totalmente carregado
document.addEventListener("DOMContentLoaded", function() {

    // 1. Encontra o elemento do Modal pelo ID que demos a ele
    const modalElement = document.getElementById('modalAdicionarCupom');

    // 2. Encontra o Botão/Card de "Adicionar" pelo ID que demos a ele
    const btnAbrirModal = document.getElementById('btn-abrir-modal-cupom');

    // 3. Verifica se os dois elementos realmente existem na página
    if (btnAbrirModal && modalElement) {
        
        // 4. Cria uma instância oficial do Modal Bootstrap
        const meuModal = new bootstrap.Modal(modalElement);

        // 5. Adiciona um "ouvinte de clique" ao card
        btnAbrirModal.addEventListener('click', function() {
            // 6. Quando clicado, manda o modal aparecer
            meuModal.show();
        });
    }

});

// Espera o HTML carregar
document.addEventListener("DOMContentLoaded", function() {
    
    // Seleciona o formulário pelo ID que demos a ele
    const formAdicionarCupom = document.getElementById("formAdicionarCupom");

    // Adiciona um "ouvinte" para o evento de SUBMIT
    formAdicionarCupom.addEventListener("submit", function(event) {
        
        // 1. Impede o recarregamento padrão da página
        event.preventDefault(); 

        // 2. Pega todos os dados do formulário
        const formData = new FormData(formAdicionarCupom);
        
        // (Opcional) Desabilita o botão para evitar cliques duplos
        document.getElementById("btnSalvarCupom").disabled = true;
        document.getElementById("btnSalvarCupom").innerText = "Salvando...";

        // 3. Envia os dados para o endpoint que retorna JSON
        // Usamos `index.php` com o campo `ajax_add_cupom` para garantir que o roteador retorne JSON
        formData.append('ajax_add_cupom', '1');
        fetch("index.php", {
            method: "POST",
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json()) // Espera uma resposta JSON do PHP
        .then(data => {
            console.log('Resposta addCupom:', data);
            if (data.success) {
                // Deu certo!
                alert("Cupom salvo com sucesso!");
                
                // Fecha o modal (você precisa do ID do seu modal)
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalAdicionarCupom'));
                modal.hide();

                // Recarrega a página para mostrar o novo cupom
                location.reload(); 
            } else {
                // Deu errado (erro vindo do PHP)
                // Mostrar mensagem principal e, se houver, o detalhe do erro
                const detalhe = data.error ? ("\nDetalhe: " + data.error) : '';
                alert("Erro ao salvar: " + (data.message || data.mensagem || 'Erro desconhecido') + detalhe);
            }
        })
        .catch(error => {
            // Deu errado (erro de rede/conexão)
            console.error("Erro na requisição:", error);
            alert("Ocorreu um erro de conexão. Tente novamente.");
        })
        .finally(() => {
            // Reabilita o botão, não importa se deu certo ou errado
            document.getElementById("btnSalvarCupom").disabled = false;
            document.getElementById("btnSalvarCupom").innerText = "Adicionar";
        });
    });
});