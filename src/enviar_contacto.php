<?php
// enviar_contacto.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');

    if ($nombre && $email && $mensaje && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Cambia este correo por el de tu cooperativa
        $destinatario = 'info@cooperativafenicia.com';
        $asunto = "Nuevo mensaje de contacto de $nombre";
        $cuerpo = "Nombre: $nombre\n";
        $cuerpo .= "Email: $email\n";
        $cuerpo .= "Mensaje:\n$mensaje\n";

        $cabeceras = "From: $email\r\nReply-To: $email\r\n";

        if (mail($destinatario, $asunto, $cuerpo, $cabeceras)) {
            header('Location: contacto.php?msg=Mensaje enviado correctamente');
            exit();
        } else {
            header('Location: contacto.php?msg=Error al enviar el mensaje');
            exit();
        }
    } else {
        header('Location: contacto.php?msg=Por favor, rellena todos los campos correctamente');
        exit();
    }
} else {
    header('Location: contacto.php');
    exit();
}