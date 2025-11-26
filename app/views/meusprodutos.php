<?php
require_once __DIR__ . '/../model/Admin.php';
require_once __DIR__ . '/../core/user.php';
require_once __DIR__ . '/../model/Loja.php';
require_once __DIR__ . '/../model/Produto.php';

// Admin logado
$idAdmin = Auth::getAdminId();

if (!$idAdmin) {
    die("Admin não logado.");
}

// Buscar loja do admin
$lojaModel = new Loja();
$loja = $lojaModel->buscarPorAdminId($idAdmin);

$idLoja = $loja['id_loja'];

// Buscar produtos da loja
$produtoModel = new Produto();
$produtos = $produtoModel->buscarPorLoja($idLoja);

?>

<h3 class="text-center mt-3">Produtos da loja:
    <span class="text-primary"><?= htmlspecialchars($loja['nome_loja']) ?></span>
</h3>

<div class="overflow-y-auto" style="max-height:600px;">
    <table class="table table-striped table-hover table-bordered mt-3 text-center align-middle">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Cor</th>
                <th>Data de modificação</th>
                <th>Info</th>
                <th>Deletar</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nome']) ?></td>

                        <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>

                        <td><?= htmlspecialchars($p['cor']) ?></td>

                        <td><?= date('d/m/Y H:i', strtotime($p['data_att'])) ?></td>

                        <td>
                            <button
                                class="btn btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalInfoProduto"
                                data-id="<?= $p['id_produto'] ?>">
                                <i class="bi bi-list fs-3"></i>
                            </button>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-delete" data-id="<?= $p['id_produto'] ?>">
                                <i class="bi bi-trash fs-3"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Nenhum produto encontrado para esta loja.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modalInfoProduto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Informações do Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p>Todos a seguir dentro de label editavel para ser modificado no banco</p>
        nome
        preço
        se for pc puxa id_info do produto.php
        join na tabela produto_info usando o id_info
        puxa da tabela id_info os campos
        marca
        categoria
        ram
        armazenamento
        processador
        placa_mae
        placa_video
        fonte
        cor 
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>

    </div>
  </div>
</div>

<script>
    const modalBody = document.querySelector('#modalInfoProduto .modal-body');

    // 1. ABRE O MODAL E CARREGA O FORMULÁRIO
    document.querySelectorAll('button[data-bs-target="#modalInfoProduto"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border text-primary"></div><p>Carregando...</p></div>';

            fetch('../app/controller/BuscaProdutoController.php?id=' + id)
            .then(r => r.text())
            .then(html => {
                modalBody.innerHTML = html;
            })
            .catch(err => {
                modalBody.innerHTML = '<p class="text-danger">Erro ao carregar informações.</p>';
            });
        });
    });

    // 2. DELETAR PRODUTO (Botão da Tabela Principal)
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            if(!confirm("Tem certeza que deseja excluir este produto permanentemente?")) return;

            const id = this.getAttribute('data-id');
            const formData = new FormData();
            formData.append('id_produto', id);
            formData.append('action', 'delete');

            fetch('../app/controller/GerenciarProdutoController.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(res => {
                if(res.sucesso) {
                    alert("Produto deletado!");
                    location.reload();
                } else {
                    alert("Erro: " + res.erro);
                }
            });
        });
    });

    // 3. FUNÇÃO PARA SALVAR (Chamada pelo botão dentro do HTML carregado pelo PHP)
    function salvarAlteracoes() {
        const form = document.getElementById('form-editar-produto');
        const formData = new FormData(form);
        formData.append('action', 'update');
        formData.append('admin_id', <?= $idAdmin ?>);

        fetch('../app/controller/GerenciarProdutoController.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if(res.sucesso) {
                alert("Produto atualizado com sucesso!");
                location.reload(); // Recarrega a página para ver as mudanças
            } else {
                alert("Erro ao atualizar: " + res.erro);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Erro na requisição.");
        });
    }
</script>