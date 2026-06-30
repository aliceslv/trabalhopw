<?php
session_start();
include "conexao.php";
include "funcoes.php";

carrinho_obter();

if (isset($_REQUEST["id"])) {

    if (!cliente_logado()) {
        echo "<span class='erro'>Faça login para comprar.</span>";
        exit;
    }

    $id      = mysqli_real_escape_string($conexao, $_REQUEST["id"]);
    $sql     = "SELECT p.nome, p.preco, e.quantidade
                FROM produtos p
                INNER JOIN estoque e ON e.produto_id = p.id
                WHERE p.id = '$id'";
    $produto = mysqli_fetch_assoc(mysqli_query($conexao, $sql));

    if (produto_tem_estoque($produto)) {
        carrinho_adicionar($produto);
        echo "<span class='sucesso'>Produto adicionado ao carrinho!</span>";
    } else {
        echo "<span class='erro'>Produto indisponível.</span>";
    }
}
?>