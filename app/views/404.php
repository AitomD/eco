<!-- Página de Erro 404 -->
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center ">
    <div class="row align-items-center justify-content-center w-100 px-3">

        <!-- Texto à esquerda -->
        <div class="col-12 col-md-6 text-center text-md-start text-light">
            <h1 class="fw-bold mb-2" style="font-size: 4rem;">404.</h1>
            <h2 class="h5 mb-3 text-light">Essa é um erro.</h2>
            <p class="mb-4 lead text-secondary" style="max-width: 500px;">
               A página que você está tentando acessar não existe<br> É tudo o que sabemos.
            </p>

            <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                <a href="index.php?url=home" class="btn btn-outline-light btn-lg px-4" role="button">
                    <i class="bi bi-house-door me-2"></i>
                    Página Inicial
                </a>
                <a href="javascript:history.back()" class="btn btn-light btn-lg px-4" role="button">
                    <i class="bi bi-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>

        <!-- Imagem à direita -->
        <div class="col-12 col-md-6 text-center mt-5 mt-md-0" data-aos="zoom-in" data-aos-duration="1000">
            <div class="error-404">
                <img src="../public/img/erro404site.png" alt="Erro 404 - Mascote" class="img-fluid w-100" style="max-width: 350px;">
            </div>
        </div>

    </div>
</div>

<style>
    .error-404 {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .btn-light:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .btn-outline-light:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(255,255,255,0.2);
    }
    
    /* Responsividade para telas menores */
    @media (max-width: 768px) {
        .error-404 h1 {
            font-size: 5rem !important;
        }
        .d-flex.gap-3 {
            flex-direction: column;
            gap: 1rem !important;
        }
        .btn {
            width: 100%;
            max-width: 300px;
        }
    }
    
    /* Animação adicional */
    .error-404 h1 {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
