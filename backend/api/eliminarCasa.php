<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

if (!isset($_POST["id"])) {
    echo json_encode(["success" => false, "message" => "ID no proporcionado"]);
    exit;
}

$id = intval($_POST["id"]);
if (!$id) {
    echo json_encode(["success" => false, "message" => "ID inválido"]);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "weekendhouse");

if ($conexion->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexión fallida"]);
    exit;
}

// Obtener el título de la casa
$stmt = $conexion->prepare("SELECT titulo FROM casa_rural WHERE id_casa = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$titulo = $row ? $row["titulo"] : null;

// Eliminar la entrada en la base de datos
$stmt = $conexion->prepare("DELETE FROM casa_rural WHERE id_casa = ?");
$stmt->bind_param("i", $id);
$exito = $stmt->execute();

if (!$exito) {
    echo json_encode([
        "success" => false,
        "message" => "Error al eliminar en la base de datos: " . $stmt->error
    ]);
    exit;
}


// Si tenía imágenes, eliminar los archivos del disco
if ($exito && $titulo) {
    $nombre_formateado = strtolower($titulo);
    $nombre_formateado = str_replace(["á","é","í","ó","ú","ñ"], ["a","e","i","o","u","n"], $nombre_formateado);
    $nombre_formateado = preg_replace('/[^a-z0-9]/', '_', $nombre_formateado);
    $nombre_formateado = preg_replace('/_+/', '_', $nombre_formateado);
    $nombre_formateado = trim($nombre_formateado, '_');

    $directorio = "C:/xampp/htdocs/dashboard/TFG/frontend/public/fotos/";
    foreach (glob($directorio . "Foto_{$nombre_formateado}(*).jpg") as $archivo) {
        unlink($archivo);
    }
}

echo json_encode(["success" => $exito]);
$conexion->close();
?>
