<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'conexao.php';

if (!isset($titulopag)) {
    $titulopag = "Cladaly Cosméticos";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php echo $titulopag ?> </title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <script src="ajax.js"></script>
</head>
<body>

<header>
<div class="logo">
    Cladaly
</div>
<nav>
    <a href="main.php"> Início </a>
    <a href="produtos.php"> Produtos </a>
    <?php if (isset($_SESSION["clienteid"])) { ?>
    <a href="carrinho.php">
    Carrinho
    </a>
    <a href="admin.php">
    Admin
    </a>
    <span class="usuario">
        <?php echo $_SESSION["nome"]; ?>
    </span>
    <a href="logout.php" class="sair">
    Sair
    </a>

    <?php } else { ?>
    <a href="cadastro.php">Cadastro</a>
    <a href="login.php">Login</a>
    <a href="carrinho.php">Carrinho</a>
    <?php } ?>
        
</nav>
</header>
