<?php
//Se utiliza para conectar a la base de datos
require_once __DIR__ . '/conexiondb.php';
//con esta funcion el servidor guarda la informacion sobre el visitante, 
//en caso de que seleccione algun producto, inicie sesion, o filtre, cualquier accion en resumen
session_start();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    // consulta para chequear si el correo ya está registrado
    //$sql_check guarda la consulta que se va a realizar
    // $stmt_check es una variable que guarda la consulta preparada
    // bind_param vincula los parametros de la consulta preparada
    //si el correo ya existe, se redirige al index.php con un mensaje de error
    //$sql se prepara para insertar los datos del usuario en la base de datos
    //si $stmt 
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

    // Si la consulta se prepara correctamente, se vinculan los parametros y se ejecuta
    // Si la ejecucion es correcta, se redirige al index.php con un mensaje de bienvenida
    // Si la ejecucion falla, se redirige al index.php con un mensaje de error
    // Si la consulta no se prepara correctamente, se redirige al index.php con un mensaje de error
    if ($stmt) {
        $stmt->bind_param('ssssss', $nombre, $apellidos, $email, $password, $direccion, $telefono);
        if ($stmt->execute()) {
            
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