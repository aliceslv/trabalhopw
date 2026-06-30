<?php
include "conexao.php";
include "funcoes.php";

if (isset($_REQUEST["produto"])) {
$resultado = produtos_buscar_ajax($conexao, $_REQUEST["produto"]);
while ($linha = mysqli_fetch_assoc($resultado)) {
echo "<a href='produto.php?id=" . $linha["id"] . "'>" . $linha["nome"] . "</a><br>";
}
}
?>