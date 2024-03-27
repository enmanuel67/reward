<?php
session_start();

// Asegúrate de que el usuario esté autenticado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

// Configuración de conexión a la base de datos
$host = 'localhost';
$dbUser = 'tu_usuario';
$dbPassword = 'tu_contraseña';
$dbName = 'tu_base_de_datos';

$mysqli = new mysqli($host, $dbUser, $dbPassword, $dbName);

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Conexión fallida: ' . $mysqli->connect_error]);
    exit;
}

// Asumiendo que 'order' es un array de IDs de archivos en el nuevo orden
$data = json_decode(file_get_contents('php://input'), true);
$order = $data['order'];
$store = $data['store'];

// Asegúrate de validar y sanear $store

foreach ($order as $index => $fileId) {
    // Asumiendo que fileId es suficiente para identificar unívocamente cada archivo
    $query = "UPDATE files SET order_index = ? WHERE id = ? AND store_id = ?";
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
        exit;
    }

    $stmt->bind_param("iii", $index, $fileId, $store);
    $stmt->execute();
}

echo json_encode(['success' => true, 'message' => 'Orden actualizado correctamente']);
?>
