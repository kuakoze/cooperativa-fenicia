<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['producto_id'])) {
    $producto_id = intval($_POST['producto_id']);

    // Eliminar imagen del servidor si existe
    $res = $conexion->query("SELECT imagen FROM productos WHERE id = $producto_id");
    if ($row = $res->fetch_assoc()) {
        $img_path = '../' . $row['imagen'];
        if (is_file($img_path)) {
            unlink($img_path);
        }
    }

    // Eliminar producto (las relaciones en producto_categoria y detalle_pedido se eliminan por ON DELETE CASCADE)
    $stmt = $conexion->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $producto_id);
    if ($stmt->execute()) {
        header('Location: modificaciones.php?msg=Producto eliminado correctamente');
        exit();
    } else {
        header('Location: modificaciones.php?msg=Error al eliminar el producto');
        exit();
    }
}
header('Location: modificaciones.php?msg=No se seleccionó ningún producto');
exit();