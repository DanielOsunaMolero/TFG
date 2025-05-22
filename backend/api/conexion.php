<?php
$host = "localhost";
$dbname = "weekendhouse";
$user = "root";
$pass = ""; // Cambia si tienes contraseña

try {
  $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>
