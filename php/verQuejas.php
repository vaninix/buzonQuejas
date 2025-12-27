<?php
session_start();
header('Content-Type: application/json');

// Verificar sesión activa
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'Debes iniciar sesión primero']);
    exit;
}

require_once __DIR__ . '/config/db.php';

if ($conn->connect_error) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Manejar GET para obtener quejas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tipo_usuario = $_SESSION['tipo'];
    $carrera = isset($_GET['carrera']) ? $conn->real_escape_string($_GET['carrera']) : '';
    $estado = isset($_GET['estado']) ? $conn->real_escape_string($_GET['estado']) : '';
    
    $query = "SELECT id, mensaje, carrera, estado FROM quejas";
    $conditions = [];
    
    // Filtros para estudiantes
    if ($tipo_usuario === 'estudiante') {
        $conditions[] = "id_estudiante = '" . $conn->real_escape_string($_SESSION['usuario']) . "'";
    }
    
    // Filtros para admin
    if ($tipo_usuario === 'admin') {
        if (!empty($carrera)) {
            $conditions[] = "carrera = '$carrera'";
        }
        if (!empty($estado)) {
            $conditions[] = "estado = '$estado'";
        }
    }
    
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $result = $conn->query($query);
    
    if (!$result) {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
        exit;
    }
    
    $quejas = [];
    while ($row = $result->fetch_assoc()) {
        $quejas[] = $row;
    }
    
    echo json_encode($quejas);
    exit;
}

// Manejar POST para acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validar acción
    if (!isset($data['action'])) {
        echo json_encode(['error' => 'Acción no especificada']);
        exit;
    }
    
    switch ($data['action']) {
        case 'cambiar_estado':
            // Verificar que los datos estén presentes
            if (!isset($data['id']) || !isset($data['estado'])) {
                echo json_encode(['error' => 'Faltan datos']);
                exit;
            }
        
            $id = (int)$data['id'];
            $estado = $conn->real_escape_string($data['estado']);
        
            // Verificar si el estado es válido
            $estados_validos = ['pendiente', 'en_proceso', 'resuelta'];
            if (!in_array($estado, $estados_validos)) {
                echo json_encode(['error' => 'Estado inválido']);
                exit;
            }
        
            // Ejecutar la actualización
            $stmt = $conn->prepare("UPDATE quejas SET estado = ? WHERE id = ?");
            $stmt->bind_param("si", $estado, $id);
            $stmt->execute();
        
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'No se pudo actualizar el estado']);
            }
            break;
        
            
        case 'editar':
            // Solo para estudiantes
            if ($_SESSION['tipo'] !== 'estudiante') {
                echo json_encode(['error' => 'No autorizado']);
                exit;
            }
            
            if (!isset($data['id']) || !isset($data['mensaje'])) {
                echo json_encode(['error' => 'Datos incompletos']);
                exit;
            }
            
            $id = (int)$data['id'];
            $mensaje = $conn->real_escape_string($data['mensaje']);
            
            // Verificar que la queja pertenece al estudiante
            $stmt = $conn->prepare("UPDATE quejas SET mensaje = ? WHERE id = ? AND id_estudiante = ?");
            $stmt->bind_param("sis", $mensaje, $id, $_SESSION['usuario']);
            $stmt->execute();
            
            echo json_encode(['success' => $stmt->affected_rows > 0]);
            break;
            
        case 'eliminar':
            if (!isset($data['id'])) {
                echo json_encode(['error' => 'ID no especificado']);
                exit;
            }
            
            $id = (int)$data['id'];
            
            // Construir consulta según tipo de usuario
            if ($_SESSION['tipo'] === 'admin') {
                $stmt = $conn->prepare("DELETE FROM quejas WHERE id = ?");
                $stmt->bind_param("i", $id);
            } else {
                $stmt = $conn->prepare("DELETE FROM quejas WHERE id = ? AND id_estudiante = ?");
                $stmt->bind_param("is", $id, $_SESSION['usuario']);
            }
            
            $stmt->execute();
            
            echo json_encode(['success' => $stmt->affected_rows > 0]);
            break;
            
        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
    
    exit;
}

echo json_encode(['error' => 'Método no permitido']);
?>