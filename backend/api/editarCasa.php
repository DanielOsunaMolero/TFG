<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . '/conexion.php';

try {
    if (!isset($_POST['id'])) {
        echo json_encode(["success" => false, "error" => "Falta el ID de la casa"]);
        exit;
    }

    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'] ?? "";
    $descripcion = $_POST['descripcion'] ?? "";
    $ubicacion = $_POST['ubicacion'] ?? "";
    $precio = $_POST['precio_noche'] ?? 0;
    $servicios = $_POST['servicios'] ?? "";

    $sql = "UPDATE casa_rural
            SET titulo = ?, descripcion = ?, ubicacion = ?, precio_noche = ?, servicios = ?
            WHERE id_casa = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$titulo, $descripcion, $ubicacion, $precio, $servicios, $id]);

    if (!empty($_FILES['imagenes']['tmp_name'][0])) {
        $formatear = function($texto) {
            $texto = strtolower($texto);
            $texto = str_replace(["á","é","í","ó","ú","ñ"], ["a","e","i","o","u","n"], $texto);
            $texto = preg_replace('/[^a-z0-9]/', '_', $texto);
            $texto = preg_replace('/_+/', '_', $texto);
            return trim($texto, '_');
        };

        $nombre_formateado = $formatear($titulo);
        $carpeta = "C:/xampp/htdocs/dashboard/TFG/frontend/public/fotos/";

        foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmp) {
            $nombre = "Foto_{$nombre_formateado}(" . ($i + 1) . ").jpg";
            move_uploaded_file($tmp, $carpeta . $nombre);
        }
    }
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
