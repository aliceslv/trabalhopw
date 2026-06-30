<?php
$titulopag = "Cosméticos";
include 'header.php';
?>

<section class="destaque">
    <div class="conteudodest">
        <h1>Cladaly Cosméticos</h1>
        <p>Realce sua beleza com produtos de qualidade.</p>
    </div>
</section>

<section class="busca">
    <h2 class="titulo">Buscar Produtos</h2>
    <form action="busca.php" method="GET">
        <input type="text" name="produto" placeholder="Digite um produto"
            onkeyup="usaAjax('buscaAjax.php?produto=' + this.value, 'resultado_busca')">

        <button type="submit">Buscar</button>
    </form>
    <div id="resultbusca" class="buscaresultado"></div>
</section>

