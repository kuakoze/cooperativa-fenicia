<?php

// aqui se guardan los datos de la conexión a la base de datos y se establece la conexión
// si la conexión falla, se muestra un mensaje de error
$servidor = "db";   
$usuario = "admin";
$password = "admin";
$base_datos = "cooperativa_fenicia";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>