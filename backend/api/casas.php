<?php
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json");

header('Content-Type: application/json');
require_once 'conexion.php';

$sql = "SELECT * FROM casa_rural";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);


?>


