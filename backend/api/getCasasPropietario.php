<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$conexion = new mysqli("localhost", "root", "", "weekendhouse");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Conexión fallida"]));
}

$id_propietario = 1;

$sql = "SELECT * FROM casa_rural WHERE id_propietario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_propietario);
$stmt->execute();
$resultado = $stmt->get_result();

function normalizarTitulo($titulo) {
    $titulo = strtolower($titulo);
    $titulo = str_replace(["á","é","í","ó","ú","ñ"], ["a","e","i","o","u","n"], $titulo);
    $titulo = preg_replace('/[^a-z0-9]/', '_', $titulo);
    $titulo = preg_replace('/_+/', '_', $titulo);
    return trim($titulo, '_');
}

$casas = [];

// 1. Obtener ruta completa
$ruta_fotos = "C:/xampp/htdocs/dashboard/TFG/frontend/public/fotos/";


if ($ruta_fotos && is_dir($ruta_fotos)) {
    $todos_los_ficheros = scandir($ruta_fotos);
    
    
} else {
    $todos_los_ficheros = [];
    
}

while ($fila = $resultado->fetch_assoc()) {
    $id = $fila["id_casa"];
    $titulo = $fila["titulo"];
    $nombre_formateado = normalizarTitulo($titulo);

    

    $imagen = "";

    foreach ($todos_los_ficheros as $fichero) {
        
        if (
            preg_match('/^Foto_' . preg_quote($nombre_formateado) . '\(\d+\)\.(jpg|jpeg|png)$/i', $fichero)
        ) {
            
            $imagen = "http://localhost:8080/fotos/$fichero";
            break;
        }
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
