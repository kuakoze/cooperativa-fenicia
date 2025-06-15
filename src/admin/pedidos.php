<?php
// filepath: c:\Users\6003411\Documents\GitHub\cooperativa-fenicia\src\admin\pedidos.php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

// Obtener pedidos y detalles
$sql = "SELECT p.id, p.fecha, p.total, u.nombre, u.apellidos, u.email
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";
$pedidos = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pedidos - Cooperativa Fenicia</title>
  <link href="../estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
  <!-- Header -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand">Cooperativa Fenicios</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
          <ul class="navbar-nav">
            
            
            <li class="nav-item"><a class="nav-link" href="modificaciones.php">Modificaciones</a></li>
            <li class="nav-item"><a class="nav-link" href="buzon.php">Buzon</a></li>
            <li class="nav-item"><a class="nav-link" href="pedidos.php">Pedidos</a></li>
            

          </ul>
          <div class="d-flex align-items-center">
            <span class="me-3 text-white fw-bold">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span>
            <form method="POST" action="../logout.php" class="d-inline">
              <button type="submit" class="btn btn-outline-light">Cerrar sesión</button>
            </form>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main class="flex-grow-1 py-4">
    <div class="container">
      <h2 class="mb-4">Pedidos realizados</h2>
      <?php if ($pedidos && $pedidos->num_rows > 0): ?>
        <div class="table-responsive mb-4">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
              <tr>
                <th>ID Pedido</th>
                <th>Fecha</th>
                <th>Total (€)</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Ver detalle</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($pedido = $pedidos->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $pedido['id']; ?></td>
                  <td><?php echo $pedido['fecha']; ?></td>
                  <td><?php echo number_format($pedido['total'], 2); ?></td>
                  <td><?php echo htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellidos']); ?></td>
                  <td><?php echo htmlspecialchars($pedido['email']); ?></td>
                  <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#detalle_<?php echo $pedido['id']; ?>">Ver</button>
                  </td>
                </tr>
                <tr class="collapse" id="detalle_<?php echo $pedido['id']; ?>">
                  <td colspan="6">
                    <?php
                    $sql_detalle = "SELECT d.cantidad, d.precio_unitario, pr.nombre
                                    FROM detalle_pedido d
                                    JOIN productos pr ON d.producto_id = pr.id
                                    WHERE d.pedido_id = ?";
                    $stmt = $conexion->prepare($sql_detalle);
                    $stmt->bind_param('i', $pedido['id']);
                    $stmt->execute();
                    $res_detalle = $stmt->get_result();
                    ?>
                    <table class="table table-sm table-bordered mb-0">
                      <thead>
                        <tr>
                          <th>Producto</th>
                          <th>Cantidad</th>
                          <th>Precio unitario (€)</th>
                          <th>Subtotal (€)</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($detalle = $res_detalle->fetch_assoc()): ?>
                          <tr>
                            <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                            <td><?php echo $detalle['cantidad']; ?></td>
                            <td><?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                            <td><?php echo number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2); ?></td>
                          </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                    <?php $stmt->close(); ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info text-center">No hay pedidos registrados.</div>
      <?php endif; ?>
    </div>
  </main>
  <footer class="footer-custom text-white text-center py-3 mt-4">
    <div class="container">
      <small>&copy; 2025 Cooperativa Fenicios. Todos los derechos reservados.</small>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>