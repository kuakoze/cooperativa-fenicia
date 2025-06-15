<?php
require_once 'conexiondb.php';

//aqui se va a comprobar si el usuario ha enviado el formulario de contacto
// y si es asi, se va a validar los datos introducidos
// si los datos son correctos, se guarda el mensaje en la base de datos
// y se envía un correo electrónico al destinatario y se redirige al usuario a la página de contacto con un mensaje de éxito
//y si no son correctos, se redirige a la página de contacto con un mensaje de error


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre && $email && $mensaje && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        $stmt = $conexion->prepare("INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $mensaje);
        $stmt->execute();
        $stmt->close();


        header('Location: contacto.php?msg=Mensaje enviado correctamente');
        exit();
    } else {
        header('Location: contacto.php?msg=Por favor, rellena todos los campos correctamente');
        exit();
    }
} else {
    header('Location: contacto.php');
    exit();
}