<?php
// filepath: c:\Users\6003411\Documents\GitHub\cooperativa-fenicia\src\procesar_pedido.php
session_start();
require_once 'conexiondb.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$carrito = $_SESSION['carrito'] ?? [];
if (!$carrito) {
    header('Location: carrito.php');
    exit();
}

// Obtener datos del usuario
$email = $_SESSION['email'];
$res = $conexion->query("SELECT id FROM usuarios WHERE email = '" . $conexion->real_escape_string($email) . "' LIMIT 1");
$user = $res->fetch_assoc();
$usuario_id = $user ? $user['id'] : null;

if (!$usuario_id) {
    header('Location: carrito.php');
    exit();
}

// Calcular total y preparar productos
$total = 0;
$productos = [];
$ids = implode(',', array_map('intval', array_keys($carrito)));
$res = $conexion->query("SELECT * FROM productos WHERE id IN ($ids)");
while ($row = $res->fetch_assoc()) {
    $cantidad = $carrito[$row['id']];
    $subtotal = $cantidad * $row['precio'];
    $productos[] = [
        'id' => $row['id'],
        'precio' => $row['precio'],
        'cantidad' => $cantidad,
        'subtotal' => $subtotal
    ];
    $total += $subtotal;
}

// Guardar pedido en la tabla "pedidos"
$stmt = $conexion->prepare("INSERT INTO pedidos (usuario_id, fecha, total) VALUES (?, NOW(), ?)");
$stmt->bind_param('id', $usuario_id, $total);
$stmt->execute();
$pedido_id = $stmt->insert_id;
$stmt->close();

// Guardar detalles en la tabla "detalle_pedido"
$stmt = $conexion->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
foreach ($productos as $prod) {
    $stmt->bind_param('iiid', $pedido_id, $prod['id'], $prod['cantidad'], $prod['precio']);
    $stmt->execute();
}
$stmt->close();

// Limpiar carrito
unset($_SESSION['carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pedido procesado - Cooperativa Fenicia</title>
  <link href="estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="icon" type="image/png" href="fotos/logo.png">
</head>
<body class="d-flex flex-column min-vh-100">
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="index.php">Cooperativa Fenicios</a>
      </div>
    </nav>
  </header>
  <main class="flex-grow-1 py-4">
    <div class="container">
      <div class="alert alert-success text-center">
        <h2>¡Pedido realizado con éxito!</h2>
        <p>Tu pedido ha sido procesado correctamente. Gracias por confiar en nosotros.</p>
        <a href="producto.php" class="btn btn-success mt-3">Seguir comprando</a>
      </div>
    </div>
  </main>
  <footer class="footer-custom text-white text-center py-3 mt-4">
    <div class="container">
      <small>&copy; 2025 Cooperativa Fenicia. Todos los derechos reservados.</small>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>