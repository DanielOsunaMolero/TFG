<?php
header("Access-Control-Allow-Origin: *"); // ← esta línea permite que Vue acceda
header("Content-Type: application/json");

header('Content-Type: application/json');
require_once 'conexion.php';

$sql = "SELECT * FROM casa_rural";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultado);

/* PARA OBTENER TODAS LAS CASAS DESDE LA BASE DE DATOS */
?>


