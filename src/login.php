<?php
require_once __DIR__ . '/conexiondb.php';

//aqui se va a comprobar si el usuario ha enviado el formulario de inicio de sesión
// y si es así, se va a validar sus credenciales
//si existe el mail y el password en la base de datos, se inicia una sesión
// y se redirige al usuario a la página de inicio o al panel de administración si es un administrador

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

            if ($_SESSION['email'] === 'admin@ejemplo.com') { 
                header('Location: admin/modificaciones.php');
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