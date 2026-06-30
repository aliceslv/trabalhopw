<?php
$titulopag = "Admin";
include "header.php";

$msg = "";

if (isset($_GET["deletar"])) {
    $id = $_GET["deletar"];
    mysqli_query($conexao, "DELETE FROM estoque WHERE produto_id='$id'");
    mysqli_query($conexao, "DELETE FROM produtos WHERE id='$id'");
    header("Location: admin.php?ok=deletado");
    exit;
}

if (isset($_POST["salvar"])) {
    $nome      = mysqli_real_escape_string($conexao, trim($_POST["nome"]));
    $descricao = mysqli_real_escape_string($conexao, trim($_POST["descricao"]));
    $preco     = $_POST["preco"];
    $cat       = $_POST["categoria_id"];
    $qtd       = $_POST["quantidade"];
    $forn      = $_POST["fornecedor_id"] != "" ? $_POST["fornecedor_id"] : "NULL";
    $id        = $_POST["id"] ?? "";

    if ($id != "") {
        mysqli_query($conexao, "UPDATE produtos
            SET nome='$nome', descricao='$descricao', preco='$preco',
                categoria_id='$cat', fornecedor_id=$forn
            WHERE id='$id'");
        mysqli_query($conexao, "UPDATE estoque SET quantidade='$qtd' WHERE produto_id='$id'");
        header("Location: admin.php?ok=editado");
    } else {
        mysqli_query($conexao, "INSERT INTO produtos (nome, descricao, preco, categoria_id, fornecedor_id)
            VALUES ('$nome','$descricao','$preco','$cat',$forn)");
        $novoid = mysqli_insert_id($conexao);
        mysqli_query($conexao, "INSERT INTO estoque (produto_id, quantidade) VALUES ('$novoid','$qtd')");
        header("Location: admin.php?ok=criado");
    }
    exit;
}

$mensagens = ["criado" => "Produto criado!", "editado" => "Produto atualizado!", "deletado" => "Produto removido!"];
if (isset($_GET["ok"]) && isset($mensagens[$_GET["ok"]])) {
    $msg = $mensagens[$_GET["ok"]];
}

$editando = "";
if (isset($_GET["editar"])) {
    $id = $_GET["editar"];
    $res = mysqli_query($conexao, "SELECT p.*, e.quantidade FROM produtos p
        LEFT JOIN estoque e ON e.produto_id = p.id WHERE p.id='$id'");
    $editando = mysqli_fetch_assoc($res);
}

$categorias_res   = mysqli_query($conexao, "SELECT * FROM categorias ORDER BY nome");
$fornecedores_res = mysqli_query($conexao, "SELECT * FROM fornecedores ORDER BY nome");

$res = mysqli_query($conexao, "SELECT p.id, p.nome, p.preco, p.fornecedor_id,
    c.nome AS categoria, f.nome AS fornecedor, e.quantidade AS estoque
    FROM produtos p
    INNER JOIN categorias c ON c.id = p.categoria_id
    LEFT JOIN fornecedores f ON f.id = p.fornecedor_id
    LEFT JOIN estoque e ON e.produto_id = p.id
    ORDER BY p.nome");
?>

<div class="banner bannerprod">
<h1>
    Administração
</h1>
</div>

<section class="secao">

<?php if ($msg != "") { ?>
<p class="avisosuc">
    <?php echo $msg; ?>
</p>
<?php } ?>

<div class="adminform">
<h3 class="admintitulo">
    <?php echo $editando
    ? 'Editar Produto'
    : 'Novo Produto'; ?>
</h3>

<form action="admin.php" method="POST">
<?php if ($editando) { ?>
    <input type="hidden" name="id" value="<?php echo $editando["id"]; ?>">
<?php } ?>

<div class="admincampos">
<div class="admincampo">
    <label>Nome</label>
    <input type="text" name="nome" required value="<?php echo $editando["nome"] ?? ""; ?>">
</div>

<div class="admincampo">
    <label>Descrição</label>
    <input type="text" name="descricao" required value="<?php echo $editando["descricao"] ?? ""; ?>">
</div>

<div class="admincampo">
    <label>Preço</label>
    <input type="number" step="0.01" name="preco" required value="<?php echo $editando["preco"] ?? ""; ?>">
</div>

<div class="admincampo">
    <label>Estoque</label>
    <input type="number" name="quantidade" min="0" required value="<?php echo $editando["quantidade"] ?? "0"; ?>">
</div>

<div class="admincampo">
    <label>Categoria</label>
    <select name="categoria_id" required>
    <?php while ($c = mysqli_fetch_assoc($categorias_res)) { ?>
    <option value="<?php echo $c["id"]; ?>"
        <?php echo ($editando && $editando["categoria_id"] == $c["id"]) ? "selected" : ""; ?>>
        <?php echo $c["nome"]; ?>
    </option>
    <?php } ?>
    </select>
</div>

<div class="admincampo">
    <label>Fornecedor</label>
    <select name="fornecedor_id">
    <option value="">Nenhum</option>
        <?php while ($f = mysqli_fetch_assoc($fornecedores_res)) { ?>
    <option value="<?php echo $f["id"]; ?>"
        <?php echo ($editando && $editando["fornecedor_id"] == $f["id"]) ? "selected" : ""; ?>>
        <?php echo $f["nome"]; ?>
    </option>
    <?php } ?>
    </select>
</div>
</div>
    <br>
    <button type="submit" name="salvar" class="botao">
        Salvar
    </button>
    <?php if ($editando) { ?>
        <a href="admin.php" class="botao botaolinha">Cancelar</a>
    <?php } ?>
</form>
</div>

<div class="admintabela">
<div class="admintabelatopo">
    <h3 class="admintitulo">
        Produtos Cadastrados
    </h3>
    <a href="relatorio.php" class="botao">
        Ver Relatório de Pedidos
    </a>
</div>

<?php if (mysqli_num_rows($res) == 0) { ?>
    <div class="vazio">
    <p>Nenhum produto cadastrado.</p>
    </div>
<?php } else { ?>
<table class="tabela">
<thead>
<tr>
<th>#</th>
<th>Nome</th>
<th>Categoria</th>
<th>Fornecedor</th>
<th>Preço</th>
<th>Estoque</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
    <?php while ($p = mysqli_fetch_assoc($res)) {
        $fornecedor = $p["fornecedor"] ?? "—";
        $estoque    = $p["estoque"] ?? 0;
    ?>
    <tr>
    <td><?php echo $p["id"]; ?></td>
    <td><?php echo $p["nome"]; ?></td>
    <td><?php echo $p["categoria"]; ?></td>
    <td><?php echo $fornecedor; ?></td>
    <td>R$ <?php echo number_format($p["preco"], 2, ",", "."); ?></td>
    <td>
    <?php if ($estoque == 0) { ?>
    <span class="badge badge-Pendente"><?php echo $estoque; ?></span>
    <?php } else { ?>
        <?php echo $estoque; ?>
    <?php } ?>
    </td>
    <td>
    <a href="admin.php?editar=<?php echo $p["id"]; ?>" class="botao botaolinha">
    Editar
    </a>
    <a href="admin.php?deletar=<?php echo $p["id"]; ?>" class="botao botaolinha"
        onclick="return confirm('Remover este produto?')">
    Remover
    </a>
    </td>
    </tr>
<?php } ?>
</tbody>
</table>
<?php } ?>
</div>
</section>
</body>
</html>