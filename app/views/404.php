<!-- Página de Erro 404 -->
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="text-center text-white">
        <div class="error-404" data-aos="zoom-in" data-aos-duration="1000">
            <h1 class="display-1 fw-bold mb-4" style="font-size: 8rem;">404</h1>
            <h2 class="h4 mb-4">Página não encontrada</h2>
            <p class="lead mb-4">
                Ops! A página que você está procurando não existe ou foi movida.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="index.php?url=home" class="btn btn-light btn-lg px-4" role="button">
                    <i class="bi bi-house-door me-2"></i>
                    Voltar ao Início
                </a>
                <a href="javascript:history.back()" class="btn btn-outline-light btn-lg px-4" role="button">
                    <i class="bi bi-arrow-left me-2"></i>
                    Voltar
                </a>
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
