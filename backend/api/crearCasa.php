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
  $id_propietario = 1; // Temporal, hasta tener login

  // 2. Insertar en base de datos
  $sql = "INSERT INTO casa_rural (id_propietario, titulo, descripcion, ubicacion, precio_noche, servicios)
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conexion->prepare($sql); // <-- AQUÍ SE CORRIGE
  $stmt->execute([$id_propietario, $titulo, $descripcion, $ubicacion, $precio, $servicios]);

  $id_casa = $conexion->lastInsertId(); // <-- TAMBIÉN AQUÍ

  // 3. Formatear nombre para imagen
  $formatear = function($texto) {
    $texto = strtolower($texto);
    $texto = str_replace(["á","é","í","ó","ú","ñ"], ["a","e","i","o","u","n"], $texto);
    $texto = preg_replace('/[^a-z0-9]/', '_', $texto);
    $texto = preg_replace('/_+/', '_', $texto);
    return trim($texto, '_');
  };
  $nombre_formateado = $formatear($titulo);

  // 4. Carpeta destino
  $carpeta = "C:/xampp/htdocs/dashboard/TFG/frontend/public/fotos/";

  // 5. Guardar imágenes
  foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmp) {
    $nombre = "Foto_{$nombre_formateado}(" . ($i + 1) . ").jpg";
    move_uploaded_file($tmp, $carpeta . $nombre);
  }

  echo json_encode(["success" => true, "id_casa" => $id_casa]);
} catch (Exception $e) {
  echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
