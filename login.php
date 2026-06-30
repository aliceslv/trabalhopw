<?php
$titulopag = "Login";
include "header.php";
include "funcoes.php";

$mensagem = "";

if (isset($_POST["email"])) {
$cliente = cliente_fazer_login($conexao, $_POST["email"], $_POST["senha"]);
if ($cliente) {
cliente_salvar_sessao($cliente);
redirecionar("main.php");
}
$mensagem = "E-mail ou senha inválidos!";
}
?>
<section class="secaoform">
    <h2 class="titulo">Entrar</h2>
<?php if ($mensagem != "") { ?>
    <p class="aviso"><?php echo $mensagem; ?></p>
<?php } ?>
    <form class="cardform" action="login.php" method="POST">
        <label>E-mail:</label>
        <input type="email" name="email" placeholder="Digite seu e-mail" required>
        <label>Senha:</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        <button type="submit" class="botao">Entrar</button>
    </form>
</section>