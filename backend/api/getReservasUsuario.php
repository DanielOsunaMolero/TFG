<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "conexion.php";

if (!isset($_GET["id_usuario"])) {
    echo json_encode(["error" => "Falta el id_usuario"]);
    exit;
}

$id_usuario = intval($_GET["id_usuario"]);

$sql = "SELECT r.id_reserva, r.fecha_inicio, r.fecha_fin, r.estado, c.titulo AS titulo_casa
        FROM reserva r
        JOIN casa_rural c ON r.id_casa = c.id_casa
        WHERE r.id_usuario = ?
        ORDER BY r.fecha_inicio DESC";

$stmt = $conexion->prepare($sql);
$stmt->execute([$id_usuario]);

$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($reservas);
?>
