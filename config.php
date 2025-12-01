<?php

session_start();

$DB_HOST = '127.0.0.1';
$DB_NAME = 'finctrl';
$DB_USER = 'root';
$DB_PASS = ''; 

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (Exception $e) {
    die('Erro ao conectar ao BD: ' . $e->getMessage());
}

/**
 * exige autenticação.
 */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /finctrl/index.php');
        exit;
    }
}
