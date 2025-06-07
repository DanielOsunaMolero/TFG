<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "conexion.php";

if (!isset($_GET["id"])) {
    echo json_encode(["error" => "Falta el parámetro id"]);
    exit;
}

$id = intval($_GET["id"]);

$stmt = $conexion->prepare("SELECT id_usuario, nombre, email, tipo, fecha_registro, foto_perfil FROM usuario WHERE id_usuario = ?");
$stmt->execute([$id]);

if ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo json_encode($usuario);
} else {
    echo json_encode(["error" => "Usuario no encontrado"]);
}
?>
