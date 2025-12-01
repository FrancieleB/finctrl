<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../conexao.php";

$usuario_id = $_SESSION['usuario_id'];

$cats = $conn->query("SELECT id, nome FROM categorias ORDER BY nome ASC");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $descricao     = $_POST['descricao'];
    $valor         = floatval($_POST['valor']);
    $categoria_id  = intval($_POST['categoria_id']);
    $data          = $_POST['data'];

    $sql = "INSERT INTO despesas (usuario_id, categoria_id, descricao, valor, data_gasto)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $usuario_id, $categoria_id, $descricao, $valor, $data);

    if ($stmt->execute()) {
        header("Location: read.php?sucesso=1");
        exit;
    } else {
        echo "Erro ao salvar gasto: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Registrar novo gasto</title>
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
        <li><a href="create.php" class="active">Registra Gastos</a></li>
        <li><a href="../categorias/read.php">Categorias</a></li>
        <li><a href="../logout.php">Sair</a></li>
    </ul>
    </nav>
</header>

<main class="form-section">
    <div class="form-wrapper">

        <h2>Cadastrar novo gasto</h2>

        <form method="POST" class="form-container">

            <div class="form-group">
                <label>Descrição:</label>
                <input type="text" name="descricao" required placeholder="Ex: Mercado, Luz, Uber">
            </div>

            <div class="form-group">
                <label>Valor (R$):</label>
                <input type="number" step="0.01" name="valor" required placeholder="Ex: 75.90">
            </div>

            <div class="form-group">
                <label>Categoria:</label>
                <select name="categoria_id" required>
                    <option value="">Selecione uma categoria</option>
                    <?php while ($c = $cats->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['nome'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Data:</label>
                <input type="date" name="data" required>
            </div>

            <button type="submit" class="btn">Salvar</button>
            <a href="../dashboard.php" class="btn voltar">Voltar</a>

        </form>

    </div>
</main>

<?php include "../templates/footer.php"; ?>

