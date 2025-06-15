<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $precio_noche = isset($_POST['precio_noche']) ? floatval($_POST['precio_noche']) : 0;
    $servicios = $_POST['servicios'] ?? '';
    $id_propietario = isset($_POST['id_propietario']) ? intval($_POST['id_propietario']) : 0;

    if ($id_propietario <= 0) {
        echo json_encode(["success" => false, "error" => "Falta id_propietario"]);
        exit;
    }

    if ($precio_noche <= 0) {
        echo json_encode(["success" => false, "error" => "Precio incorrecto"]);
        exit;
    }

    $sql = "INSERT INTO casa_rural (id_propietario, titulo, descripcion, ubicacion, precio_noche, servicios)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id_propietario, $titulo, $descripcion, $ubicacion, $precio_noche, $servicios]);

    $id_casa = $conexion->lastInsertId();

    function normalizarTitulo($titulo) {
        $titulo = mb_strtolower($titulo, 'UTF-8');
        $titulo = strtr($titulo, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'n'
        ]);
        $titulo = preg_replace('/[^a-z0-9]/', '_', $titulo);
        $titulo = preg_replace('/_+/', '_', $titulo);
        return trim($titulo, '_');
    }

    $nombre_formateado = normalizarTitulo($titulo);

    $carpeta = $_SERVER['DOCUMENT_ROOT'] . '/fotos/';
    $imagenes_guardadas = [];

    if (!empty($_FILES['imagenes']['name'][0])) {
        foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmp) {
            $nombre = "Foto_{$nombre_formateado}(" . ($i + 1) . ").jpg";

            if (move_uploaded_file($tmp, $carpeta . $nombre)) {
                $imagenes_guardadas[] = $nombre;
            } else {
                echo json_encode([
                    "success" => false,
                    "error" => "No se pudo mover la imagen '{$nombre}'",
                    "tmp_name" => $tmp,
                    "destino" => $carpeta . $nombre,
                    "permisos_carpeta" => substr(sprintf('%o', fileperms($carpeta)), -4)
                ]);
                exit;
            }
        }
    }

    echo json_encode([
        "success" => true,
        "id_casa" => $id_casa,
        "imagenes_guardadas" => $imagenes_guardadas
    ]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
