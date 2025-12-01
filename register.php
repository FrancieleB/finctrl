<?php
session_start();

include "conexao.php";

$erro = "";

/* PROCESSAR FORMULÁRIO */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome  = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    /* Evita cadastro duplicado */
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $erro = "Este e-mail já está cadastrado.";
    } else {
        $query = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $query->bind_param("sss", $nome, $email, $senha);

        if ($query->execute()) {
            header("Location: login.php?cadastrado=1");
            exit;
        } else {
            $erro = "Erro ao cadastrar. Tente novamente.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINCTRL | Criar Conta</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header>
    <nav class="menu">
        <div class="logo">
            <img src="img/logo.png" alt="Logo FINCTRL">
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Início</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="hero">
        <h2>Criar nova conta</h2>
        <p>Registre-se para começar a controlar seus gastos.</p>
    </section>

    <section class="form-section">
        <form method="POST" class="form-container">

            <?php if (!empty($erro)): ?>
                <p style="color:red;"><?= $erro ?></p>
            <?php endif; ?>

            <div class="form-group">
                <label>Nome:</label>
                <input type="text" name="nome" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>

            <button class="btn" type="submit">Criar Conta</button>
        </form>
    </section>
</main>

<?php include "templates/footer.php"; ?>
</body>
</html>

