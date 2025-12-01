<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../conexao.php";

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT nome FROM categorias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome);
$stmt->fetch();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $novo_nome = $_POST["nome"];
    $stmt = $conn->prepare("UPDATE categorias SET nome=? WHERE id=?");
    $stmt->bind_param("si", $novo_nome, $id);
    $stmt->execute();
    header("Location: read.php?editado=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <link rel="stylesheet" href="/finctrl/css/style.css">
</head>
<body>

<header>
    <nav class="menu">
        <div class="logo"><img src="../img/logo.png" alt="Logo FINCTRL"></div>
        <ul class="nav-links">
            <li><a href="read.php">Categorias</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="form-section">
        <div class="form-wrapper">
            <h2>Editar Categoria</h2>
            <form method="POST" class="form-container">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                </div>
                <button type="submit" class="btn">Salvar</button>
                <a href="read.php" class="btn voltar">Cancelar</a>
            </form>
        </div>
    </section>
</main>



