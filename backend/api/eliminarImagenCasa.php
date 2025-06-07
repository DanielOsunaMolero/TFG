<?php
// Habilitar errores para depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Encabezados CORS y JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Leer datos del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Validar que se recibió el nombre
if (!isset($data['nombre'])) {
    echo json_encode(["success" => false, "message" => "Falta el nombre de la imagen."]);
    exit;
}

$nombre = basename($data['nombre']); // Evita rutas relativas o maliciosas
$ruta = "../../frontend/public/fotos/" . $nombre;

if (file_exists($ruta)) {
    if (unlink($ruta)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo eliminar."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Archivo no encontrado."]);
}
?>
