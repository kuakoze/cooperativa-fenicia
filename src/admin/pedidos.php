<?php
// filepath: c:\Users\6003411\Documents\GitHub\cooperativa-fenicia\src\admin\buzon.php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';

// Obtener mensajes de contacto
$mensajes = $conexion->query("SELECT nombre, email, mensaje, fecha FROM mensajes_contacto ORDER BY fecha DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Buzón de mensajes - Cooperativa Fenicia</title>
  <link href="../estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
  <!-- Header -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand">Cooperativa Fenicia</a>
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


   <footer class="footer-custom text-white text-center py-3 mt-4">
    <div class="container">
      <small>&copy; 2025 Cooperativa Fenicia. Todos los derechos reservados.</small>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>