<?php
// Debe ser lo PRIMERO en el archivo, sin espacios antes
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/config/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || $_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$query = $conn->prepare("SELECT id, contra, tipo FROM usuarios WHERE id = ?");
$query->bind_param("s", $data['nControl']);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($data['contra'], $user['contra'])) {
    // Configurar sesión
    $_SESSION['usuario'] = $user['id'];
    $_SESSION['tipo'] = $user['tipo'];

    // Configurar cookie manualmente
    setcookie(session_name(), session_id(), 0, '/', '', false, true);
    
    echo json_encode([
        'success' => true,
        'userType' => $user['tipo'],
        'userId' => $user['id'], // Nuevo campo

        'mensaje' => '✅ Inicio de sesión exitoso'
    ]);
} else {
    echo json_encode(['error' => '❌ Credenciales incorrectas']);
}
?>
