<?php
// Conexión a la base de datos
require_once __DIR__ . '/config/db.php';

// Datos del admin
$id = "12345678"; 
$nombre = "Administrador";
$primApe = "Gestion";
$segApe = "Proyectos";
$contra = "Qwerty*25"; 
$tipo = "admin";

// Hashear contraseña
$hashedPassword = password_hash($contra, PASSWORD_DEFAULT);

// Insertar admin
$stmt = $conn->prepare("INSERT INTO usuarios (id, nombre, primApe, segApe, contra, tipo) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $id, $nombre, $primApe, $segApe, $hashedPassword, $tipo);

if ($stmt->execute()) {
    echo "Admin registrado correctamente";
} else {
    echo "Error al registrar admin: " . $stmt->error;
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
