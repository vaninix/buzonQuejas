<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['autenticado' => false]);
    exit;
}

echo json_encode([
    'autenticado' => true,
    'usuario' => $_SESSION['usuario'],
    'tipo' => $_SESSION['tipo']
]);
?>