<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS y tipo de respuesta
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

require_once __DIR__ . '/conexion.php';

// Validar que venga una imagen y el id del usuario
if (!isset($_FILES['foto']) || !isset($_POST['id_usuario'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos"]);
    exit;
}

$id_usuario = intval($_POST['id_usuario']);
$archivo = $_FILES['foto'];


$permitidos = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($archivo['type'], $permitidos)) {
    echo json_encode(["success" => false, "message" => "Formato no permitido"]);
    exit;
}


$extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
$nombre_archivo = "perfil_" . $id_usuario . "_" . time() . "." . $extension;
$ruta_destino = $_SERVER['DOCUMENT_ROOT'] . "/fotos_perfil/" . $nombre_archivo;

try {
    //Obtener la foto actual (si existe)
    $stmt = $conexion->prepare("SELECT foto_perfil FROM usuario WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $foto_actual = $stmt->fetchColumn();

    //Borrar foto anterior si hay una
    if ($foto_actual) {
        $rutaAnterior = $_SERVER['DOCUMENT_ROOT'] . "/fotos_perfil/" . $foto_actual;
        if (file_exists($rutaAnterior)) {
            unlink($rutaAnterior);
        }
    }

    // 3. Mover nueva imagen
    if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        echo json_encode(["success" => false, "message" => "Error al guardar imagen"]);
        exit;
    }

    // 4. Actualizar en la base de datos
    $stmt = $conexion->prepare("UPDATE usuario SET foto_perfil = ? WHERE id_usuario = ?");
    $stmt->execute([$nombre_archivo, $id_usuario]);

    echo json_encode(["success" => true, "nombre_foto" => $nombre_archivo]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
