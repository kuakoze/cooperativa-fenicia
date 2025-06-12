<?php
require_once 'conexiondb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre && $email && $mensaje && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Guardar en la base de datos
        $stmt = $conexion->prepare("INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $email, $mensaje);
        $stmt->execute();
        $stmt->close();

        // (Opcional) Enviar correo
        $destinatario = 'info@cooperativafenicia.com';
        $asunto = "Nuevo mensaje de contacto de $nombre";
        $cuerpo = "Nombre: $nombre\n";
        $cuerpo .= "Email: $email\n";
        $cuerpo .= "Mensaje:\n$mensaje\n";
        $cabeceras = "From: $email\r\nReply-To: $email\r\n";

        mail($destinatario, $asunto, $cuerpo, $cabeceras);

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