<?php
$titulopag = "Relatório de Pedidos";
include "header.php";

$sql = "SELECT
    p.id,
    DATE_FORMAT(p.data_pedido, '%d/%m/%Y') AS data_formatada,
    p.total,
    p.status,
    c.nome AS cliente,
    pg.forma_pagamento,
    e.status AS status_entrega
FROM pedidos p
INNER JOIN clientes c ON c.id = p.cliente_id
LEFT JOIN pagamentos pg ON pg.pedido_id = p.id
LEFT JOIN entregas e ON e.pedido_id = p.id
ORDER BY p.data_pedido DESC";

$pedidos = mysqli_query($conexao, $sql);
$lista = [];
$receita = 0;

while ($linha = mysqli_fetch_assoc($pedidos)) {
    $linha["forma_pagamento"] = $linha["forma_pagamento"] != "" ? $linha["forma_pagamento"] : "—";
    $linha["status_entrega"]  = $linha["status_entrega"]  != "" ? $linha["status_entrega"]  : "—";
    $lista[] = $linha;
    $receita += $linha["total"];
}

$total_pedidos = count($lista);
?>

<div class="banner bannerprod">
<h1>
    Relatório de Pedidos
</h1>
</div>

<section class="secao">

<div class="admintabela">
<?php if ($total_pedidos == 0) { ?>
<div class="vazio">
    <p>Nenhum pedido encontrado.</p>
</div>

<?php } else { ?>
<table class="tabela">
    <thead>
    <tr>
    <th>#</th>
    <th>Data</th>
    <th>Cliente</th>
    <th>Pagamento</th>
    <th>Total</th>
    <th>Pedido</th>
    <th>Entrega</th>
    </tr>
    </thead>
<tbody>
    <?php foreach ($lista as $p) { ?>
    <tr>
    <td><?php echo $p["id"]; ?></td>
    <td><?php echo $p["data_formatada"]; ?></td>
    <td><?php echo $p["cliente"]; ?></td>
    <td><?php echo $p["forma_pagamento"]; ?></td>
    <td>R$ <?php echo number_format($p["total"], 2, ",", "."); ?></td>
    <td>
        <span class="badge badge-<?php echo $p["status"]; ?>">
        <?php echo $p["status"]; ?>
        </span>
    </td>
    <td>
        <span class="badge badge-<?php echo $p["status_entrega"]; ?>">
        <?php echo $p["status_entrega"]; ?>
        </span>
    </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
<?php } ?>

<div class="relvoltar">
<a href="admin.php" class="botao botaolinha">
    Voltar ao Admin
</a>
</div>
    
</div>
</section>
</body>
</html>