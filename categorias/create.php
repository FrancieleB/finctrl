<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];

    $stmt = $conn->prepare("INSERT INTO categorias (nome) VALUES (?)");
    $stmt->bind_param("s", $nome);

    if ($stmt->execute()) {
        header("Location: read.php?sucesso=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Categoria</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <nav class="menu">
        <div class="logo"><img src="../img/logo.png" alt="Logo FINCTRL"></div>
        <ul class="nav-links">
            <li><a href="../dashboard.php">Relatório</a></li>
            <li><a href="../despesas/create.php">Registrar Gastos</a></li>
            <li><a href="../categorias/read.php">Categorias</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main class="form-section">
    <div class="form-wrapper">
        <h2>Nova Categoria</h2>

        <form method="POST" class="form-container">
            <div class="form-group">
                <label>Nome da categoria:</label>
                <input type="text" name="nome" required>
            </div>

            <button type="submit" class="btn">Salvar</button>
            <a href="read.php" class="btn voltar">Voltar</a>
        </form>
    </div>
</main>




