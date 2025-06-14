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

    // Comprobar si el email ya existe
    $sql_check = "SELECT id FROM usuarios WHERE email = ? LIMIT 1";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param('s', $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        echo "<script>alert('El correo electrónico ya está registrado.'); window.location.href='index.php';</script>";
        exit();
    }
    $stmt_check->close();

    $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, direccion, telefono) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ssssss', $nombre, $apellidos, $email, $password, $direccion, $telefono);
        if ($stmt->execute()) {
            // Registro exitoso, NO iniciar sesión automáticamente
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