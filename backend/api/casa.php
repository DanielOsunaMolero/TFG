<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
  $sql = "SELECT * FROM casa_rural WHERE id_casa = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->execute([$id]);
  $casa = $stmt->fetch(PDO::FETCH_ASSOC);

  echo json_encode($casa);
} else {
  echo json_encode(["error" => "ID no proporcionado"]);
}
/*PARA OBTENER UNA DE LAS CASAS */
?>
