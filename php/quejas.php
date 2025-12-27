<?php
session_start();
header('Content-Type: application/json');

// Verificar sesión activa
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Debes iniciar sesión primero']);
    exit;
}

if ($_SESSION['tipo'] !== 'estudiante') {
    echo json_encode(['error' => 'Acceso no autorizado para tu tipo de usuario']);
    exit;
}

require_once __DIR__ . '/config/db.php';

// Obtener datos del request
$data = json_decode(file_get_contents('php://input'), true);

// Verificar método y datos
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$data) {
    echo json_encode(['error' => 'Método no permitido o datos inválidos']);
    exit;
}

// Validar acción
if (!isset($data['action'])) {
    echo json_encode(['error' => 'Acción no especificada']);
    exit;
}

switch ($data['action']) {
    case 'enviar_queja':
        if (empty(trim($data['queja']))) {
            echo json_encode(['error' => 'El texto de la queja no puede estar vacío']);
            exit;
        }

        // Obtener la carrera del estudiante desde la tabla estudiantes
        $query_carrera = $conn->prepare("SELECT carrera FROM estudiantes WHERE id = ?");
        $query_carrera->bind_param("s", $_SESSION['usuario']);
        $query_carrera->execute();
        $resultado = $query_carrera->get_result();
        
        if ($resultado->num_rows === 0) {
            echo json_encode(['error' => 'No se encontró información del estudiante']);
            $query_carrera->close();
            exit;
        }
        
        $fila = $resultado->fetch_assoc();
        $carrera = $fila['carrera'];
        $query_carrera->close();

        // Insertar queja con carrera del estudiante
        $stmt = $conn->prepare("INSERT INTO quejas (mensaje, id_estudiante, carrera) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $data['queja'], $_SESSION['usuario'], $carrera);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'mensaje' => 'Queja registrada exitosamente']);
        } else {
            echo json_encode(['error' => 'Error al guardar la queja: ' . $conn->error]);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['error' => 'Acción no reconocida: ' . $data['action']]);
}

$conn->close();
?>