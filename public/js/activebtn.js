// Seleciona todos os botões
const buttons = document.querySelectorAll(".op-btn");

buttons.forEach((btn) => {
  btn.addEventListener("click", () => {
    // Remove a classe 'active' de todos os botões
    buttons.forEach((b) => b.classList.remove("active"));

    // Adiciona a classe 'active' no botão clicado
    btn.classList.add("active");
  });
});
