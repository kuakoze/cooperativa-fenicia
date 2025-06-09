<?php
session_start();
require_once __DIR__ . '/../includes/conexiondb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Consulta para buscar el usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Guardar datos importantes en la sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['rol'] = $usuario['rol']; // <- AQUÍ GUARDAMOS EL ROL

        // Redirigir al inicio
        header('Location: /src/index.php');
        exit;
    } else {
        // Credenciales incorrectas
        $_SESSION['error_login'] = "Correo o contraseña incorrectos";
        header('Location: /src/login.php');
        exit;
    }
}
?>