<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

include 'conexion.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'POST':
    $data = json_decode(file_get_contents("php://input"), true);

    try {
      $sql = "INSERT INTO reserva (id_usuario, id_casa, fecha_inicio, fecha_fin, importe_total)
              VALUES (:id_usuario, :id_casa, :fecha_inicio, :fecha_fin, :importe_total)";
      $stmt = $conexion->prepare($sql);
      $stmt->execute([
        ':id_usuario' => $data['id_usuario'],
        ':id_casa' => $data['id_casa'],
        ':fecha_inicio' => $data['fecha_inicio'],
        ':fecha_fin' => $data['fecha_fin'],
        ':importe_total' => $data['importe_total']
      ]);

      echo json_encode(["status" => "ok", "id_reserva" => $conexion->lastInsertId()]);
    } catch (PDOException $e) {
      echo json_encode(["status" => "error", "error" => $e->getMessage()]);
    }
    break;

  case 'PUT':
    parse_str(file_get_contents("php://input"), $data);

    try {
      $sql = "UPDATE reserva SET estado = :estado WHERE id_reserva = :id_reserva";
      $stmt = $conexion->prepare($sql);
      $stmt->execute([
        ':estado' => $data['estado'],
        ':id_reserva' => $data['id_reserva']
      ]);

      echo json_encode(["status" => "ok"]);
    } catch (PDOException $e) {
      echo json_encode(["status" => "error", "error" => $e->getMessage()]);
    }
    break;

  case 'GET':
    if (isset($_GET['id_propietario'])) {
      try {
        $sql = "
          SELECT r.*, u.nombre AS nombre_usuario, u.foto_perfil, c.titulo AS titulo_casa
          FROM reserva r
          JOIN casa_rural c ON r.id_casa = c.id_casa
          JOIN usuario u ON r.id_usuario = u.id_usuario
          WHERE c.id_propietario = :id_propietario
          ORDER BY fecha_reserva DESC
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id_propietario' => $_GET['id_propietario']]);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($reservas);
      } catch (PDOException $e) {
        echo json_encode(["status" => "error", "error" => $e->getMessage()]);
      }
    }
    break;
}
?>
