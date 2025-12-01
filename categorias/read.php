<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../conexao.php";

$cats = $conn->query("SELECT * FROM categorias ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Categorias</title>
    <link rel="stylesheet" href="/finctrl/css/style.css">
</head>

<body>

<header>
    <nav class="menu">
        <div class="logo">
            <img src="../img/logo.png">
        </div>

        <ul class="nav-links">
            <li><a href="../dashboard.php">Relatório</a></li>
            <li><a href="../despesas/create.php">Registrar Gastos</a></li>
            <li><a href="create.php">Categoria</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main class="form-section">

    <div class="form-wrapper">
        <h2>Lista de Categorias</h2>

        <a href="create.php" class="btn" style="margin-bottom:20px;">+ Nova Categoria</a>

        <table border="1" width="100%" class="table">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>

            <?php while ($c = $cats->fetch_assoc()): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= $c['nome'] ?></td>
                    <td>
                        <a href="update.php?id=<?= $c['id'] ?>" class="btn">Editar</a>
                        <a href="delete.php?id=<?= $c['id'] ?>" class="btn voltar" 
                           onclick="return confirm('Tem certeza que deseja excluir?')">
                           Excluir
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <a href="../dashboard.php" class="btn voltar" style="margin-top:20px;">Voltar</a>

    </div>

</main>

<?php include "../templates/footer.php"; ?>

