<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../conexao.php";
$usuario_id = $_SESSION['usuario_id'];

$sql = "
SELECT d.id, d.descricao, d.valor, d.data_gasto,
       c.nome AS categoria
FROM despesas d
LEFT JOIN categorias c ON c.id = d.categoria_id
WHERE d.usuario_id = ?
ORDER BY d.data_gasto DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>FINCTRL | Gastos</title>
    <link rel="stylesheet" href="/finctrl/css/style.css">
</head>

<body>

<header>
    <nav class="menu">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo FINCTRL">
        </div>
        <ul class="nav-links">
            <li><a href="../dashboard.php">Relatório</a></li>
            <li><a href="create.php">Registrar Gasto</a></li>
            <li><a href="../logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main class="about-container">

    <h2 style="text-align:center; margin-bottom:20px;">Meus Gastos</h2>

    <?php if (isset($_GET['sucesso'])): ?>
        <p style="color:green; text-align:center;">
            ✔ Operação realizada com sucesso!
        </p>
    <?php endif; ?>

    <div style="text-align:center;">
        <a href="create.php" class="btn">+ Novo Gasto</a>
    </div>

    <br>

    <table style="width:100%; border-collapse: collapse;">
        <tr style="background:#2C7A7B; color:white;">
            <th style="padding:10px;">Descrição</th>
            <th>Valor</th>
            <th>Categoria</th>
            <th>Data</th>
            <th>Ações</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr style="background:white; border-bottom:1px solid #ccc;">
            <td style="padding:10px;"><?= $row['descricao'] ?></td>
            <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
            <td><?= $row['categoria'] ?? 'Sem categoria' ?></td>
            <td><?= date("d/m/Y", strtotime($row['data_gasto'])) ?></td>

            <td>
                <a href="update.php?id=<?= $row['id'] ?>" class="btn">Editar</a>
                <a href="delete.php?id=<?= $row['id'] ?>" 
                   class="btn voltar" 
                   onclick="return confirm('Excluir gasto?')">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</main>

<?php include "../templates/footer.php"; ?>

