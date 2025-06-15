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

require_once __DIR__ . '/conexion.php';

try {
    //Obtener el título de la casa
    $stmt = $conexion->prepare("SELECT titulo FROM casa_rural WHERE id_casa = ?");
    $stmt->execute([$id]);
    $titulo = $stmt->fetchColumn();

    //Eliminar la entrada en la base de datos
    $stmt2 = $conexion->prepare("DELETE FROM casa_rural WHERE id_casa = ?");
    $exito = $stmt2->execute([$id]);

    if (!$exito) {
        echo json_encode([
            "success" => false,
            "message" => "Error al eliminar en la base de datos"
        ]);
        exit;
    }

    //Si tenía imágenes, eliminar los archivos del disco
    if ($titulo) {
        $nombre_formateado = mb_strtolower($titulo, 'UTF-8');
        $nombre_formateado = strtr($nombre_formateado, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'n'
        ]);
        $nombre_formateado = preg_replace('/[^a-z0-9]/', '_', $nombre_formateado);
        $nombre_formateado = preg_replace('/_+/', '_', $nombre_formateado);
        $nombre_formateado = trim($nombre_formateado, '_');

        // 📌 Carpeta en hosting
        $carpetaFotos = realpath(__DIR__ . '/../../frontend/public/fotos/') . '/';

        $ficheros = glob($carpetaFotos . "Foto_{$nombre_formateado}(*).jpg");

        foreach ($ficheros as $archivo) {
            unlink($archivo);
        }
    }

    echo json_encode(["success" => true]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>
