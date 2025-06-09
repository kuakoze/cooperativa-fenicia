<?php
require_once __DIR__ . '/conexiondb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM usuarios WHERE email = ? AND password = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            session_start();
            $usuario = $resultado->fetch_assoc();
            $_SESSION['usuario'] = $usuario['nombre'] . ' ' . $usuario['apellidos'];
            $_SESSION['email'] = $usuario['email'];

            if ($_SESSION['email'] === 'admin@ejemplo.com') { // Pon aquí el email real del admin
                header('Location: admin/admin.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            header('Location: index.php?login=error');
            exit();
        }
        $stmt->close();
    } else {
        header('Location: index.php?login=error');
        exit();
    }
}
?>