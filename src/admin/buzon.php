<?php

session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

//$mensajes se van a guardar los mensajes de contacto a traves de una consulta 
$mensajes = $conexion->query("SELECT nombre, email, mensaje, fecha FROM mensajes_contacto ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cooperativa Fenicios</title>
  <link href="../estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
   <link rel="icon" type="image/png" href="../fotos/logo.png">
</head>
<body class="d-flex flex-column min-vh-100">
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
      <h2 class="mb-4">Buzón de mensajes de contacto</h2>
      <?php if ($mensajes->num_rows === 0): ?>
        <div class="alert alert-info text-center">No hay mensajes en el buzón.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
              <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Mensaje</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $mensajes->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                  <td><?php echo htmlspecialchars($row['email']); ?></td>
                  <td><?php echo nl2br(htmlspecialchars($row['mensaje'])); ?></td>
                  <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
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