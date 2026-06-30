<?php
$titulopag = "Carrinho";
include "header.php";
include "funcoes.php";

if (isset($_GET["limpar"])) {
    carrinho_limpar();
    redirecionar("carrinho.php");
}

$nome  = carrinho_nome();
$preco = carrinho_preco();
?>

<div class="banner bannerprod">
<h1> Meu Carrinho </h1>
</div>

<section class="secao">

<?php if (carrinho_vazio()) { ?>
<div class="vazio">
    <p>Seu carrinho está vazio.</p>
    </a>
</div>

<?php } else { ?>
<div class="carrinho">
    <div class="card">
    <div class="imgcard">
    </div>
    <div class="maincard">
    <h3><?php echo $nome; ?></h3>
    <div class="precocard">
    R$ <?php echo formatar_preco($preco); ?>
    </div>
    <a href="carrinho.php?limpar=1" class="botao botaolinha botaosz">
    Remover
    </a>
    </div>
</div>

    <?php if (cliente_logado()) { ?>
    <a href="finalizar.php" class="botao">
    Finalizar Compra
    </a>

    <?php } else { ?>
    <p class="aviso">
    Faça login para finalizar a compra.
    </p>
    <a href="login.php" class="botao">
    Entrar
    </a>
    <?php } ?>
</div>
<?php } ?>
</section>
</body>
</html>