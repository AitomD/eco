<?php


if (!$isLoggedIn) {
    header('Location: ?url=login'); // Redireciona para o login
    exit; // Para a execução
}
// Se o script chegou aqui, o usuário ESTÁ logado e $userData existe.
// ---------------------------------------------------------------------------
?>

<style>

    /* Estilizando os links da sidebar para parecerem com o ML */
    .list-group-item-action {
        color: var(--black); /* Cor de texto padrão (preto/cinza) */
        font-weight: 500;
        border: none; /* Remove bordas padrão */
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .list-group-item-action:hover,
    .list-group-item-action:focus {
        background-color: #f5f5f5; /* Um cinza leve no hover */
        color: var(--pmain); /* Azul do ML no hover */
    }

    .list-group-item-action.active-ml {
        background-color: #fff;
        color: var(--pmain);
        font-weight: 700;
        border-left: 3px solid var(--pmain);
        box-shadow: 3px 0 5px -5px rgba(0, 0, 0, 0.1);
    }
</style>

<main class="container py-4">

    <div class="row g-4">

        <div class="col-lg-3">
            <div class="list-group shadow-sm rounded">
                <a href="?url=meuperfil" class="list-group-item list-group-item-action active-ml d-flex align-items-center">
                    <i class="bi bi-person-fill fs-5 me-3"></i>
                    Meus dados
                </a>

                <a href="?url=minhascompras" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-box-seam fs-5 me-3"></i>
                    Minhas compras
                </a>

                <?php if ($userData['is_admin'] == 1): ?>
                    <a href="?url=minhasvendas" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="bi bi-shop fs-5 me-3"></i>
                        Minhas vendas
                    </a>
                <?php endif; ?>

                <a href="?url=favoritos" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="bi bi-heart fs-5 me-3"></i>
                    Favoritos
                </a>

                <a href="?url=logout" class="list-group-item list-group-item-action d-flex align-items-center text-danger">
                    <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                    Sair
                </a>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm">

                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-person-circle fs-1 me-3 text-muted"></i>
                    <div class="lh-1">
                        <h2 class="h4 mb-0 fw-bold">Olá, <?php echo htmlspecialchars($userData['name']); ?></h2>
                        <span class="text-muted">Aqui você pode gerenciar suas informações.</span>
                    </div>
                </div>

                <hr class="mb-4">

                <h3 class="h5 mb-3">Informações da conta</h3>

                <dl class="row">
                    <dt class="col-sm-3 text-muted">Nome:</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($userData['name']); ?></dd>

                    <dt class="col-sm-3 text-muted">Email:</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($userData['email']); ?></dd>

                    <dt class="col-sm-3 text-muted">Nascimento:</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($userData['data_nascimento']); ?></dd>
                </dl>

                <a href="?url=editardados" class="btn-product  text-decoration-none mt-3">
                    <i class="bi bi-pencil-fill me-2"></i>
                    Editar dados
                </a>

            </div>
        </div>

    </div></main>