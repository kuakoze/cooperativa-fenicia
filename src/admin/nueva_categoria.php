<?php

session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nombre_categoria'])) {
    $nombre = trim($_POST['nombre_categoria']);
    // Evitar duplicados
    $stmt = $conexion->prepare("SELECT id FROM categorias WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        header('Location: modificaciones.php?msg=La categoría ya existe');
        exit();
    }
    $stmt->close();

    $stmt = $conexion->prepare("INSERT INTO categorias (nombre) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    if ($stmt->execute()) {
        header('Location: modificaciones.php?msg=Categoría creada correctamente');
        exit();
    } else {
        header('Location: modificaciones.php?msg=Error al crear la categoría');
        exit();
    }
}
header('Location: modificaciones.php?msg=Nombre de categoría vacío');
exit();