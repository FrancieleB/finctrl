<?php
session_start();

/* Se já estiver logado, manda para o dashboard */
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}

include "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT id, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $user = $resultado->fetch_assoc();

        if (password_verify($senha, $user["senha"])) {
            $_SESSION['usuario_id'] = $user["id"];
            header("Location: dashboard.php");
            exit;
        }
    }

    $erro_login = true;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FINCTRL | Login</title>
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
            <li><a href="about.html">Sobre</a></li>
            <li><a class="active" href="login.php">Login</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="hero">
        <h2>Acesse sua conta</h2>
        <p>Entre para registrar e acompanhar seus gastos.</p>
    </section>

    <section class="form-section">
        <form method="POST" class="form-container">

            <?php if (!empty($erro_login)): ?>
                <p style="color:red;margin-bottom:10px;">
                     Usuário ou senha incorretos.
                </p>
            <?php endif; ?>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>

            <button type="submit" class="btn">Entrar</button>

            <p style="margin-top:15px;">
                Ainda não tem conta?
                <a href="register.php">Criar conta</a>
            </p>
        </form>
    </section>
</main>

<?php include "templates/footer.php"; ?>

</body>
</html>
