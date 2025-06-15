<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['nombre'])) {
    echo json_encode(["success" => false, "message" => "Falta el nombre de la imagen."]);
    exit;
}

$nombre = basename($data['nombre']); 

$ruta = $_SERVER['DOCUMENT_ROOT'] . "/fotos/" . $nombre;

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
