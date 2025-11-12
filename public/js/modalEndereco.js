document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Seleciona os elementos
            const triggerLabel = document.querySelector('label[for="mudaEndereco"]');
            const modalElement = document.getElementById('enderecoModal');
            const formEndereco = document.getElementById('form-novo-endereco');
            
            // Verifica se os elementos essenciais existem
            if (!triggerLabel || !modalElement || !formEndereco) {
                console.error('Erro: Elementos do modal ou gatilho não encontrados.');
                return; // Para o script se algo estiver faltando
            }

            // 2. Cria a instância do Modal Bootstrap
            const enderecoModal = new bootstrap.Modal(modalElement);

            // 3. Adiciona o "escutador" de clique no label "Escolher Endereco"
            triggerLabel.addEventListener('click', function(event) {
                
                event.preventDefault();
                
                // Abre o modal
                enderecoModal.show();
            });

            // 4. Lógica para quando o formulário for ENVIADO (Botão "Salvar endereço")
            formEndereco.addEventListener('submit', function(event) {
                event.preventDefault(); // Impede o recarregamento da página

                // (Aqui você faria a validação e envio dos dados para o backend)
                alert('Endereço salvo!');

                // AGORA sim, marcamos manualmente o rádio "Escolher Endereco de envio"
                document.getElementById('mudaEndereco').checked = true;
                

                // Esconde o modal
                enderecoModal.hide();
            });

         

        });