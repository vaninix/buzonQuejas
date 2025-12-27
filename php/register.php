<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Manejar solicitud OPTIONS para CORS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/config/db.php';

// Obtener datos JSON
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si se recibieron datos
if (!$data) {
    echo json_encode(["error" => "No se recibieron datos", "debug" => file_get_contents("php://input")]);
    exit();
}

// Verificar campos obligatorios
$requiredFields = ['nControl', 'nombre', 'primApe', 'contra', 'carrera'];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        echo json_encode(["error" => "❗ El campo $field es requerido"]);
        exit();
    }
}

// Validar número de control (8 dígitos)
if (!preg_match('/^\d{8}$/', $data['nControl'])) {
    echo json_encode(["error" => "❌ El número de control debe tener 8 dígitos"]);
    exit();
}

// Verificar si usuario ya existe
$checkQuery = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$checkQuery->bind_param("s", $data['nControl']);
$checkQuery->execute();
$checkQuery->store_result();

if ($checkQuery->num_rows > 0) {
    echo json_encode(["error" => "⚠️ Este usuario ya está registrado"]);
    exit();
}

// Hash de contraseña
$hashedPassword = password_hash($data['contra'], PASSWORD_DEFAULT);

// Insertar usuario principal
$insertUser = $conn->prepare("INSERT INTO usuarios (id, nombre, primApe, segApe, contra, tipo) VALUES (?, ?, ?, ?, ?, 'estudiante')");
$insertUser->bind_param("sssss", 
    $data['nControl'],
    $data['nombre'],
    $data['primApe'],
    $data['segApe'],
    $hashedPassword
);

if (!$insertUser->execute()) {
    echo json_encode(["error" => "❌ Error al registrar usuario"]);
    exit();
}

// Insertar datos de estudiante
$insertStudent = $conn->prepare("INSERT INTO estudiantes (id, carrera) VALUES (?, ?)");
$insertStudent->bind_param("ss", $data['nControl'], $data['carrera']);

if ($insertStudent->execute()) {
    echo json_encode(["success" => true, "mensaje" => "✅ Registro exitoso. Ahora puedes iniciar sesión"]);
} else {
    echo json_encode(["error" => "❌ Error al registrar datos académicos"]);
}

// Cerrar conexiones
$checkQuery->close();
$insertUser->close();
$insertStudent->close();
$conn->close();