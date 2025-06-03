<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// Carga conexión con ruta segura y correcta
require_once __DIR__ . '/conexion.php';

// Verificar que se ha recibido el parámetro id
if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Falta el parámetro id"]);
    exit;
}

$id = intval($_GET['id']);

try {
    $sql = "SELECT id_casa, titulo, descripcion, ubicacion, precio_noche, servicios
            FROM casa_rural WHERE id_casa = ?";
    $stmt = $conexion->prepare($sql); // <- aquí se usa $conexion correctamente
    $stmt->execute([$id]);
    $casa = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($casa) {
        echo json_encode($casa);
    } else {
        echo json_encode(["error" => "Casa no encontrada"]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
