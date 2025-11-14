/**
 * Inicializa o(s) controlador(es) do modal de privacidade.
 */
document.addEventListener("DOMContentLoaded", () => {
  // Seleciona os elementos da DOM
  const modalElement = document.getElementById("privacidadeModal");
  const btnAbrir = document.getElementById("btnAbrirPrivacidade");

  // Verificação de robustez: só continua se os elementos existirem
  if (modalElement && btnAbrir) {
    // Cria uma instância do Modal do Bootstrap
    const privacidadeModal = new bootstrap.Modal(modalElement, {
      keyboard: true, // Permite fechar com 'Esc'
    });

    // Adiciona o 'ouvinte' de clique ao botão
    btnAbrir.addEventListener("click", () => {
      // Ação: Exibe o modal
      privacidadeModal.show();
    });
  } else {
    // Log de erro para o desenvolvedor
    console.warn(
      "Elemento do modal de privacidade ou botão de gatilho não encontrado."
    );
  }
});
