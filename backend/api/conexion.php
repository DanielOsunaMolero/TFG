<?php
$host = "localhost";
$dbname = "weekendhouse";
$user = "root";
$pass = ""; 
//CONFIGURADO PARA LANZARLO EN LOCALHOST 
// EL CONEXION PARA DESPLEGARLO EN EL SERVIDOR LO TENGO YO PERSONALMENTE
try {
  $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
  $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>
