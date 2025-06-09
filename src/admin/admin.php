<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel de Administración - Cooperativa Fenicia</title>
  <link href="../estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="d-flex flex-column min-vh-100">
  <!-- Header -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="../index.php">Cooperativa Fenicia</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link active" href="../index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="#productos">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
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

  <!-- ALERTA ADMIN -->
  <div class="container mt-3">
    <div class="alert alert-success" role="alert">
      Logeado como admin
    </div>
  </div>

  <!-- Main -->
  <main class="flex-grow-1 py-4">
    <section class="contenedor-principal d-flex flex-column flex-lg-row align-items-center justify-content-center">
      <div class="contenedor-imagen d-flex justify-content-center align-items-center mb-4 mb-lg-0">
        <img src="../imagenes/cooperativa-fenicia.jpg" alt="Cooperativa Fenicia" class="img-fluid imagen-cooperativa" />
      </div>
      <div class="contenedor-descripcion px-3">
        <h2 class="mb-3">Nuestra Idiosincrasia</h2>
        <p>
          En la Cooperativa Fenicia nos une la pasión por la agricultura sostenible, la solidaridad entre nuestros socios y el compromiso con el desarrollo rural. Apostamos por la innovación, el trabajo en equipo y el respeto al medio ambiente para construir un futuro mejor para todos.
        </p>
      </div>
    </section>
  </main>

  <footer class="footer-custom text-white text-center py-3 mt-4">
    <div class="container">
      <small>&copy; 2025 Cooperativa Fenicia. Todos los derechos reservados.</small>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>