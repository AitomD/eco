<?php
/**
 * Arquivo de funções utilitárias para o sistema
 * 
 * Este arquivo contém funções auxiliares que podem ser usadas
 * em todo o sistema para evitar duplicação de código.
 */

/**
 * Inicia a sessão de forma segura
 * Verifica se já existe uma sessão ativa antes de tentar iniciar
 * 
 * @return bool true se a sessão foi iniciada ou já estava ativa
 */
function iniciarSessaoSegura() {
    if (session_status() === PHP_SESSION_NONE) {
        return session_start();
    }
    return true;
}

/**
 * Verifica se o usuário está logado
 * 
 * @return bool true se o usuário está logado
 */
function usuarioLogado() {
    iniciarSessaoSegura();
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Obtém o ID do usuário logado
 * 
 * @return int|null ID do usuário ou null se não estiver logado
 */
function obterIdUsuario() {
    return usuarioLogado() ? $_SESSION['user_id'] : null;
}

/**
 * Define uma mensagem de sucesso na sessão
 * 
 * @param string $mensagem Mensagem de sucesso
 */
function definirMensagemSucesso($mensagem) {
    iniciarSessaoSegura();
    $_SESSION['sucesso'] = $mensagem;
}

/**
 * Define uma mensagem de erro na sessão
 * 
 * @param string $mensagem Mensagem de erro
 */
function definirMensagemErro($mensagem) {
    iniciarSessaoSegura();
    $_SESSION['erro'] = $mensagem;
}

/**
 * Obtém e remove uma mensagem de sucesso da sessão
 * 
 * @return string|null Mensagem de sucesso ou null
 */
function obterMensagemSucesso() {
    iniciarSessaoSegura();
    if (isset($_SESSION['sucesso'])) {
        $mensagem = $_SESSION['sucesso'];
        unset($_SESSION['sucesso']);
        return $mensagem;
    }
    return null;
}

/**
 * Obtém e remove uma mensagem de erro da sessão
 * 
 * @return string|null Mensagem de erro ou null
 */
function obterMensagemErro() {
    iniciarSessaoSegura();
    if (isset($_SESSION['erro'])) {
        $mensagem = $_SESSION['erro'];
        unset($_SESSION['erro']);
        return $mensagem;
    }
    return null;
}

/**
 * Redireciona para uma URL específica
 * 
 * @param string $url URL de destino
 * @param bool $permanente Se o redirecionamento é permanente (301)
 */
function redirecionar($url, $permanente = false) {
    if ($permanente) {
        header("HTTP/1.1 301 Moved Permanently");
    }
    header("Location: " . $url);
    exit;
}

/**
 * Escapa HTML para exibição segura
 * 
 * @param string $texto Texto a ser escapado
 * @return string Texto escapado
 */
function escaparHtml($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

/**
 * Valida se um número está dentro de um range
 * 
 * @param int|float $numero Número a ser validado
 * @param int|float $min Valor mínimo
 * @param int|float $max Valor máximo
 * @return bool true se está no range
 */
function validarRange($numero, $min, $max) {
    return $numero >= $min && $numero <= $max;
}

/**
 * Formata preço para exibição em Real brasileiro
 * 
 * @param float $valor Valor a ser formatado
 * @return string Valor formatado
 */
function formatarPreco($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

/**
 * Formata data brasileira
 * 
 * @param string $data Data no formato Y-m-d H:i:s
 * @param bool $incluirHora Se deve incluir hora
 * @return string Data formatada
 */
function formatarDataBr($data, $incluirHora = false) {
    $formato = $incluirHora ? 'd/m/Y \à\s H:i' : 'd/m/Y';
    return date($formato, strtotime($data));
}
?>