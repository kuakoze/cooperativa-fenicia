<?php
session_start();
// Solo usuarios registrados pueden agregar productos al carrito
if (!isset($_SESSION['usuario']) || !isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

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