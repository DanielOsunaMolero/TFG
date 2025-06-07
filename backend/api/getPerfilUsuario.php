<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;

if ($id_usuario <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "ID de usuario inválido"]);
    exit;
}

try {
    // Obtener datos del usuario sin contraseña
    $stmt = $conexion->prepare("SELECT id_usuario, nombre, email, tipo, fecha_registro, foto_perfil FROM usuario WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        echo json_encode(["error" => "Usuario no encontrado"]);
        exit;
    }

    // Obtener sus reservas
    $stmt2 = $conexion->prepare("
        SELECT r.id_reserva, r.fecha_inicio, r.fecha_fin, r.estado, c.titulo AS titulo_casa
        FROM reserva r
        JOIN casa_rural c ON r.id_casa = c.id_casa
        WHERE r.id_usuario = ?
        ORDER BY r.fecha_inicio DESC
    ");
    $stmt2->execute([$id_usuario]);
    $reservas = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "usuario" => $usuario,
        "reservas" => $reservas
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
}
