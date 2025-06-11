<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contacto - Cooperativa Fenicia</title>
  <link href="../estilos.css" rel="stylesheet" />
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
            <li class="nav-item"><a class="nav-link" href="producto.php">Productos</a></li>
            <li class="nav-item"><a class="nav-link active" href="contacto.php">Contacto</a></li>
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
      <h2 class="mb-4">Contacto</h2>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title mb-3">Datos de contacto</h5>
              <p class="mb-2"><strong>Dirección:</strong> Calle Ejemplo, 123, 28000 Ciudad, Provincia</p>
              <p class="mb-2"><strong>Teléfono:</strong> <a href="tel:+34900123456">900 123 456</a></p>
              <p class="mb-2"><strong>Email:</strong> <a href="mailto:info@cooperativafenicia.com">info@cooperativafenicia.com</a></p>
              <p class="mb-2"><strong>Horario:</strong> Lunes a Viernes, 9:00 - 14:00 y 16:00 - 19:00</p>
              <hr>
              <h6 class="mb-2">Redes sociales</h6>
              <a href="#" class="me-2"><img src="imagenes/facebook.svg" alt="Facebook" width="24"></a>
              <a href="#" class="me-2"><img src="imagenes/twitter.svg" alt="Twitter" width="24"></a>
              <a href="#"><img src="imagenes/instagram.svg" alt="Instagram" width="24"></a>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title mb-3">Formulario de contacto</h5>
              <form method="POST" action="enviar_contacto.php">
                <div class="mb-3">
                  <label for="nombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Correo electrónico</label>
                  <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="mensaje" class="form-label">Mensaje</label>
                  <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-body p-2">
              <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=-3.7037902%2C40.4167754%2C-3.7037902%2C40.4167754&amp;layer=mapnik"
                style="width:100%; height:300px; border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
          </div>
        </div>
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