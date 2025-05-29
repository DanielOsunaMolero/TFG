<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

if (!$id) {
    echo json_encode(["error" => "ID inválido"]);
    exit;
}

$conexion = new mysqli("localhost", "root", "", "weekendhouse");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
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
