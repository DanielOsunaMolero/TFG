<?php
// Habilitar errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cabeceras CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

// ✅ RESPUESTA A PETICIONES OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/conexion.php';

// Recibir JSON crudo y decodificar
$input = file_get_contents("php://input");
$datos = json_decode($input, true);

// Validar si el JSON llegó bien
if (!$datos || !is_array($datos)) {
    http_response_code(400);
    echo json_encode(["error" => "Formato JSON inválido."]);
    exit;
}

// Asignar valores
$nombre = trim($datos['nombre'] ?? '');
$email = trim($datos['email'] ?? '');
$password = $datos['password'] ?? '';
$tipo = $datos['tipo'] ?? 'visitante';

// Validar campos obligatorios
if (!$nombre || !$email || !$password) {
    http_response_code(400);
    echo json_encode(["error" => "Faltan datos obligatorios."]);
    exit;
}

// Validar tipo permitido
$tiposPermitidos = ['visitante', 'propietario', 'admin'];
if (!in_array($tipo, $tiposPermitidos)) {
    http_response_code(400);
    echo json_encode(["error" => "Tipo de usuario no válido."]);
    exit;
}

try {
    // Verificar si el correo ya está en uso
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("El email ya está registrado.");
    }

    // Insertar usuario
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("INSERT INTO usuario (nombre, email, contraseña, tipo, fecha_registro)
                                VALUES (?, ?, ?, ?, CURDATE())");
    $stmt->execute([$nombre, $email, $hash, $tipo]);

    echo json_encode(["success" => true, "mensaje" => "Registro exitoso"]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
