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


$input = file_get_contents("php://input");
$datos = json_decode($input, true);


if (!$datos || !is_array($datos)) {
    http_response_code(400);
    echo json_encode(["error" => "Formato JSON inválido."]);
    exit;
}


$nombre = trim($datos['nombre'] ?? '');
$email = trim($datos['email'] ?? '');
$password = $datos['password'] ?? '';
$tipo = $datos['tipo'] ?? 'visitante';


if (!$nombre || !$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan datos obligatorios."]);
    exit;
}


$tiposPermitidos = ['visitante', 'propietario', 'admin'];
if (!in_array($tipo, $tiposPermitidos)) {
    http_response_code(400);
    echo json_encode(["error" => "Tipo de usuario no válido."]);
    exit;
}

try {

    $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("El email ya está registrado.");
    }


    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("INSERT INTO usuario (nombre, email, password, tipo, fecha_registro, foto_perfil)
                                VALUES (?, ?, ?, ?, CURDATE(), ?)");
    $stmt->execute([$nombre, $email, $hash, $tipo, 'default.jpg']);


    $id_usuario = $conexion->lastInsertId();

    $stmt = $conexion->prepare("SELECT id_usuario, nombre, email, tipo, fecha_registro, foto_perfil
                                FROM usuario WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


    echo json_encode(["success" => true, "usuario" => $usuario]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
