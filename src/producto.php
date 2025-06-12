<?php
session_start();
require_once 'conexiondb.php';

// Obtener categorías para el filtro
$categorias = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");

// Procesar filtros
$where = [];
$params = [];
$types = '';

if (!empty($_GET['nombre'])) {
    $where[] = "productos.nombre LIKE ?";
    $params[] = '%' . $_GET['nombre'] . '%';
    $types .= 's';
}

if (!empty($_GET['categorias'])) {
    $catIds = array_filter(array_map('intval', $_GET['categorias']));
    if ($catIds) {
        $in = implode(',', array_fill(0, count($catIds), '?'));
        $where[] = "productos.id IN (
            SELECT producto_id FROM producto_categoria WHERE categoria_id IN ($in)
        )";
        $params = array_merge($params, $catIds);
        $types .= str_repeat('i', count($catIds));
    }
}

$sql = "SELECT * FROM productos";
if ($where) {
    $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY nombre ASC";

$stmt = $conexion->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();
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
            <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
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
    <div class="container">
      <h2 class="mb-4">Nuestros Productos</h2>

      <!-- Filtro de búsqueda -->
      <form class="card card-body mb-4" method="get" action="producto.php">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label for="nombre" class="form-label">Buscar por nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_GET['nombre'] ?? ''); ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Filtrar por categoría</label>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($categorias as $cat): ?>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="cat_<?php echo $cat['id']; ?>" name="categorias[]" value="<?php echo $cat['id']; ?>"
                    <?php if (!empty($_GET['categorias']) && in_array($cat['id'], $_GET['categorias'])) echo 'checked'; ?>>
                  <label class="form-check-label" for="cat_<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
          </div>
        </div>
      </form>

      <div class="row g-4">
        <?php if ($res->num_rows === 0): ?>
          <div class="col-12">
            <div class="alert alert-warning text-center">No se encontraron productos con los filtros seleccionados.</div>
          </div>
        <?php endif; ?>
        <?php while ($prod = $res->fetch_assoc()): ?>
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
  
<!-- MODAL DE REGISTRO -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRegistroLabel">Registrarse</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>