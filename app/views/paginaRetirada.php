<style>
    /* Estilos para os cards de opção de entrega.
           Usamos 'has' para mudar a borda quando o input dentro dele estiver checado.
        */
    .shipping-option {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: border-color 0.2s;
    }

    /* Quando o input dentro da label está checado, muda a borda da label */
    .shipping-option:has(input:checked) {
        border-color: #3483fa;
        /* Azul do Mercado Livre */
        border-width: 2px;
        padding: calc(1.25rem - 1px);
        /* Compensa a borda mais grossa */
    }

    /* Garante que o input de rádio real fique alinhado */
    .shipping-option .form-check-input {
        margin-top: 0.25em;
    }

    span,
    h5,
    h6 {
        color: var(--black);
    }
</style>

<body>

    <main class="container py-4 mt-3 bg-white my-3">
        <div class="row g-4">
            <div class="col-lg-7">


                <div class="bg-white rounded shadow p-4">
                    <h5 class="fw-bold mb-4 text-ml-dark">Escolha a forma de entrega</h5>
                    <label for="mudaEndereco" class="shipping-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="entrega-option" id="mudaEndereco" checked>
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Alterar meu endereço</span>
                                </div>
                            </div>

                        </div>
                    </label>

                    <label for="envEndereco" class="shipping-option">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="entrega-option" id="envEndereco" checked>
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Enviar no meu endereço</span>
                                    <span class="text-muted" style="font-size: 0.9em;">Terra Boa - CEP 87240000</span>
                                </div>
                            </div>
                            <span class="fw-bold text-primary mx-3">Grátis</span>
                        </div>
                    </label>

                    <label for="retiraAgencia" class="shipping-option mb-0">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <input class="form-check-input me-3" type="radio" name="entrega-option" id="retiraAgencia">
                                <div>
                                    <span class="fw-bold text-ml-dark d-block">Retirada na Agência HAFTECH</span>
                                </div>
                            </div>
                            <span class="fw-bold text-primary mx-3">Grátis</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white rounded shadow p-4" style="position: sticky; top: 20px;">
                    <h6 class="fw-bold mb-3">Resumo da compra</h6>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Produto</span>
                        <span class="text-muted">R$ 3.330</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Frete</span>
                        <span class="fw-bold text-primary">GRÁTIS</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span class="text-ml-dark">Total</span>
                        <span class="text-ml-dark">R$ 3.330</span>
                    </div>
                </div>
                <div class="text-center mt-4 w-100">
                    <button class="btn-product ">Continuar</button>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="enderecoModal" tabindex="-1" aria-labelledby="enderecoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="enderecoModalLabel">Alterar meu endereço</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-novo-endereco">
                        <div class="row g-3">
                           
                            <div class="col-md-6"><label for="cep" class="form-label">CEP</label><input type="text" class="form-control" id="cep" placeholder="00000-000" required></div>
                            <div class="col-md-9"><label for="rua" class="form-label">Rua / Logradouro</label><input type="text" class="form-control" id="rua" required></div>
                            <div class="col-md-3"><label for="numero" class="form-label">Número</label><input type="text" class="form-control" id="numero" required></div>
                            <div class="col-md-6"><label for="complemento" class="form-label">Complemento <span class="text-muted">(Opcional)</span></label><input type="text" class="form-control" id="complemento"></div>
                            <div class="col-md-6"><label for="bairro" class="form-label">Bairro</label><input type="text" class="form-control" id="bairro" required></div>
                            <div class="col-md-8"><label for="cidade" class="form-label">Cidade</label><input type="text" class="form-control" id="cidade" required></div>
                            <div class="col-md-4"><label for="estado" class="form-label">Estado</label><input type="text" class="form-control" id="estado" required></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" form="form-novo-endereco">Salvar endereço</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>