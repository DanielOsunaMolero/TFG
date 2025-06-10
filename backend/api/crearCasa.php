<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . '/conexion.php';

try {
    // 1. Recoger datos
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $ubicacion = $_POST['ubicacion'];
    $precio = $_POST['precio'];
    $servicios = $_POST['servicios'];
    $id_propietario = intval($_POST['id_propietario'] ?? 0); // ✅ recogerlo del POST

    if ($id_propietario === 0) {
        echo json_encode(["success" => false, "error" => "Falta id_propietario"]);
        exit;
    }

    // 2. Insertar en base de datos
    $sql = "INSERT INTO casa_rural (id_propietario, titulo, descripcion, ubicacion, precio_noche, servicios)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id_propietario, $titulo, $descripcion, $ubicacion, $precio, $servicios]);

    // 3. Obtener id_casa
    $id_casa = $conexion->lastInsertId();

    // 4. Función para normalizar el título
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

    // 5. Carpeta destino
    $carpeta = realpath(__DIR__ . '/../../frontend/public/fotos/') . '/';

    // 6. Guardar imágenes si vienen
    if (!empty($_FILES['imagenes']['name'][0])) {
        foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmp) {
            $nombre = "Foto_{$nombre_formateado}(" . ($i + 1) . ").jpg";
            move_uploaded_file($tmp, $carpeta . $nombre);
        }
    }

    // 7. Devolver respuesta
    echo json_encode(["success" => true, "id_casa" => $id_casa]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
