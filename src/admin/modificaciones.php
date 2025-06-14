<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@ejemplo.com') {
    header('Location: ../index.php');
    exit();
}
require_once '../conexiondb.php';
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
        <a class="navbar-brand" >Cooperativa Fenicios</a>
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
          <option selected value="">Elige una opción</option>
          <option value="1">Subir un producto</option>
          <option value="2">Modificar un producto</option>
          <option value="3">Eliminar un producto</option>
          <option value="4">Crear una nueva categoría</option>
          <option value="5">Eliminar una categoría</option>
          <option value="6">Ver pedidos</option>
        </select>
      </form>

      <!-- Formulario para subir producto (oculto por defecto) -->
      <div id="formSubirProducto" class="card p-4 mb-3" style="display:none;">
        <h5 class="mb-3">Subir nuevo producto</h5>
        <form method="POST" action="subir_producto.php" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="nombreProducto" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombreProducto" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="descripcionProducto" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcionProducto" name="descripcion" required></textarea>
          </div>
          <div class="mb-3">
            <label for="precioProducto" class="form-label">Precio</label>
            <input type="number" step="0.01" class="form-control" id="precioProducto" name="precio" required>
          </div>
          <div class="mb-3">
            <label for="stockProducto" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stockProducto" name="stock" required>
          </div>
          <div class="mb-3">
            <label for="imagenProducto" class="form-label">Imagen</label>
            <input type="file" class="form-control" id="imagenProducto" name="imagen" accept="image/*" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Categorías</label>
            <div>
              <?php
              $res = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
              while ($row = $res->fetch_assoc()):
              ?>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" id="cat_<?php echo $row['id']; ?>" name="categorias[]" value="<?php echo $row['id']; ?>">
                  <label class="form-check-label" for="cat_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></label>
                </div>
              <?php endwhile; ?>
            </div>
            <small class="form-text text-muted">Selecciona una o varias categorías.</small>
          </div>
          <button type="submit" class="btn btn-success">Subir producto</button>
        </form>
      </div>

      <!-- Formulario para eliminar producto (oculto por defecto) -->
      <div id="formEliminarProducto" class="card p-4 mb-3" style="display:none;">
        <h5 class="mb-3">Eliminar producto</h5>
        <form method="POST" action="eliminar_producto.php">
          <div class="mb-3">
            <label for="productoEliminar" class="form-label">Seleccione el producto a eliminar</label>
            <select class="form-select" id="productoEliminar" name="producto_id" required>
              <option value="">Seleccione un producto</option>
              <?php
              $res = $conexion->query("SELECT id, nombre FROM productos ORDER BY nombre ASC");
              while ($row = $res->fetch_assoc()):
              ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que desea eliminar este producto?');">Eliminar</button>
        </form>
      </div>

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

      <!-- Tabla para modificar productos (oculta por defecto) -->
      <div id="tablaModificarProducto" class="card p-4 mb-3" style="display:none;">
        <h5 class="mb-3">Modificar productos</h5>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Categorías</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $productos = $conexion->query("SELECT * FROM productos");
              while ($prod = $productos->fetch_assoc()):
                // Obtener categorías del producto
                $catRes = $conexion->query("SELECT categoria_id FROM producto_categoria WHERE producto_id = " . $prod['id']);
                $prodCats = [];
                while ($catRow = $catRes->fetch_assoc()) {
                  $prodCats[] = $catRow['categoria_id'];
                }
                // Obtener todas las categorías
                $allCats = $conexion->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
              ?>
              <tr>
                <form method="POST" action="modificar_producto.php" enctype="multipart/form-data">
                  <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                  <td><input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($prod['nombre']); ?>" required></td>
                  <td><textarea class="form-control" name="descripcion" required><?php echo htmlspecialchars($prod['descripcion']); ?></textarea></td>
                  <td><input type="number" step="0.01" class="form-control" name="precio" value="<?php echo $prod['precio']; ?>" required></td>
                  <td><input type="number" class="form-control" name="stock" value="<?php echo $prod['stock']; ?>" required></td>
                  <td>
                    <?php if (!empty($prod['imagen'])): ?>
                      <img src="../<?php echo htmlspecialchars($prod['imagen']); ?>" alt="Imagen" style="max-width:60px;max-height:60px;">
                    <?php endif; ?>
                    <input type="file" class="form-control mt-2" name="imagen" accept="image/*">
                  </td>
                  <td>
                    <?php while ($cat = $allCats->fetch_assoc()): ?>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categorias[]" value="<?php echo $cat['id']; ?>"
                          <?php if (in_array($cat['id'], $prodCats)) echo 'checked'; ?>>
                        <label class="form-check-label"><?php echo htmlspecialchars($cat['nombre']); ?></label>
                      </div>
                    <?php endwhile; ?>
                  </td>
                  <td>
                    <button type="submit" class="btn btn-warning btn-sm">Modificar</button>
                  </td>
                </form>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tabla para ver pedidos (oculta por defecto) -->
      <div id="tablaVerPedidos" class="card p-4 mb-3" style="display:none;">
        <h5 class="mb-3">Pedidos realizados</h5>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>ID Pedido</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Detalles</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $pedidos = $conexion->query("SELECT p.id, u.email, p.fecha, p.total 
                                           FROM pedidos p 
                                           JOIN usuarios u ON p.usuario_id = u.id 
                                           ORDER BY p.fecha DESC");
              while ($pedido = $pedidos->fetch_assoc()):
              ?>
              <tr>
                <td><?php echo $pedido['id']; ?></td>
                <td><?php echo htmlspecialchars($pedido['email']); ?></td>
                <td><?php echo $pedido['fecha']; ?></td>
                <td><?php echo number_format($pedido['total'], 2); ?> €</td>
                <td>
                  <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#detalles_<?php echo $pedido['id']; ?>">
                    Ver detalles
                  </button>
                </td>
              </tr>
              <tr class="collapse" id="detalles_<?php echo $pedido['id']; ?>">
                <td colspan="5">
                  <strong>Productos:</strong>
                  <ul class="mb-0">
                    <?php
                    $detalles = $conexion->query("SELECT dp.cantidad, dp.precio_unitario, pr.nombre 
                                                  FROM detalle_pedido dp 
                                                  JOIN productos pr ON dp.producto_id = pr.id 
                                                  WHERE dp.pedido_id = " . intval($pedido['id']));
                    while ($detalle = $detalles->fetch_assoc()):
                    ?>
                      <li>
                        <?php echo htmlspecialchars($detalle['nombre']); ?> - 
                        <?php echo intval($detalle['cantidad']); ?> ud. x 
                        <?php echo number_format($detalle['precio_unitario'], 2); ?> €
                      </li>
                    <?php endwhile; ?>
                  </ul>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
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
  <script>
    // Mostrar/ocultar formularios según la opción seleccionada
    document.getElementById('modSelect').addEventListener('change', function() {
      const formCat = document.getElementById('formNuevaCategoria');
      const formDelCat = document.getElementById('formEliminarCategoria');
      const formSubirProd = document.getElementById('formSubirProducto');
      const tablaModProd = document.getElementById('tablaModificarProducto');
      const formDelProd = document.getElementById('formEliminarProducto');
      const tablaVerPedidos = document.getElementById('tablaVerPedidos');
      if (this.value === '4') {
        formCat.style.display = 'block';
        formDelCat.style.display = 'none';
        formSubirProd.style.display = 'none';
        tablaModProd.style.display = 'none';
        formDelProd.style.display = 'none';
        tablaVerPedidos.style.display = 'none';
      } else if (this.value === '5') {
        formCat.style.display = 'none';
        formDelCat.style.display = 'block';
        formSubirProd.style.display = 'none';
        tablaModProd.style.display = 'none';
        formDelProd.style.display = 'none';
        tablaVerPedidos.style.display = 'none';
      } else if (this.value === '1') {
        formCat.style.display = 'none';
        formDelCat.style.display = 'none';
        formSubirProd.style.display = 'block';
        tablaModProd.style.display = 'none';
        formDelProd.style.display = 'none';
        tablaVerPedidos.style.display = 'none';
      } else if (this.value === '2') {
        formCat.style.display = 'none';
        formDelCat.style.display = 'none';
        formSubirProd.style.display = 'none';
        tablaModProd.style.display = 'block';
        formDelProd.style.display = 'none';
        tablaVerPedidos.style.display = 'none';
      } else if (this.value === '3') {
        formCat.style.display = 'none';
        formDelCat.style.display = 'none';
        formSubirProd.style.display = 'none';
        tablaModProd.style.display = 'none';
        formDelProd.style.display = 'block';
        tablaVerPedidos.style.display = 'none';
      } else if (this.value === '6') {
        formCat.style.display = 'none';
        formDelCat.style.display = 'none';
        formSubirProd.style.display = 'none';
        tablaModProd.style.display = 'none';
        formDelProd.style.display = 'none';
        tablaVerPedidos.style.display = 'block';
      } else {
        formCat.style.display = 'none';
        formDelCat.style.display = 'none';
        formSubirProd.style.display = 'none';
        tablaModProd.style.display = 'none';
        formDelProd.style.display = 'none';
        tablaVerPedidos.style.display = 'none';
      }
    });
  </script>
</body>
</html>