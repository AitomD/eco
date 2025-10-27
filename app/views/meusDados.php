   <div class="tab-pane fade show active" id="pane-meus-dados" role="tabpanel" aria-labelledby="link-meus-dados" tabindex="0">

                    <div class="bg-white p-4 p-md-5 rounded shadow-sm">
                        <!--MEUS DADOS-->
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-person-circle fs-1 me-3 " style="color:var(--pmain);"></i>
                            <div class="lh-1">
                                <h2 class="h4 mb-0 fw-bold">Olá, <?php echo htmlspecialchars($userData['nome']); ?></h2>
                                <span class="text-muted" style="color:var(--black);">Aqui você pode gerenciar suas informações.</span>
                            </div>
                        </div>

                        <hr class="mb-4" style="color:var(--black);">
                        <h3 class="h5 mb-3">Informações da conta</h3>

                        <dl class="row">
                            <dt class="col-sm-3">NOME:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($userData['nome']); ?></dd>

                            <dt class="col-sm-3">EMAIL:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($userData['email']); ?></dd>

                            <dt class="col-sm-3">NASCIMENTO:</dt>
                            <dd class="col-sm-9"><?php echo htmlspecialchars($userData['data_nascimento']); ?></dd>

                            <hr class="my-3">
                            <h3 class="h5 mb-3" style="color:var(--pmain);">Meu Endereço</h3>

                            <dt class="col-sm-3 ">ENDEREÇO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['endereco']); ?></dd>

                            <dt class="col-sm-3 ">CEP:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['cep']); ?></dd>

                            <dt class="col-sm-3 ">COMPLEMENTO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['complemento']); ?></dd>

                            <dt class="col-sm-3 ">BAIRRO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['bairro']); ?></dd>

                            <dt class="col-sm-3 ">CIDADE:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['cidade']); ?></dd>

                            <dt class="col-sm-3 ">ESTADO:</dt>
                            <dd class="col-sm-9 "><?php echo htmlspecialchars($userData['estado']); ?></dd>
                        </dl>

                        <a href="#" class="btn-product text-decoration-none mt-3" data-bs-toggle="modal" data-bs-target="#modalEditarDados">
                            <i class="bi bi-pencil-fill me-2"></i>
                            Editar dados
                        </a>

                    </div>
                </div>
                 <div class="modal p-4 fade" id="modalEditarDados" tabindex="-1" aria-labelledby="modalEditarDadosLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarDadosLabel">Editar Informações Pessoais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="formAtualizarDados">
                    <div class="modal-body">
                        <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>">

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($userData['nome']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?php echo htmlspecialchars($userData['data_nascimento']); ?>">
                        </div>

                        <hr>
                        <p class="text-muted">Deixe os campos de senha vazios se não quiser alterar.</p>
                        <div class="mb-3">
                            <label for="senha_nova" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="senha_nova" name="senha_nova">
                        </div>
                        <div class="mb-3">
                            <label for="senha_confirmar" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="senha_confirmar" name="senha_confirmar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-product">Salvar Alterações</button>
                        <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>