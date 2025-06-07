<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

require_once __DIR__ . '/conexion.php';

$id_usuario = $_GET['id_usuario'] ?? null;
$id_casa = $_GET['id_casa'] ?? null;

if ($id_usuario && $id_casa) {
    $query = "
        SELECT COUNT(*) AS total
        FROM reserva
        WHERE id_usuario = :id_usuario
          AND id_casa = :id_casa
          AND estado = 'confirmada'
    ";

    $stmt = $conexion->prepare($query);

    $stmt->execute([
        'id_usuario' => $id_usuario,
        'id_casa' => $id_casa
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['haReservado' => $result['total'] > 0]);
} else {
    echo json_encode(['haReservado' => false]);
}
?>
