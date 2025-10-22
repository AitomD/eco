<style>
    /* Faz o item da lista ser clicável */
    .list-group-item-action {
        cursor: pointer;
    }
</style>

<main class="container py-4">

    <section class="col-lg-8 offset-lg-2 bg-white rounded shadow-sm p-4">

        <h5 class="fw-bold mb-4 text-ml-title">Adicione um seguro</h5>

        <div class="list-group list-group-flush">

            <label for="seguro-12" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">

                <div class="d-flex align-items-center">
                    <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-12">
                    <span class="fw-bold text-ml-dark" data-seguro='12'>12 meses de Garantia estendida</span>
                </div>

                <div class="text-end">
                    <span class="fs-5 fw-bold text-ml-dark">R$ 289</span>
                    <div>
                        <span class="text-primary fw-semibold">12x R$ 24,08</span>
                        <span class="text-primary" style="font-size: 0.9em;"> sem juros</span>
                    </div>
                </div>
            </label>

            <label for="seguro-18" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-18">
                    <span class="fw-bold text-ml-dark" data-seguro="18">18 meses de Garantia estendida</span>
                </div>
                <div class="text-end">
                    <span class="fs-5 fw-bold text-ml-dark">R$ 463</span>
                    <div>
                        <span class="text-primary fw-semibold">12x R$ 36,17</span>
                        <span class="text-primary" style="font-size: 0.9em;"> sem juros</span>
                    </div>
                </div>
            </label>

            <label for="seguro-24" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-24">
                    <span class="fw-bold text-ml-dark" data-seguro="24">24 meses de Garantia estendida</span>
                </div>
                <div class="text-end">
                    <span class="fs-5 fw-bold text-ml-dark">R$ 499</span>
                    <div>
                        <span class="text-primary fw-semibold">12x R$ 41,58</span>
                        <span class="text-primary" style="font-size: 0.9em;"> sem juros</span>
                    </div>
                </div>
            </label>

            <label for="seguro-none" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                <div class="d-flex align-items-center">
                    <input class="form-check-input me-3" type="radio" name="seguro-option" id="seguro-none" checked>
                    <span class="fw-bold text-ml-dark">Sem seguro</span>
                </div>
            </label>
        </div>
        <div class="text-center mt-5">
            <a href="index.php?url=paginaRetirada"><button class="btn-product py-2">Prosseguir</button></a>
        </div>
    </section>


</main>