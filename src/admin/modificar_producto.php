<?php

session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    !empty($_POST['id']) &&
    !empty($_POST['nombre']) &&
    !empty($_POST['descripcion']) &&
    !empty($_POST['precio']) &&
    !empty($_POST['stock']) &&
    isset($_POST['categorias']) && is_array($_POST['categorias']) && count($_POST['categorias']) > 0
) {
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Procesar imagen si se sube una nueva
    $img_db_path = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $img_dir = '../uploads/';
        if (!is_dir($img_dir)) {
            mkdir($img_dir, 0777, true);
        }
        $img_name = uniqid('prod_') . '_' . basename($_FILES['imagen']['name']);
        $img_path = $img_dir . $img_name;
        $img_db_path = 'uploads/' . $img_name;
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $img_path)) {
            header('Location: modificaciones.php?msg=Error al subir la imagen');
            exit();
        }
    }

    // Actualizar producto
    if ($img_db_path) {
        $stmt = $conexion->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen=? WHERE id=?");
        $stmt->bind_param("ssdisi", $nombre, $descripcion, $precio, $stock, $img_db_path, $id);
    } else {
        $stmt = $conexion->prepare("UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE id=?");
        $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $id);
    }
    if ($stmt->execute()) {
        // Actualizar categorías: eliminar las actuales y añadir las nuevas
        $conexion->query("DELETE FROM producto_categoria WHERE producto_id = $id");
        $cat_stmt = $conexion->prepare("INSERT INTO producto_categoria (producto_id, categoria_id) VALUES (?, ?)");
        foreach ($_POST['categorias'] as $categoria_id) {
            $cat_stmt->bind_param("ii", $id, $categoria_id);
            $cat_stmt->execute();
        }
        $cat_stmt->close();
        header('Location: modificaciones.php?msg=Producto modificado correctamente');
        exit();
    } else {
        header('Location: modificaciones.php?msg=Error al modificar el producto');
        exit();
    }
} else {
    header('Location: modificaciones.php?msg=Faltan datos o categorías');
    exit();
}