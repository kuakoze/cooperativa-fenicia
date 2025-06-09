<?php
require_once __DIR__ . '/conexiondb.php'; // ← RUTA ROBUSTA

if (
    isset($_POST['nombre'], $_POST['apellidos'], $_POST['email'], $_POST['password'], $_POST['direccion'], $_POST['telefono'])
) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Correo electrónico no válido.');
    }

    $stmt = $conexion->prepare("INSERT INTO usuario (nombre, apellidos, email, password, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $apellidos, $email, $password, $direccion, $telefono);

    if ($stmt->execute()) {
        echo "Registro exitoso. <a href='index.php'>Volver</a>";
    } else {
        if ($conexion->errno === 1062) {
            echo "El correo electrónico ya está registrado.";
        } else {
            echo "Error en el registro: " . $stmt->error;
        }
    }

    $stmt->close();
    $conexion->close();
} else {
    echo "Todos los campos son obligatorios.";
}
?>