<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["clienteid"])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION["carrinho"])) {
    header("Location: carrinho.php");
    exit;
}

$titulopag = "Finalizar Compra";
include "header.php";

$mensagem = "";
$tipo = "";

$nome  = $_SESSION["carrinho"]["nome"];
$preco = $_SESSION["carrinho"]["preco"];

if (isset($_POST["finalizar"])) {
    $cliente  = (int) $_SESSION["clienteid"];
    $forma    = mysqli_real_escape_string($conexao, $_POST["formapag"] ?? "");
    $endereco = mysqli_real_escape_string($conexao, trim($_POST["endereco"] ?? ""));

if ($forma == "" || $endereco == "") {
    $mensagem = "Preencha todos os campos.";
    $tipo = "erro";

} else {
     try {
        mysqli_begin_transaction($conexao);

        $sql = "INSERT INTO pedidos(cliente_id,total,status)
                VALUES('$cliente','$preco','Pendente')";
        $pedido = mysqli_query($conexao, $sql);

if ($pedido) {
    $pedidoid = mysqli_insert_id($conexao);
    $sql = "INSERT INTO pagamentos(pedido_id,forma_pagamento,valor,status)
            VALUES('$pedidoid','$forma','$preco','Aguardando')";
    $pagamento = mysqli_query($conexao, $sql);

if ($pagamento) {
    $sql = "INSERT INTO entregas(pedido_id,endereco,status)
            VALUES('$pedidoid','$endereco','Preparando')";
    $entrega = mysqli_query($conexao, $sql);

if ($entrega) {
    mysqli_commit($conexao);
    $_SESSION["carrinho"] = [];
    $mensagem = "Pedido #" . $pedidoid . " realizado com sucesso!";
$tipo = "sucesso";
} else {
    mysqli_rollback($conexao);
    $mensagem = "Erro ao cadastrar a entrega.";
    $tipo = "erro";
                    }
} else {
    mysqli_rollback($conexao);
    $mensagem = "Erro ao cadastrar o pagamento.";
    $tipo = "erro";
                }
} else {
    mysqli_rollback($conexao);
    $mensagem = "Erro ao criar o pedido.";
    $tipo = "erro";
        }
} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conexao);
    $mensagem = "Erro ao processar o pedido. Tente novamente.";
    $tipo = "erro";
    error_log("Erro finalizar.php: " . $e->getMessage());
        }
    }
}
?>

<div class="final">

<?php if ($mensagem !== "") { ?>
<div class="aviso <?php echo $tipo === "sucesso" ? "sucesso" : "erro"; ?>">
    <?php echo $mensagem; ?>
</div>
<?php } ?>

<?php if ($tipo !== "sucesso") { ?>

<div class="finalresumo">
<h3 class="finaltitulo">
    Resumo do Pedido
</h3>

<div class="finalitem">
<div class="finalicone">
</div>

<div class="finalinfo">
    <span class="finalnome">
        <?php echo $nome; ?>
    </span>
    <span class="finalpreco">
        R$ <?php echo number_format($preco,2,",","."); ?>
    </span>
</div>
</div>

<div class="finaltotal">
    <span>Total</span>
    <span class="finalvalor">
        R$ <?php echo number_format($preco,2,",","."); ?>
    </span>
</div>
</div>

<form class="finalform" action="finalizar.php" method="POST">
<div class="finalbloco">
<h3 class="finaltitulo">
    Endereço de Entrega
</h3>

<label>Endereço completo</label>
<input type="text" name="endereco" placeholder="Rua, número, bairro, cidade — Estado" required
value="<?php if(isset($_POST['endereco'])){ echo $_POST['endereco']; } ?>">
</div>

<div class="finalbloco">
<h3 class="finaltitulo">
    Forma de Pagamento
</h3>

<div class="formapag">
    <label class="opcaopag">
    <input type="radio" name="formapag" value="PIX" required>
    <span class="cartaopag">
        PIX
    </span>
    </label>
</div>

<div class="finalbotoes">
    <button type="submit" name="finalizar" class="botao">
        Finalizar Compra
    </button>
</div>
</div>
</form>

<?php } else { ?>

<div class="finalbotoes">
    <a href="main.php" class="botao">
        Voltar para a loja
    </a>
</div>

<?php } ?>
</div>