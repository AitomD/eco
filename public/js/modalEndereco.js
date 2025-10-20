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
                
                // event.preventDefault() é a CHAVE!
                // Impede que o rádio 'mudaEndereco' seja marcado IMEDIATAMENTE.
                // O rádio só será marcado se o usuário "Salvar" no modal.
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
                
                // (Opcional) Atualizar o texto do label com o novo endereço
                // const spanTexto = triggerLabel.querySelector('.text-ml-dark');
                // if(spanTexto) {
                //    spanTexto.textContent = 'Enviar em: ' + document.getElementById('rua').value;
                // }

                // Esconde o modal
                enderecoModal.hide();
            });

            // 5. Lógica para "Cancelar" ou "X"
            // Se o usuário fechar o modal sem salvar (clicando em "Cancelar",
            // no "X" ou fora do modal), nenhuma ação é necessária.
            // O event.preventDefault() no passo 3 já garantiu que a seleção
            // de rádio original (ex: "Enviar no meu endereço") foi mantida.

        });