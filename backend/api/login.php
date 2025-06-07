<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'conexion.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $input = json_decode(file_get_contents("php://input"), true);

  if (isset($input["email"]) && isset($input["password"])) {
    $email = $input["email"];
    $password = $input["password"];

    $stmt = $conexion->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["password"])) {
      echo json_encode([
        "success" => true,
        "usuario" => [
          "id_usuario" => $usuario["id_usuario"],
          "nombre" => $usuario["nombre"],
          "email" => $usuario["email"],
          "tipo" => $usuario["tipo"],
          "fecha_registro" => $usuario["fecha_registro"],
          "foto_perfil" => $usuario["foto_perfil"] ?? null
        ]
      ]);
    } else {
      echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
    }
  } else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Faltan datos"]);
  }
}
