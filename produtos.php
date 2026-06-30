<?php
$titulopag = "Cladaly — Produtos";
include "header.php";
include "funcoes.php";

$produtos = produtos_listar($conexao);

?>
<div class="banner">
    <h1>Produtos</h1>
</div>

<section class="secao">
<?php if (mysqli_num_rows($produtos) == 0) { ?>
<div class="vazio">
    <p>Nenhum produto encontrado.</p>
</div>

<?php } else { ?>
<div class="cards">
<?php while ($p = mysqli_fetch_assoc($produtos)) { ?>
    <div class="card">
    <div class="imgcard"></div>
    <div class="maincard">
    <h3><?php echo $p["nome"]; ?></h3>
    <p><?php echo $p["descricao"]; ?></p>
    <div class="precocard">R$ <?php echo formatar_preco($p["preco"]); ?></div>
<?php if ($p["estoque"] > 0) { ?>
    <div class="cliquescard">
    <button class="botao botaosz" onclick="usaAjax('adicionaCar.php?id=<?php echo $p['id']; ?>','msg<?php echo $p['id']; ?>')">
    Adicionar
</button>
</div>
<div id="msg<?php echo $p["id"]; ?>" class="msgcarrinho"></div>
<?php } else { ?>
<p class="estoquezero">Fora de estoque</p>
<?php } ?>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</section>