<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../conexao.php";

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: read.php?removido=1");
exit;
