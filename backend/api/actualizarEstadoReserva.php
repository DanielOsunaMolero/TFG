<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Pragma: no-cache");

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $data = json_decode(file_get_contents("php://input"), true);

    $id_reserva = isset($data['id_reserva']) ? intval($data['id_reserva']) : 0;
    $estado = $data['estado'] ?? '';

    if ($id_reserva <= 0 || empty($estado)) {
        echo json_encode(["success" => false, "message" => "Datos incompletos"]);
        exit;
    }

    $stmt = $conexion->prepare("UPDATE reserva SET estado = ? WHERE id_reserva = ?");
    $stmt->execute([$estado, $id_reserva]);

    echo json_encode(["success" => true, "message" => "Estado actualizado correctamente"]);
    exit;

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}
?>
