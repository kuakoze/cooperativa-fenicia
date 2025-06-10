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
  <title>Modificaciones - Cooperativa Fenicia</title>
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
            <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="#productos">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
            <li class="nav-item"><a class="nav-link active" href="modificaciones.php">Modificaciones</a></li>
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

  <!-- Main -->
  <main class="flex-grow-1 py-4">
    <div class="container">
      <h2 class="mb-4">Panel de Modificaciones</h2>
      <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($_GET['msg']); ?></div>
      <?php endif; ?>
      <form>
        <label for="modSelect" class="form-label">Seleccione una opción para modificar:</label>
        <select class="form-select form-select-lg mb-3" id="modSelect" aria-label="Large select example">
          <option selected value="">Elige una opcion</option>
          <option value="1">Subir un producto</option>
          <option value="2">Modificar un producto</option>
          <option value="3">Eliminar un producto</option>
          <option value="4">Crear una nueva categoria</option>
          <option value="5">Eliminar una categoria</option>
        </select>
      </form>

      <!-- Formulario para crear nueva categoría (oculto por defecto) -->
      <div id="formNuevaCategoria" class="card p-4 mb-3" style="display:none;">
        <h5 class="mb-3">Crear nueva categoría</h5>
        <form method="POST" action="nueva_categoria.php">
          <div class="mb-3">
            <label for="nombreCategoria" class="form-label">Nombre de la categoría</label>
            <input type="text" class="form-control" id="nombreCategoria" name="nombre_categoria" required>
          </div>
          <button type="submit" class="btn btn-success">Crear categoría</button>
        </form>
      </div>

      <!-- Formulario para eliminar categoría (oculto por defecto) -->
      <div id="formEliminarCategoria" class="card p-4 mb-3" style="display:none;">
        <h5 class="mb-3">Eliminar categoría</h5>
        <form method="POST" action="eliminar_categoria.php">
          <div class="mb-3">
            <label for="categoriaEliminar" class="form-label">Seleccione la categoría a eliminar</label>
            <select class="form-select" id="categoriaEliminar" name="categoria_id" required>
              <option value="">Seleccione una categoría</option>
              <?php
              require_once '../conexiondb.php';
              $res = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
              while ($row = $res->fetch_assoc()):
              ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que desea eliminar esta categoría?');">Eliminar</button>
        </form>
      </div>
    </div>
  </main>

  <footer class="footer-custom text-white text-center py-3 mt-4">
    <div class="container">
      <small>&copy; 2025 Cooperativa Fenicia. Todos los derechos reservados.</small>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Mostrar/ocultar formularios según la opción seleccionada
    document.getElementById('modSelect').addEventListener('change', function() {
      const formCat = document.getElementById('formNuevaCategoria');
      const formDelCat = document.getElementById('formEliminarCategoria');
      if (this.value === '4') {
        formCat.style.display = 'block';
        formDelCat.style.display = 'none';
      } else if (this.value === '5') {
        formCat.style.display = 'none';
        formDelCat.style.display = 'block';
      } else {
        formCat.style.display = 'none';
        formDelCat.style.display = 'none';
      }
    });
  </script>
</body>
</html>