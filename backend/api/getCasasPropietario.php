<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/conexion.php'; // así usas siempre la misma conexión

$id_propietario = isset($_GET['id_propietario']) ? intval($_GET['id_propietario']) : 0;

$sql = "SELECT * FROM casa_rural WHERE id_propietario = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id_propietario]);
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

// Ajustes para el HOSTING (https dinámico)
$url_fotos = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") .
    "://" . $_SERVER['HTTP_HOST'] . "/fotos/";

$carpeta_fotos = $_SERVER['DOCUMENT_ROOT'] . "/fotos/";

$casas = [];

foreach ($resultado as $fila) {
    $id = $fila["id_casa"];
    $titulo = $fila["titulo"];
    $nombre_formateado = normalizarTitulo($titulo);

    // Buscar las fotos de la casa
    $ficheros = glob($carpeta_fotos . "Foto_" . $nombre_formateado . "(*).{jpg,jpeg,png}", GLOB_BRACE);

    // Si hay fotos, coger la primera; si no, poner imagen vacía
    if (!empty($ficheros)) {
        $imagen = $url_fotos . basename($ficheros[0]);
    } else {
        $imagen = ""; // No ponemos default
    }

    $casas[] = [
        "id" => $id,
        "titulo" => $titulo,
        "imagen" => $imagen
    ];
}

echo json_encode($casas);
?>
