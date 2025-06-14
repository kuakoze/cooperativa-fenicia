<?php
// filepath: c:\Users\6003411\Documents\GitHub\cooperativa-fenicia\src\carrito.php
session_start();
require_once 'conexiondb.php';

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
        <form method="POST" action="procesar_pedido.php" class="text-center" id="form_procesar_pedido">
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
  <div class="modal fade" id="modal_registro_pedido" tabindex="-1" aria-labelledby="modal_registro_pedido_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal_registro_pedido_label">Atención</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          Debes estar registrado e iniciar sesión para realizar un pedido.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Registro -->
  <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRegistroLabel">Registro de usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form method="POST" action="registro.php">
          <div class="modal-body">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="mb-3">
              <label for="apellidos" class="form-label">Apellidos</label>
              <input type="text" class="form-control" id="apellidos" name="apellidos" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
              <label for="direccion" class="form-label">Dirección</label>
              <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
            <div class="mb-3">
              <label for="telefono" class="form-label">Teléfono</label>
              <input type="tel" class="form-control" id="telefono" name="telefono" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Registrarse</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Login -->
  <div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLoginLabel">Iniciar sesión</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <form method="POST" action="login.php">
          <div class="modal-body">
            <div class="mb-3">
              <label for="loginEmail" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="loginEmail" name="email" required>
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="loginPassword" name="password" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-success">Iniciar sesión</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    var usuario_registrado = <?php echo isset($_SESSION['usuario']) ? 'true' : 'false'; ?>;
    document.addEventListener('DOMContentLoaded', function() {
      var form_procesar_pedido = document.getElementById('form_procesar_pedido');
      if (form_procesar_pedido) {
        form_procesar_pedido.addEventListener('submit', function(e) {
          if (!usuario_registrado) {
            e.preventDefault();
            var modal = new bootstrap.Modal(document.getElementById('modal_registro_pedido'));
            modal.show();
          }
        });
      }
    });
  </script>
</body>
</html>