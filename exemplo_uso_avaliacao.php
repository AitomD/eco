<?php
/**
 * Exemplo de uso da classe Avaliacao
 * 
 * Este arquivo demonstra como usar as funcionalidades da classe Avaliacao
 * para gerenciar avaliações de produtos em um e-commerce.
 */

require_once '../app/model/avaliações.php';

// Criar uma instância da classe Avaliacao
$avaliacao = new Avaliacao();

echo "<h2>Exemplos de uso da classe Avaliacao</h2>\n\n";

// 1. Adicionar uma nova avaliação
echo "<h3>1. Adicionar uma nova avaliação</h3>\n";
$id_user = 1;        // ID do usuário
$id_produto = 1;     // ID do produto
$nota = 5;           // Nota de 1 a 5
$comentario = "Produto excelente! Superou minhas expectativas.";

$resultado = $avaliacao->adicionarAvaliacao($id_user, $id_produto, $nota, $comentario);
if ($resultado) {
    echo "✅ Avaliação adicionada com sucesso! ID da avaliação: {$resultado}\n";
} else {
    echo "❌ Erro ao adicionar avaliação ou usuário já avaliou este produto.\n";
}

// 2. Verificar se um usuário já avaliou um produto
echo "\n<h3>2. Verificar se usuário já avaliou o produto</h3>\n";
$jaAvaliou = $avaliacao->jaAvaliou($id_user, $id_produto);
echo $jaAvaliou ? "✅ Usuário já avaliou este produto.\n" : "❌ Usuário ainda não avaliou este produto.\n";

// 3. Buscar avaliações de um produto
echo "\n<h3>3. Buscar avaliações de um produto</h3>\n";
$avaliacoesProduto = $avaliacao->obterAvaliacoesProduto($id_produto, 5); // Buscar até 5 avaliações
if ($avaliacoesProduto) {
    echo "📋 Avaliações encontradas: " . count($avaliacoesProduto) . "\n";
    foreach ($avaliacoesProduto as $index => $av) {
        echo "  {$index}. {$av['nome_usuario']} - {$av['nota']} estrelas - {$av['data_avaliacao']}\n";
        if (!empty($av['comentario'])) {
            echo "     Comentário: {$av['comentario']}\n";
        }
    }
} else {
    echo "❌ Nenhuma avaliação encontrada.\n";
}

// 4. Calcular média das avaliações
echo "\n<h3>4. Calcular média das avaliações</h3>\n";
$mediaAvaliacoes = $avaliacao->calcularMediaAvaliacoes($id_produto);
if ($mediaAvaliacoes) {
    echo "📊 Estatísticas do produto:\n";
    echo "  • Média: {$mediaAvaliacoes['media']}/5\n";
    echo "  • Total de avaliações: {$mediaAvaliacoes['total']}\n";
    echo "  • Distribuição de notas:\n";
    for ($i = 5; $i >= 1; $i--) {
        echo "    {$i} estrelas: {$mediaAvaliacoes['distribuicao'][$i]} avaliações\n";
    }
} else {
    echo "❌ Erro ao calcular estatísticas.\n";
}

// 5. Gerar HTML de estrelas
echo "\n<h3>5. Gerar HTML de estrelas</h3>\n";
$htmlEstrelas = Avaliacao::gerarEstrelas(4.5, 'text-warning');
echo "🌟 HTML das estrelas (nota 4.5): {$htmlEstrelas}\n";

// 6. Buscar avaliações de um usuário
echo "\n<h3>6. Buscar avaliações de um usuário</h3>\n";
$avaliacoesUsuario = $avaliacao->obterAvaliacoesUsuario($id_user);
if ($avaliacoesUsuario) {
    echo "👤 Avaliações do usuário {$id_user}: " . count($avaliacoesUsuario) . "\n";
    foreach ($avaliacoesUsuario as $index => $av) {
        echo "  {$index}. {$av['nome_produto']} - {$av['nota']} estrelas - {$av['data_avaliacao']}\n";
    }
} else {
    echo "❌ Usuário ainda não fez avaliações.\n";
}

// 7. Buscar produtos mais bem avaliados
echo "\n<h3>7. Produtos mais bem avaliados</h3>\n";
$produtosMaisAvaliados = $avaliacao->obterProdutosMaisAvaliados(5);
if ($produtosMaisAvaliados) {
    echo "🏆 Top produtos mais bem avaliados:\n";
    foreach ($produtosMaisAvaliados as $index => $produto) {
        echo "  " . ($index + 1) . ". {$produto['nome']} - Média: {$produto['media_notas']}/5 ({$produto['total_avaliacoes']} avaliações)\n";
    }
} else {
    echo "❌ Nenhum produto encontrado.\n";
}

echo "\n<hr>\n";
echo "<p><strong>💡 Dicas de uso:</strong></p>\n";
echo "<ul>\n";
echo "<li>Sempre validar os dados antes de chamar os métodos da classe</li>\n";
echo "<li>Verificar se o usuário já avaliou o produto antes de permitir nova avaliação</li>\n";
echo "<li>Usar try-catch ao instanciar a classe para tratar erros de conexão</li>\n";
echo "<li>Implementar paginação ao buscar muitas avaliações</li>\n";
echo "<li>Usar o método gerarEstrelas() para manter consistência visual</li>\n";
echo "</ul>\n";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Exemplo de uso da classe Avaliacao</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        h3 { color: #666; margin-top: 30px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
        .estrelas { color: #ffc107; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <h1>Sistema de Avaliações - Classe Avaliacao</h1>
    <p>Esta classe fornece um sistema completo de avaliações para produtos em e-commerce.</p>
    
    <h3>Funcionalidades Principais:</h3>
    <ul>
        <li>✅ Adicionar, atualizar e remover avaliações</li>
        <li>📊 Calcular médias e estatísticas</li>
        <li>🔍 Buscar avaliações por produto ou usuário</li>
        <li>🌟 Gerar HTML de estrelas</li>
        <li>🏆 Ranking de produtos mais bem avaliados</li>
        <li>🛡️ Validação e segurança</li>
    </ul>
</body>
</html>