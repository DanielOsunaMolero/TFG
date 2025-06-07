<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

require_once __DIR__ . '/conexion.php';

$id_usuario = $_GET['id_usuario'] ?? null;
$id_casa = $_GET['id_casa'] ?? null;

$query = "
    SELECT v.*, u.nombre AS nombre_usuario, u.foto_perfil, c.titulo AS nombre_casa
    FROM valoracion v
    JOIN usuario u ON v.id_usuario = u.id_usuario
    JOIN casa_rural c ON v.id_casa = c.id_casa
    WHERE 1=1
";

$params = [];

if ($id_usuario) {
    $query .= " AND v.id_usuario = :id_usuario";
    $params['id_usuario'] = $id_usuario;
}

if ($id_casa) {
    $query .= " AND v.id_casa = :id_casa";
    $params['id_casa'] = $id_casa;
}

$query .= " ORDER BY v.fecha_valoracion DESC";

$stmt = $conexion->prepare($query);

$stmt->execute($params);

$valoraciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($valoraciones);
?>
