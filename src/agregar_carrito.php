<?php
session_start();

$id = intval($_POST['producto_id']);
$cantidad = max(1, intval($_POST['unidades']));

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_SESSION['carrito'][$id])) {
    $_SESSION['carrito'][$id] += $cantidad;
} else {
    $_SESSION['carrito'][$id] = $cantidad;
}

// Redirigir a la página de origen (o a producto.php si no se envía)
$return_url = isset($_POST['return_url']) ? $_POST['return_url'] : 'producto.php';
header('Location: ' . $return_url);
exit();