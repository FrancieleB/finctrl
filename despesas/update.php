<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../conexao.php";

if (!isset($_GET['id'])) {
    header("Location: read.php");
    exit;
}

$id = $_GET['id'];
$usuario_id = $_SESSION['usuario_id'];


$sql = "SELECT * FROM despesas WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
$gasto = $result->fetch_assoc();

if (!$gasto) {
    echo "Gasto não encontrado!";
    exit;
}


$cats = $conn->query("SELECT id, nome FROM categorias ORDER BY nome ASC");


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $descricao    = $_POST['descricao'];
    $valor        = $_POST['valor'];
    $categoria_id = $_POST['categoria_id'];
    $data         = $_POST['data'];

    $sql = "UPDATE despesas 
            SET descricao = ?, valor = ?, categoria_id = ?, data_gasto = ?
            WHERE id = ? AND usuario_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssii", $descricao, $valor, $categoria_id, $data, $id, $usuario_id);

    if ($stmt->execute()) {
        header("Location: read.php?sucesso=2");
        exit;
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Gasto</title>
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

<main class="form-section">

    <div class="form-wrapper">
        <h2>Editar Gasto</h2>

        <form method="POST" class="form-container">

            <div class="form-group">
                <label>Descrição:</label>
                <input type="text" name="descricao" value="<?= $gasto['descricao'] ?>" required>
            </div>

            <div class="form-group">
                <label>Valor (R$):</label>
                <input type="number" step="0.01" name="valor" value="<?= $gasto['valor'] ?>" required>
            </div>

            <div class="form-group">
                <label>Categoria:</label>
                <select name="categoria_id" required>
                    <?php while ($c = $cats->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>" <?= ($c['id'] == $gasto['categoria_id']) ? 'selected' : '' ?>>
                            <?= $c['nome'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Data:</label>
                <input type="date" name="data" value="<?= $gasto['data_gasto'] ?>" required>
            </div>

            <button type="submit" class="btn">Atualizar</button>
            <a href="read.php" class="btn voltar">Voltar</a>

        </form>
    </div>

</main>

<?php include "../templates/footer.php"; ?>