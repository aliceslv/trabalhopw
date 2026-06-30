<?php
$titulopag = "Cadastro";
include "header.php";
include "funcoes.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$resultado = cliente_cadastrar($conexao, $_POST["nome"], $_POST["email"], $_POST["senha"]);

if ($resultado === true) {
$mensagem = "Cadastro realizado com sucesso!";
} elseif (strpos($resultado, "Duplicate") !== false) {
$mensagem = "Este e-mail já está cadastrado.";
} else {
$mensagem = "Erro ao cadastrar: " . $resultado;
}
}
?>
<section class="secaoform">
    <h2 class="titulo">Criar Conta</h2>
<?php if ($mensagem != "") { ?>
    <p class="aviso"><?php echo $mensagem; ?></p>
<?php } ?>

<form class="cardform" action="cadastro.php" method="POST">
    <label>Nome:</label>
    <input type="text" name="nome" placeholder="Digite seu nome" required>
    <label>E-mail:</label>
    <input type="email" name="email" placeholder="Digite seu e-mail" required>
    <label>Senha:</label>
    <input type="password" name="senha" placeholder="Digite sua senha" required>
    <button type="submit" class="botao">Cadastrar</button>
</form>
</section>
</body>
</html>