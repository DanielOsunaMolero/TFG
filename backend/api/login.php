<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// ✅ RESPUESTA A OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/conexion.php';

$input = file_get_contents("php://input");
$datos = json_decode($input, true);

$email = $datos['email'] ?? '';
$password = $datos['password'] ?? '';

if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan credenciales."]);
    exit;
}

try {
    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($password, $usuario['contraseña'])) {
        http_response_code(401);
        echo json_encode(["error" => "Credenciales incorrectas."]);
        exit;
    }

    unset($usuario['contraseña']); // No enviar contraseña al frontend

    echo json_encode(["success" => true, "usuario" => $usuario]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
