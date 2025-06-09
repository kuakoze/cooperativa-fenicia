<?php
$servidor = "db";
$usuario = "root";
$password = "admin";
$base_datos = "cooperativa_fenicia";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>