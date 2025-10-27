<?php
require_once '../app/model/Loja.php';
require_once '../app/model/EnderecoLoja.php';
require_once '../app/model/DonoLoja.php';
require_once '../app/model/EstoqueLoja.php';

// Puxa os dados da classe loja
$lojaModel = new Loja();
$loja = $lojaModel->buscarLojaPorId(1); // substitua 1 pelo ID da loja desejada

// Puxar dados do endereço da loja
$enderecoModel = new EnderecoLoja();
$end = $enderecoModel->buscarEnderecoPorId($loja['id_endereco']);

// Puxa o nome do dono da loja
$donoModel = new DonoLoja();
$dono = $donoModel->buscarDonoPorIdAdmin($loja['id_admin']);

//Puxa o estoque de cada loja
$estoqueModel = new EstoqueLoja();
$produtos = $estoqueModel->buscarEstoquePorLoja(1);
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main>
<!-- Informaçoes da loja e dono -->
  <div class="container-fluid mx-auto">
    <div class="info-loja container mt-4">
      <h4>🏪 Loja: <?= htmlspecialchars($loja['nome']) ?></h4>
      <h4>👤 Proprietário: <?= htmlspecialchars($dono['nome_dono']) ?></h4>
      <h4>💼 CNPJ: <?= htmlspecialchars($loja['cnpj']) ?></h4>
      <h4>📍 Endereço:
        <?= htmlspecialchars($end['endereco']) ?>, <?= htmlspecialchars($end['cidade']) ?> / <?= htmlspecialchars($end['estado']) ?>
      </h4>
    </div>
  </div>

  <div class="container-fluid mx-auto">
    <div class="container">
      <h2 class="text-center">Estoque de produtos:</h2>
        <?php
        foreach ($produtos as $produto) {
    echo "Produto: {$produto['nome_produto']} ({$produto['cor']})<br>";
    echo "Preço: R$ {$produto['preco']}<br>";
    echo "Quantidade em estoque: {$produto['quantidade']}<br>";
    echo "Total: {$produto['total']}<br>";
    echo "Última atualização estoque: {$produto['data_estoque']}<br>";
    echo "Última atualização produto: {$produto['data_atualizacao_produto']}<br>";
    echo "<hr>";
}
?>
    </div>
  </div>
</main>