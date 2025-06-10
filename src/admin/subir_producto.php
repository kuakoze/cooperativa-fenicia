<?php

session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

// Validar campos obligatorios
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    !empty($_POST['nombre']) &&
    !empty($_POST['descripcion']) &&
    !empty($_POST['precio']) &&
    !empty($_POST['stock']) &&
    isset($_FILES['imagen']) &&
    isset($_POST['categorias']) && is_array($_POST['categorias']) && count($_POST['categorias']) > 0
) {
    // Procesar imagen
    $img_dir = '../uploads/';
    if (!is_dir($img_dir)) {
        mkdir($img_dir, 0777, true);
    }
    $img_name = uniqid('prod_') . '_' . basename($_FILES['imagen']['name']);
    $img_path = $img_dir . $img_name;
    $img_db_path = 'uploads/' . $img_name; // Ruta relativa para guardar en la BD

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $img_path)) {
        // Insertar producto
        $stmt = $conexion->prepare("INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssdis",
            $_POST['nombre'],
            $_POST['descripcion'],
            $_POST['precio'],
            $_POST['stock'],
            $img_db_path
        );
        if ($stmt->execute()) {
            $producto_id = $stmt->insert_id;
            // Insertar categorías en tabla producto_categoria
            $cat_stmt = $conexion->prepare("INSERT INTO producto_categoria (producto_id, categoria_id) VALUES (?, ?)");
            foreach ($_POST['categorias'] as $categoria_id) {
                $cat_stmt->bind_param("ii", $producto_id, $categoria_id);
                $cat_stmt->execute();
            }
            $cat_stmt->close();
            header('Location: modificaciones.php?msg=Producto subido correctamente');
            exit();
        } else {
            // Si falla la inserción, elimina la imagen subida
            if (file_exists($img_path)) unlink($img_path);
            header('Location: modificaciones.php?msg=Error al subir el producto');
            exit();
        }
    } else {
        header('Location: modificaciones.php?msg=Error al subir la imagen');
        exit();
    }
} else {
    header('Location: modificaciones.php?msg=Faltan datos o categorías');
    exit();
}