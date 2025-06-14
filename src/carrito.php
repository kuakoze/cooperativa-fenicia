<?php
// filepath: c:\Users\6003411\Documents\GitHub\cooperativa-fenicia\src\carrito.php
session_start();
require_once 'conexiondb.php';

if (!isset($_SESSION['usuario'])) {
    // Mostrar mensaje amigable si no está logueado
    echo '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Carrito de la compra - Cooperativa Fenicia</title>
  <link href="estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
  <div class="container py-5">
    <div class="alert alert-warning text-center mt-5">
      <h4 class="mb-3">Debes registrarte o iniciar sesión para ver el carrito.</h4>
      <a href="index.php" class="btn btn-success">Volver al inicio</a>
    </div>
  </div>
</body>
</html>';
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link active" href="producto.php">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@ejemplo.com'): ?>
              <li class="nav-item"><a class="nav-link" href="admin/modificaciones.php">Modificaciones</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
          </ul>
          <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['usuario'])): ?>
              <span class="me-3 text-white fw-bold">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span>
              <form method="POST" action="logout.php" class="d-inline">
                <button type="submit" class="btn btn-outline-light">Cerrar sesión</button>
              </form>
            <?php else: ?>
              <button class="btn btn-light text-success me-2" data-bs-toggle="modal" data-bs-target="#modalRegistro">Registrarse</button>
              <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalLogin">Iniciar sesión</button>
            <?php endif; ?>
          </div>
        </div>
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