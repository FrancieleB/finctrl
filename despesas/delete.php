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

$sql = "DELETE FROM despesas WHERE id=? AND usuario_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();

header("Location: read.php?sucesso=3");
exit;
