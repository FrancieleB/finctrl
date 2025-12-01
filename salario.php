<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include "conexao.php";

$usuario_id = $_SESSION['usuario_id'];
$mensagem = "";

/* Buscar salário atual */
$stmt = $conn->prepare("SELECT salario FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($salario_atual);
$stmt->fetch();
$stmt->close();

/* Atualizar salário */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entrada = preg_replace('/[^\d,.-]/', '', $_POST['salario']);
    $entrada = str_replace('.', '', $entrada);
    $entrada = str_replace(',', '.', $entrada);
    $novo_salario = floatval($entrada);

    $stmt = $conn->prepare("UPDATE usuarios SET salario = ? WHERE id = ?");
    $stmt->bind_param("di", $novo_salario, $usuario_id);

    if ($stmt->execute()) {
        $mensagem = "Salário atualizado com sucesso!";
        $salario_atual = $novo_salario;
    } else {
        $mensagem = "Erro ao atualizar salário.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>FINCTRL | Salário</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <nav class="menu">
        <div class="logo">
            <img src="img/logo.png" alt="Logo FINCTRL">
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">Relatório</a></li>
            <li><a href="despesas/create.php">Registrar Gasto</a></li>
            <li><a href="categorias/read.php">Categorias</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="hero">
        <h2>Atualizar Salário</h2>
        <p>Insira seu salário mensal para atualizar seu resumo financeiro.</p>
    </section>

    <section class="form-section">
        <form method="POST" class="form-container">

            <?php if (!empty($mensagem)): ?>
                <p style="color:#2C7A7B; text-align:center; font-weight:600;">
                    <?= htmlspecialchars($mensagem) ?>
                </p>
            <?php endif; ?>

            <div class="form-group">
                <label for="salario">Seu salário atual:</label>
                <input
                    type="text"
                    name="salario"
                    id="salario"
                    value="<?= number_format((float)$salario_atual, 2, ',', '.') ?>"
                    required
                >
            </div>

            <button type="submit" class="btn">Salvar</button>

            <div style="text-align:center;">
                <a href="dashboard.php" class="link-discreto">← Voltar ao Dashboard</a>
            </div>
        </form>
    </section>
</main>

<?php include "templates/footer.php"; ?>



