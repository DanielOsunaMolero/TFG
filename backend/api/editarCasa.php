<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

require_once __DIR__ . '/conexion.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Recoger datos
        $id = $_POST['id'] ?? null;
        $titulo = $_POST['titulo'] ?? '';
        $descripcion = $_POST['descripcion'] ?? '';
        $ubicacion = $_POST['ubicacion'] ?? '';
        $precio_noche = isset($_POST['precio_noche']) ? floatval($_POST['precio_noche']) : 0;
        $servicios = $_POST['servicios'] ?? '';

        if (!$id) {
            echo json_encode(["success" => false, "message" => "Falta ID de la casa."]);
            exit;
        }

        if ($precio_noche <= 0) {
            echo json_encode(["success" => false, "message" => "Precio incorrecto"]);
            exit;
        }

        // Actualizar datos básicos
        $sql = "UPDATE casa_rural SET 
                    titulo = ?, 
                    descripcion = ?, 
                    ubicacion = ?, 
                    precio_noche = ?, 
                    servicios = ?
                WHERE id_casa = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$titulo, $descripcion, $ubicacion, $precio_noche, $servicios, $id]);

        //Función para normalizar el título
        function normalizarTitulo($titulo) {
            $titulo = mb_strtolower($titulo, 'UTF-8');
            $titulo = strtr($titulo, [
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
                'ñ' => 'n', 'Ñ' => 'n'
            ]);
            $titulo = preg_replace('/[^a-z0-9]/', '_', $titulo);
            $titulo = preg_replace('/_+/', '_', $titulo);
            return trim($titulo, '_');
        }

        $nombreBase = normalizarTitulo($titulo);

        //Carpeta destino (mejor con realpath)
        $carpetaDestino = realpath(__DIR__ . '/../../frontend/public/fotos/') . '/';


        $imagenes_guardadas = [];

        if (!empty($_FILES['imagenes']['name'][0])) {

            $ficherosExistentes = glob($carpetaDestino . "Foto_" . $nombreBase . "(*).jpg");
            $indice = count($ficherosExistentes) + 1;

            foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmpName) {
                $nombreFinal = "Foto_" . $nombreBase . "($indice).jpg";

                if (move_uploaded_file($tmpName, $carpetaDestino . $nombreFinal)) {
                    $imagenes_guardadas[] = $nombreFinal;
                    $indice++;
                }
            }
        }

        $imagenesActuales = [];
        $ficheros = glob($carpetaDestino . "Foto_" . $nombreBase . "(*).jpg");

        foreach ($ficheros as $fichero) {
            $imagenesActuales[] = basename($fichero);
        }

        sort($imagenesActuales);


        echo json_encode([
            "success" => true,
            "imagenes_guardadas" => $imagenes_guardadas,
            "imagenes" => $imagenesActuales
        ]);

    } else {
        echo json_encode(["success" => false, "message" => "Método no permitido."]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
