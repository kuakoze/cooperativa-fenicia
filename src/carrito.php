<?php
// filepath: c:\Users\6003411\Documents\GitHub\cooperativa-fenicia\src\carrito.php
session_start();
require_once 'conexiondb.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$carrito = $_SESSION['carrito'] ?? [];

$productos = [];
$total = 0;

if ($carrito) {
    $ids = implode(',', array_map('intval', array_keys($carrito)));
    $res = $conexion->query("SELECT * FROM productos WHERE id IN ($ids)");
    while ($row = $res->fetch_assoc()) {
        $row['cantidad'] = $carrito[$row['id']];
        $row['subtotal'] = $row['cantidad'] * $row['precio'];
        $productos[] = $row;
        $total += $row['subtotal'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Carrito de la compra - Cooperativa Fenicia</title>
  <link href="estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="index.php">Cooperativa Fenicia</a>
        <!-- ... resto de la navbar igual que en tus otras páginas ... -->
      </div>
    </nav>
  </header>
  <main class="flex-grow-1 py-4">
    <div class="container">
      <h2 class="mb-4">Carrito de la compra</h2>
      <?php if (!$productos): ?>
        <div class="alert alert-info text-center">Tu carrito está vacío.</div>
      <?php else: ?>
        <div class="table-responsive mb-4">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
              <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($productos as $prod): ?>
                <tr>
                  <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                  <td><?php echo number_format($prod['precio'], 2); ?> €</td>
                  <td><?php echo intval($prod['cantidad']); ?></td>
                  <td><?php echo number_format($prod['subtotal'], 2); ?> €</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end">Total</th>
                <th><?php echo number_format($total, 2); ?> €</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <form method="POST" action="procesar_pedido.php" class="text-center">
          <button type="submit" class="btn btn-success btn-lg">Procesar pedido</button>
        </form>
      <?php endif; ?>
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