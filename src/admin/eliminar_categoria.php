<?php

session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['categoria_id'])) {
    $categoria_id = intval($_POST['categoria_id']);
    // Elimina la categoría (las relaciones en producto_categoria se eliminan por ON DELETE CASCADE)
    $stmt = $conexion->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $categoria_id);
    if ($stmt->execute()) {
        header('Location: modificaciones.php?msg=Categoría eliminada correctamente');
        exit();
    } else {
        header('Location: modificaciones.php?msg=Error al eliminar la categoría');
        exit();
    }
}
header('Location: modificaciones.php?msg=No se seleccionó ninguna categoría');
exit();