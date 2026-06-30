<?php

function carrinho_obter() {
    if (!isset($_SESSION["carrinho"])) {
        $_SESSION["carrinho"] = [];
    }
    return $_SESSION["carrinho"];
}

function carrinho_vazio() {
    return empty($_SESSION["carrinho"]);
}

function carrinho_limpar() {
    $_SESSION["carrinho"] = [];
}

function carrinho_adicionar($produto) {
    $_SESSION["carrinho"] = $produto;
}

function carrinho_nome() {
    return $_SESSION["carrinho"]["nome"] ?? "";
}

function carrinho_preco() {
    return $_SESSION["carrinho"]["preco"] ?? 0;
}

function formatar_preco($valor) {
    return number_format($valor, 2, ",", ".");
}

function produto_tem_estoque($produto) {
    return $produto && $produto["quantidade"] > 0;
}

function produtos_listar($conexao) {
    return mysqli_query($conexao, "SELECT p.id, p.nome, p.descricao, p.preco, e.quantidade AS estoque
        FROM produtos p
        LEFT JOIN estoque e ON e.produto_id = p.id
        ORDER BY p.nome");
}

function produtos_buscar($conexao, $termo) {
    $termo = mysqli_real_escape_string($conexao, $termo);
    return mysqli_query($conexao, "SELECT p.id, p.nome, p.preco, e.quantidade AS estoque
        FROM produtos p
        LEFT JOIN estoque e ON e.produto_id = p.id
        WHERE p.nome LIKE '%$termo%'
        ORDER BY p.nome");
}

function produtos_buscar_ajax($conexao, $termo) {
    $termo = mysqli_real_escape_string($conexao, $termo);
    return mysqli_query($conexao, "SELECT * FROM produtos WHERE nome LIKE '$termo%'");
}

function cliente_logado() {
    return isset($_SESSION["clienteid"]);
}

function cliente_fazer_login($conexao, $email, $senha) {
    $email = mysqli_real_escape_string($conexao, $email);
    $senha = mysqli_real_escape_string($conexao, $senha);
    $res = mysqli_query($conexao, "SELECT * FROM clientes WHERE email='$email' AND senha='$senha'");
    if (mysqli_num_rows($res)) {
        return mysqli_fetch_assoc($res);
    }
    return false;
}

function cliente_salvar_sessao($cliente) {
    $_SESSION["clienteid"] = $cliente["id"];
    $_SESSION["nome"]      = $cliente["nome"];
}

function cliente_cadastrar($conexao, $nome, $email, $senha) {
    $nome  = mysqli_real_escape_string($conexao, $nome);
    $email = mysqli_real_escape_string($conexao, $email);
    $senha = mysqli_real_escape_string($conexao, $senha);
    if (mysqli_query($conexao, "INSERT INTO clientes (nome, email, senha) VALUES ('$nome', '$email', '$senha')")) {
        return true;
    }
    return mysqli_error($conexao);
}

function redirecionar($url) {
    header("Location: $url");
    exit;
}