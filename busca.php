<?php
$titulopag = "Resultado da Busca";
include "header.php";
include "funcoes.php";

$termo = isset($_GET["produto"]) ? $_GET["produto"] : "";
$resultado = produtos_buscar($conexao, $termo);
?>

<section class="busca">
    <h2 class="titulo">Buscar Produtos</h2>
    <form action="busca.php" method="GET">
        <input type="text" name="produto" placeholder="Digite um produto" value="<?php echo $termo; ?>"
        onkeyup="usaAjax('buscaAjax.php?produto=' + this.value, 'resultbusca')">
        <button type="submit"> Buscar </button>
    </form>

    <div id="resultbusca"></div>
</section>

<section class="secao">
    <h2 class="titulo">Resultado para "<?php echo $termo; ?>"</h2>
    <?php if (mysqli_num_rows($resultado) == 0) { ?>
    <div class="vazio">
        <p>Nenhum produto encontrado.</p>
        <a href="produtos.php" class="botao">Ver todos os produtos</a>
    </div>

<?php } else { ?>

<div class="cards">
    <?php while ($p = mysqli_fetch_assoc($resultado)) { ?>
<div class="card">
<div class="imgcard"></div>
<div class="maincard">
    <h3><?php echo $p["nome"]; ?></h3>
<div class="precocard">R$ <?php echo formatar_preco($p["preco"]); ?></div>

<?php if ($p["estoque"] > 0) { ?>
    <p><strong>Estoque:</strong> <?php echo $p["estoque"]; ?></p>

<?php } else { ?>
    <p class="estoquezero">
        Fora de estoque
    </p>
<?php } ?>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</section>