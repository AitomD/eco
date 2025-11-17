   /**
         * Inicializa o(s) controlador(es) do modal de termos.
         * Envolvemos em 'DOMContentLoaded' para garantir que o HTML foi carregado.
         */
        document.addEventListener('DOMContentLoaded', () => {
            
            // Seleciona os elementos da DOM
            const modalElement = document.getElementById('termosModal');
            const btnAbrir = document.getElementById('btnAbrirModal');

            // Verificação de robustez: só continua se os elementos existirem
            if (modalElement && btnAbrir) {
                
                // Cria uma instância do Modal do Bootstrap
                // Isso nos permite controlar o modal via JS (ex: termosModal.show())
                const termosModal = new bootstrap.Modal(modalElement, {
                    keyboard: true // Permite fechar com 'Esc' (boa prática)
                });

                // Adiciona o 'ouvinte' de clique ao botão
                btnAbrir.addEventListener('click', () => {
                    // Ação: Exibe o modal
                    termosModal.show();
                });

            } else {
                // Log de erro para o desenvolvedor, caso um elemento não seja encontrado
                console.warn('Elemento do modal ou botão de gatilho não encontrado.');
            }
        });