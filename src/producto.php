<?php
session_start();
require_once 'conexiondb.php';

// Obtener categorías para el filtro
$categorias = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");

// --- Paginación ---
$productosPorPagina = 8;
$paginaActual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($paginaActual - 1) * $productosPorPagina;

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

$sqlBase = "FROM productos";
if ($where) {
    $sqlBase .= " WHERE " . implode(' AND ', $where);
}

// --- Total de productos para paginación ---
$sqlCount = "SELECT COUNT(*) $sqlBase";
$stmtCount = $conexion->prepare($sqlCount);
if ($params) {
    $stmtCount->bind_param($types, ...$params);
}
$stmtCount->execute();
$stmtCount->bind_result($totalProductos);
$stmtCount->fetch();
$stmtCount->close();
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// --- Consulta de productos con paginación ---
$sql = "SELECT * $sqlBase ORDER BY nombre ASC LIMIT ? OFFSET ?";
if ($params) {
    $paramsQuery = $params;
    $paramsQuery[] = $productosPorPagina;
    $paramsQuery[] = $offset;
    $typesQuery = $types . 'ii';
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($typesQuery, ...$paramsQuery);
} else {
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $productosPorPagina, $offset);
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
  <style>
    .product-img {
      width: 100%;
      height: 260px; /* Aumentado el tamaño de la imagen */
      object-fit: cover;
      object-position: center;
      border-radius: 0.375rem 0.375rem 0 0;
    }
    .input-cantidad {
      max-width: 90px;
    }
  </style>
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

  <!-- Main -->
  <main class="flex-grow-1 py-4">
    <div class="container">
      <h2 class="mb-4">Nuestros Productos</h2>

      <!-- Filtro de búsqueda mejorado y unificado -->
      <form class="card card-body mb-4 shadow-sm bg-light border-0" method="get" action="producto.php">
        <div class="row g-3 mb-2">
          <div class="col-12">
            <label for="nombre" class="form-label fw-bold">Buscar por nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($_GET['nombre'] ?? ''); ?>" placeholder="Introduce el nombre...">
          </div>
        </div>
        <div class="row g-3 align-items-end">
          <div class="col-12 col-md-10">
            <label class="form-label fw-bold">Filtrar por categoría</label>
            <div class="rounded p-2 border bg-light">
              <div class="row">
                <?php
                $i = 0;
                foreach ($categorias as $cat):
                  if ($i % 3 === 0 && $i > 0) echo '</div><div class="row">';
                ?>
                  <div class="col-12 col-md-4 mb-1">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="cat_<?php echo $cat['id']; ?>" name="categorias[]" value="<?php echo $cat['id']; ?>"
                        <?php if (!empty($_GET['categorias']) && in_array($cat['id'], $_GET['categorias'])) echo 'checked'; ?> />
                      <label class="form-check-label" for="cat_<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></label>
                    </div>
                  </div>
                <?php $i++; endforeach; ?>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-2 d-flex justify-content-center align-items-end">
            <button type="submit" class="btn btn-success w-100">Buscar</button>
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
        <?php
          // Obtener categorías del producto
          $catRes = $conexion->query("SELECT c.nombre FROM categorias c INNER JOIN producto_categoria pc ON c.id = pc.categoria_id WHERE pc.producto_id = " . intval($prod['id']));
          $catNombres = [];
          while ($catRow = $catRes->fetch_assoc()) {
            $catNombres[] = $catRow['nombre'];
          }
        ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
          <div class="card flex-fill h-100" style="width: 18rem;">
            <img src="<?php echo htmlspecialchars($prod['imagen']); ?>" class="product-img" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
            <div class="card-body d-flex flex-column h-100">
              <h5 class="card-title"><?php echo htmlspecialchars($prod['nombre']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars($prod['descripcion']); ?></p>
              <p class="card-text mb-1"><strong>Stock:</strong> <?php echo intval($prod['stock']); ?></p>
              <p class="card-text mb-1"><strong>Categoría<?php echo count($catNombres) > 1 ? 's' : ''; ?>:</strong>
                <?php echo htmlspecialchars(implode(', ', $catNombres)); ?>
              </p>
              <p class="card-text fw-bold mb-2">Precio: <?php echo number_format($prod['precio'], 2); ?> €</p>
              <div class="mt-auto d-flex flex-column gap-2">
                <form method="POST" action="agregar_carrito.php" class="d-flex align-items-center gap-2">
                  <input type="hidden" name="producto_id" value="<?php echo $prod['id']; ?>">
                  <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                  <input type="number" name="unidades" class="form-control input-cantidad" min="1" max="<?php echo intval($prod['stock']); ?>" value="1"
                    <?php if ($prod['stock'] < 1) echo 'disabled'; ?>>
                  <button type="submit" class="btn btn-success"
                    <?php if ($prod['stock'] < 1) echo 'disabled'; ?>>Añadir al carro</button>
                </form>
                <!-- Botón 'Ver más' eliminado -->
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <!-- PAGINACIÓN -->
      <?php if ($totalPaginas > 1): ?>
      <!-- Aquí empieza la paginación -->
      <nav aria-label="Paginación de productos" class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item<?php if ($paginaActual <= 1) echo ' disabled'; ?>">
            <a class="page-link" href="?<?php
              $paramsPag = $_GET;
              $paramsPag['pagina'] = $paginaActual - 1;
              echo http_build_query($paramsPag);
            ?>">Anterior</a>
          </li>
          <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item<?php if ($i == $paginaActual) echo ' active'; ?>">
              <a class="page-link" href="?<?php
                $paramsPag = $_GET;
                $paramsPag['pagina'] = $i;
                echo http_build_query($paramsPag);
              ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item<?php if ($paginaActual >= $totalPaginas) echo ' disabled'; ?>">
            <a class="page-link" href="?<?php
              $paramsPag = $_GET;
              $paramsPag['pagina'] = $paginaActual + 1;
              echo http_build_query($paramsPag);
            ?>">Siguiente</a>
          </li>
        </ul>
      </nav>
      <!-- Aquí termina la paginación -->
      <?php endif; ?>
      <!-- FIN PAGINACIÓN -->

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