<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/conexion.php';

// Recibir JSON crudo
$input = file_get_contents("php://input");
$datos = json_decode($input, true);

// Validar si el JSON llegó bien
if (!$datos || !is_array($datos)) {
    http_response_code(400);
    echo json_encode(["error" => "Formato JSON inválido."]);
    exit;
}

// Asignar valores
$email = trim($datos['email'] ?? '');
$password = $datos['password'] ?? '';

// Validar campos obligatorios
if (!$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan datos obligatorios."]);
    exit;
}

try {
    // Buscar usuario
    $stmt = $conexion->prepare("SELECT id_usuario, nombre, email, password, tipo, fecha_registro, foto_perfil
                                FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($password, $usuario['password'])) {
        throw new Exception("Credenciales incorrectas.");
    }

    unset($usuario['password']);

    // Devolver usuario
    echo json_encode(["success" => true, "usuario" => $usuario]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
