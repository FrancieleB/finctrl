<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include "conexao.php";

$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT nome, salario FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome, $salario);
$stmt->fetch();
$stmt->close();

/* Total do mês */
$stmt = $conn->prepare("
    SELECT SUM(valor)
    FROM despesas
    WHERE usuario_id = ? 
      AND MONTH(data_gasto) = MONTH(CURDATE())
      AND YEAR(data_gasto) = YEAR(CURDATE())
");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($total_mes);
$stmt->fetch();
$stmt->close();

$total_mes = $total_mes ?? 0;
$saldo = ($salario ?? 0) - $total_mes;

/* Gastos por categoria  */
$sql = "
SELECT 
    COALESCE(c.nome, 'Sem categoria') AS nome,
    SUM(d.valor) AS total
FROM despesas d
LEFT JOIN categorias c ON c.id = d.categoria_id
WHERE d.usuario_id = ?
GROUP BY nome
ORDER BY total DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$categorias = [];
$totais = [];
while ($row = $result->fetch_assoc()) {
    $categorias[] = $row['nome'];
    $totais[] = $row['total'];
}
$stmt->close();

/* Lista despesas do mês atual */
$sql_despesas = "
SELECT d.data_gasto, d.valor, COALESCE(c.nome, 'Sem categoria') AS categoria
FROM despesas d
LEFT JOIN categorias c ON c.id = d.categoria_id
WHERE d.usuario_id = ?
  AND MONTH(d.data_gasto) = MONTH(CURDATE())
  AND YEAR(d.data_gasto) = YEAR(CURDATE())
ORDER BY d.data_gasto DESC
";
$stmt = $conn->prepare($sql_despesas);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result_despesas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>FINCTRL | Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<header>
    <nav class="menu">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Início</a></li>
            <li><a href="dashboard.php" class="active">Relatório</a></li>
            <li><a href="despesas/create.php">Registrar Gastos</a></li>
            <li><a href="categorias/read.php">Categorias</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main class="form-section">
    <div class="form-container">
        <h2>Olá, <?= htmlspecialchars($nome) ?>!</h2>
        <p style="text-align:center; margin-bottom: 2rem;">Aqui está seu resumo financeiro.</p>

        <div class="form-group">
            <label>Total gasto no mês:</label>
            <p class="value">R$ <?= number_format($total_mes, 2, ',', '.') ?></p>
        </div>

        <div class="form-group">
            <label>Seu salário:</label>
            <?php if ($salario !== null): ?>
                <p class="value">R$ <?= number_format($salario, 2, ',', '.') ?></p>
            <?php else: ?>
                <p class="value" style="color:#999;">Salário não informado</p>
            <?php endif; ?>
            <small><a href="salario.php" class="link-discreto">Alterar salário</a></small>
        </div>

        <div class="form-group">
            <label>Saldo disponível:</label>
            <p class="value <?= $saldo > 0 ? 'positivo' : 'negativo' ?>">R$ <?= number_format($saldo, 2, ',', '.') ?></p>
        </div>

        <div class="tabela-wrapper">
            <h3>Gastos adicionados no mês</h3>
            <?php if ($result_despesas->num_rows > 0): ?>
            <table class="tabela-gastos">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Categoria</th>
                        <th>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_despesas->fetch_assoc()): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($row['data_gasto'])) ?></td>
                            <td><?= htmlspecialchars($row['categoria']) ?></td>
                            <td>R$ <?= number_format($row['valor'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="text-align:center; color:#666;">Nenhum gasto registrado neste mês.</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Distribuição dos Gastos por Categoria:</label>
            <div class="chart-wrapper">
                <canvas id="grafico"></canvas>
            </div>
        </div>
    </div>
</main>

<script>
const ctx = document.getElementById('grafico');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($categorias) ?>,
        datasets: [{
            data: <?= json_encode($totais) ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 1.5
    }
});
</script>

<?php include "templates/footer.php"; ?>
</body>
</html>

