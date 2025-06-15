<?php
session_start();

//$id contiene el ID del producto a agregar al carrito
// cantidad contiene el número de unidades a agregar
//comprueba si existe el carrito y sino lo crea vacio para poder añadir productos
//si el id ya existe en el carrito, se incrementa la cantidad
// si no existe, se añade el producto con la cantidad especificada

$id = intval($_POST['producto_id']);
$cantidad = max(1, intval($_POST['unidades']));

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// 
if (isset($_SESSION['carrito'][$id])) {
    $_SESSION['carrito'][$id] += $cantidad;
} else {
    $_SESSION['carrito'][$id] = $cantidad;
}

// Redirige a la pagina de origen (o a producto.php si no se envía)
$return_url = isset($_POST['return_url']) ? $_POST['return_url'] : 'producto.php';
header('Location: ' . $return_url);
exit();