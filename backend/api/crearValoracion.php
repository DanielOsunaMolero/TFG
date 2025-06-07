<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once __DIR__ . '/conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

$id_usuario = $data['id_usuario'] ?? null;
$id_casa = $data['id_casa'] ?? null;
$texto_valoracion = $data['texto_valoracion'] ?? '';
$puntuacion = $data['puntuacion'] ?? 0;
$dias_estancia = $data['dias_estancia'] ?? 0;

if ($id_usuario && $id_casa && trim($texto_valoracion) !== '' && $puntuacion > 0 && $dias_estancia > 0) {
    $query = "
        INSERT INTO valoracion (id_usuario, id_casa, texto_valoracion, puntuacion, dias_estancia)
        VALUES (:id_usuario, :id_casa, :texto_valoracion, :puntuacion, :dias_estancia)
    ";

    $stmt = $conexion->prepare($query);

    $success = $stmt->execute([
        'id_usuario' => $id_usuario,
        'id_casa' => $id_casa,
        'texto_valoracion' => $texto_valoracion,
        'puntuacion' => $puntuacion,
        'dias_estancia' => $dias_estancia
    ]);

    echo json_encode(['success' => $success]);
} else {
    echo json_encode(['success' => false, 'error' => 'Faltan datos']);
}
?>
