<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cooperativa Fenicia</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Estilos personalizados -->
  <link href="estilos.css" rel="stylesheet" />
  <link rel="icon" type="image/png" href="fotos/logo.png">
</head>
<body class="d-flex flex-column min-vh-100">
  <!-- Header -->
  <header>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="#">Cooperativa Fenicios</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="producto.php">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
            <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>

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
              <button class="btn btn-light text-success me-2" data-bs-toggle="modal" data-bs-target="#modalRegistro">Registrarse</button>
              <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#modalLogin">Iniciar sesión</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main -->
  <main class="flex-grow-1 py-4">
    <section class="contenedor-principal d-flex flex-column flex-lg-row align-items-center justify-content-center">
      <!-- Imagen -->
      <div class="contenedor-imagen d-flex justify-content-center align-items-center mb-4 mb-lg-0">
        <img src="fotos/fotoinicio.jpeg" alt="Cooperativa Fenicia" class="img-fluid imagen-cooperativa" />
      </div>
      <!-- Descripcion -->
      <div class="contenedor-descripcion px-3">
        <h2 class="mb-3 centrar-titulo-index">Bienvenidos a la Cooperativa Agrícola Fenicios</h2>
        <p>Bienvenidos a la Cooperativa Agrícola de Adra
          En el corazón de la vega de Adra, donde el mar y la tierra se dan la mano, 
          nace nuestra cooperativa: un proyecto con alma local, 
          pensado por y para los agricultores de nuestra comarca.
          Creemos en una agricultura responsable, sostenible y cercana.
           Por eso ofrecemos productos fitosanitarios de calidad, 
           asesoramiento técnico y un trato personal que solo una cooperativa puede brindar.
            Conocemos el campo porque también lo trabajamos. 
            Sabemos lo que necesitas porque compartimos la misma tierra. 
            Desde ahora, puedes hacer tus pedidos a través de esta web y venir a recogerlos cómodamente en nuestra sede.
             Rápido, fácil y sin perder el trato directo que tanto valoramos.
             Gracias por confiar en tu gente. Gracias por confiar en tu cooperativa.        
        </p>
      </div>
    </section>
  </main>

  <!-- Modals -->
  <!-- MODAL DE REGISTRO -->
  <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRegistroLabel">Registrarse</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Formulario de registro -->
          <form method="POST" action="registro.php">
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
            <button type="submit" class="btn btn-success">Registrarse</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL DE LOGIN -->
  <div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLoginLabel">Iniciar sesión</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="login.php">
            <div class="mb-3">
              <label for="loginEmail" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" id="loginEmail" name="email" required>
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Contraseña</label>
              <input type="password" class="form-control" id="loginPassword" name="password" required>
            </div>
            <button type="submit" class="btn btn-success">Iniciar sesión</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer-custom text-white text-center py-3 mt-4">
    <div class="container">
      <small>&copy; 2025 Cooperativa Fenicia. Todos los derechos reservados.</small>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>