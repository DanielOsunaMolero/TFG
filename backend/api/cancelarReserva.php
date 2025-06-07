<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  echo json_encode(["success" => false, "message" => "Método no permitido"]);
  exit;
}

require_once "conexion.php";

// Recibir JSON del frontend
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input["id_reserva"])) {
  echo json_encode(["success" => false, "message" => "ID de reserva no proporcionado"]);
  exit;
}

$id_reserva = intval($input["id_reserva"]);

try {
  $stmt = $conexion->prepare("DELETE FROM reserva WHERE id_reserva = ?");
  $stmt->execute([$id_reserva]);

  echo json_encode(["success" => true, "message" => "Reserva eliminada correctamente"]);
} catch (PDOException $e) {
  echo json_encode([
    "success" => false,
    "message" => "Error al eliminar la reserva: " . $e->getMessage()
  ]);
}
?>
