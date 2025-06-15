<?php
//con esta funcion el servidor guarda la informacion sobre el visitante, 
//en caso de que seleccione algun producto, inicie sesion, o filtre, cualquier accion en resumen
session_start();

//Se utiliza para conectar a la base de datos
require_once 'conexiondb.php';

// $categorias guarda las categorías disponibles con la siguiente consulta, ordenando por nombre ascendente.
$categorias = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");

// para la paginacion, en $prodctosPorPagina se define cuantos productos se mostraran por pagina en este caso 8,
// en $paginaActual se guarda la pagina actual, si no se especifica se pone 1 por defecto, primero se comprueba si existe un valor para pagina, sino se deja en 1
//intval convierte el valor a entero, y max nos aseguramos de que coge el valor maximo y como minimo el 1.
//$paginacion se calcula el numero de productos que va a mostrar y a partir de cual se va a mostrar,
//asi por ejemplo, si estamos en la pagina 1, se mostraran los productos del 0 al 7, si estamos en la pagina 2, del 8 al 15, etc.
$productosPorPagina = 8;
$paginaActual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$paginacion = ($paginaActual - 1) * $productosPorPagina;

// $contiene se guarda las condiciones de filtrado que se aplicaran a la consulta de productos,
// $parametros se guarda los parametros que se pasaran a la consulta, y $tipo se guarda el tipo de cada parametro para la consulta preparada.
$contiene = [];
$parametros = [];
$tipo = '';


// si se busca por nombre, se añade una consulta a $contiene, y se añade el parametro a $parametros,
// el tipo de parametro se añade a $tipo, en este caso 's' para string.
// si el nombre esta vacio, no se añade nada a la consulta.
// el valor en $parametros se añade a contiene sustituyendo ? por el valor del nombre introducido por el usuario
if (!empty($_GET['nombre'])) {
    $contiene[] = "productos.nombre LIKE ?";
    $parametros[] = '%' . $_GET['nombre'] . '%';
    $tipo .= 's';
}

// si se seleccionan categorias, se filtran los ids de las categorias seleccionadas, y se añade una consulta a $contiene,
// se añade el parametro a $parametros, y el tipo de parametro se añade a $tipo, en este caso 'i' para integer
//$catIds guarda las ids de las c ategorias seleccionadas, 
// con array_map  nos aseguramos que esos valores son numeros enteros, y con array_filter eliminamos los valores vacios
// para comprobar las categorias de cada producto, se comprueba en la tabla producto_categoria,
// $in guarda los ids de las categorias seleccionadas, y se crea una consulta que comprueba si el producto esta en alguna de esas categorias
if (!empty($_GET['categorias'])) {
    $catIds = array_filter(array_map('intval', $_GET['categorias']));
    if ($catIds) {
        $in = implode(',', array_fill(0, count($catIds), '?'));
        $contiene[] = "productos.id IN (
            SELECT producto_id FROM producto_categoria WHERE categoria_id IN ($in)
        )";
        $parametros = array_merge($parametros, $catIds);
        $tipo .= str_repeat('i', count($catIds));
    }
}

//se guarda en en la variable $sqlBase la consulta base de productos,
// si $contiene tiene condiciones, se añade una clausula WHERE con las condiciones unidas por AND

$sqlBase = "FROM productos";
if ($contiene) {
    $sqlBase .= " WHERE " . implode(' AND ', $contiene);
}

// $sqlCount guarda la consulta para contar el total de productos que cumplen las condiciones de filtrado,
//$stmtCount se prepara la consulta, y si hay parametros, se vinculan a la consulta con bind_param
// si hay filtros como la categoria o nombre, se comprueba y se añade a la consulta el tipo de parametro correspondiente,
// despues se ejecuta la consulta en la base de datos, y se guarda el resultado en $totalProductos,
// con fetch se extrae el resultado y se cierra la consulta.
// con ceil vamos a redondear hacia arriba por si la division de productos y paginas no es exacta
$sqlCount = "SELECT COUNT(*) $sqlBase";
$stmtCount = $conexion->prepare($sqlCount);
if ($parametros) {
    $stmtCount->bind_param($tipo, ...$parametros);
}
$stmtCount->execute();
$stmtCount->bind_result($totalProductos);
$stmtCount->fetch();
$stmtCount->close();
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// en este apartado del codigo ya si se va hacer la  consulta principal para obtener los productos,
//$sql va a buscar los productos con los filtros que se le guardo $sqlBase,
// se ordenan por nombre ascendente, y se limita el resultado a los productos por pagina y la paginacion calculada anteriormente
// si hay parametros, se combinan con el numero de productos que se muestran por pagina y desde cual empezar a mosotrar
//$tiposQuery guarda el tipo de cada parametro, en este caso 'ii' para dos enteros (productosPorPagina y paginacion),
//$stmt se prepara la consulta 
//si no hay nada que mostrar a la consulta solo se le pasa dos numeros enteros pasa saber cuantos productos mostrar y dedes donde
//$res contiene los productos obtenidos de la consulta
$sql = "SELECT * $sqlBase ORDER BY nombre ASC LIMIT ? OFFSET ?";
if ($parametros) {
    $parametrosQuery = $parametros;
    $parametrosQuery[] = $productosPorPagina;
    $parametrosQuery[] = $paginacion;
    $tiposQuery = $tipo . 'ii';
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param($tiposQuery, ...$parametrosQuery);
} else {
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('ii', $productosPorPagina, $paginacion);
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
  <link rel="icon" type="image/png" href="fotos/logo.png">
  <!-- cambio de estilos para que las imagenes de los productos se vean mas grande -->
  <style>
    
    .product-img {
      width: 100%;
      height: 260px; 
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
  
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="index.php">Cooperativa Fenicios</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link active" href="producto.php">Productos</a></li>
            <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
            <!-- Se comprueba si el que inicio sesion es el admin, si es asi lo redirige a la pagina de modificaciones -->
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@ejemplo.com'): ?>
              <li class="nav-item"><a class="nav-link" href="admin/modificaciones.php">Modificaciones</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
          </ul>
          <div class="d-flex align-items-center">
            <!-- Si el usuario ha iniciado sesion, muestra su nombre y un boton para cerrar sesion, si no ha iniciado sesion muestra los botones de registro e inicio de sesion -->
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

  
  <main class="flex-grow-1 py-4">
    <div class="container">
      <h2 class="mb-4">Nuestros Productos</h2>

      <!-- en el valor de filtro se usa esa consulta en value para que no se borre el texto introducido en la busqueda, asi siempre sabes que es lo ultimo que has puesto para buscar-->
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
                // Se recorre el array de categorías y se crea un checkbox para cada una
                // Se usa un contador $i para crear filas de 3 columnas
                //categorias[] es el array que guarda las ids de las categorias seleccionadas,
                //con checked se comprueba si la categoria esta seleccionada, y se marca el checkbox si es asi
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
        <!-- Si no hay productos, se muestra un mensaje de alerta -->
        <?php if ($res->num_rows === 0): ?>
          <div class="col-12">
            <div class="alert alert-warning text-center">No se encontraron productos con los filtros seleccionados.</div>
          </div>
        <?php endif; ?>
        <?php while ($prod = $res->fetch_assoc()): ?>
        <?php
          // se obtienen las categorías del producto actual,
          // se hace una consulta a la base de datos para obtener los nombres de las categorías asociadas al producto
          $catRes = $conexion->query("SELECT c.nombre FROM categorias c INNER JOIN producto_categoria pc ON c.id = pc.categoria_id WHERE pc.producto_id = " . intval($prod['id']));
          $catNombres = [];
          while ($catRow = $catRes->fetch_assoc()) {
            $catNombres[] = $catRow['nombre'];
          }
        ?>
        <!-- Se crea una tarjeta para cada producto 
         la cual se ira rellenando haciendo consultas dinamicas en la base de datos
         la variable $prod es una array que contiene los datos del producto, gracias a ellos se rellenan las cards-->
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
                
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <!-- Muestra la barra de navegacion de la paginacion
       solo se muestra si hay mas de una pagina que mostrar -->
      <?php if ($totalPaginas > 1): ?>
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
      <?php endif; ?>
      

    </div>
  </main>

  <footer class="footer-custom text-white text-center py-3 mt-4">
    <div class="container">
      <small>&copy; 2025 Cooperativa Fenicios. Todos los derechos reservados.</small>
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