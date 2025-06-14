<?php
require_once __DIR__ . '/conexiondb.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssssss', $nombre, $apellidos, $email, $password, $direccion, $telefono);
        if ($stmt->execute()) {
            // Guardar nombre en la sesión
            $_SESSION['usuario'] = $nombre;
            header('Location: index.php?bienvenida=1');
            exit();
        } else {
            header('Location: index.php?registro=error');
            exit();
        }
        $stmt->close();
    } else {
        header('Location: index.php?registro=error');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}