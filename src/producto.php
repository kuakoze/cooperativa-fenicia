<?php
session_start();
require_once 'conexiondb.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Productos - Cooperativa Fenicia</title>
  <link href="estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
  <!-- Header -->
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
            <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@ejemplo.com'): ?>
              <li class="nav-item"><a class="nav-link" href="admin/modificaciones.php">Modificaciones</a></li>
            <?php endif; ?>
          </ul>
          <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['usuario'])): ?>
              <span class="me-3 text-white fw-bold">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span>
              <form method="POST" action="logout.php" class="d-inline">
                <button type="submit" class="btn btn-outline-light">Cerrar sesión</button>
              </form>
            <?php else: ?>
              <a href="index.php#modalLogin" class="btn btn-outline-light">Iniciar sesión</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main -->
  <main class="flex-grow-1 py-4">
    <div class="container">
      <h2 class="mb-4">Nuestros Productos</h2>
      <div class="row g-4">
        <?php
        $res = $conexion->query("SELECT * FROM productos");
        while ($prod = $res->fetch_assoc()):
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
          <div class="card flex-fill" style="width: 18rem;">
            <img src="<?php echo htmlspecialchars($prod['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
              <p class="card-text fw-bold mb-2">Precio: <?php echo number_format($prod['precio'], 2); ?> €</p>
              <a href="#" class="btn btn-primary mt-auto">Ver más</a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
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