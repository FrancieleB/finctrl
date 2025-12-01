<?php
session_start();
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>FINCTRL</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/finctrl/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background:#2C7A7B;">
  <div class="container">
    <a class="navbar-brand" href="/finctrl/dashboard.php">FINCTRL</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_name'])): ?>
          <li class="nav-item"><a class="nav-link" href="/finctrl/dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="/finctrl/despesas/list.php">Despesas</a></li>
          <li class="nav-item"><a class="nav-link" href="/finctrl/categorias/list.php">Categorias</a></li>
          <li class="nav-item"><a class="nav-link" href="/finctrl/logout.php">Sair (<?=htmlspecialchars($_SESSION['user_name'])?>)</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/finctrl/index.php">Entrar</a></li>
          <li class="nav-item"><a class="nav-link" href="/finctrl/register.php">Cadastrar</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
