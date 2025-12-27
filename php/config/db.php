<?php
$host = "localhost";
$usuario = "root"; 
$password = "";
$baseDatos = "buzonVue";

$conn = new mysqli($host, $usuario, $password, $baseDatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
