<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conexion = new mysqli("localhost", "root", "", "weekendhouse");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
}

$id_propietario = isset($_GET['id_propietario']) ? intval($_GET['id_propietario']) : 0;

$sql = "SELECT * FROM casa_rural WHERE id_propietario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_propietario);
$stmt->execute();
$resultado = $stmt->get_result();

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

$casas = [];

$url_fotos = "http://localhost:8080/fotos/";
$carpeta_fotos = realpath(__DIR__ . '/../../frontend/public/fotos/') . '/';

while ($fila = $resultado->fetch_assoc()) {
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
$conexion->close();
?>
