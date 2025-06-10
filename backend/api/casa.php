<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/conexion.php';

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Falta el parámetro id"]);
    exit;
}

$id = intval($_GET['id']);

try {
    // Leer la casa
    $sql = "SELECT id_casa, titulo, descripcion, ubicacion, precio_noche, servicios
            FROM casa_rural WHERE id_casa = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$id]);
    $casa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$casa) {
        echo json_encode(["error" => "Casa no encontrada"]);
        exit;
    }

    // Función para normalizar el título
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

    // Buscar imágenes
    $imagenes = [];
    $nombreBase = normalizarTitulo($casa['titulo']);

    // Carpeta fotos (AJUSTADA BIEN)
    $carpetaFotos = realpath(__DIR__ . '/../../frontend/public/fotos/') . '/';

    // Glob con extensión jpg / jpeg / png
    $ficheros = glob($carpetaFotos . "Foto_" . $nombreBase . "(*).{jpg,jpeg,png}", GLOB_BRACE);

    foreach ($ficheros as $fichero) {
        $imagenes[] = basename($fichero);
    }

    // Añadir las imágenes al JSON
    $casa['imagenes'] = $imagenes;

    // Devolver la casa completa (con imágenes)
    echo json_encode($casa);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
