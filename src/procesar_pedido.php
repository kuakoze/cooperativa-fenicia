<?php
//con esta funcion el servidor guarda la informacion sobre el visitante, 
//en caso de que seleccione algun producto, inicie sesion, o filtre, cualquier accion en resumen
session_start();
//Se utiliza para conectar a la base de datos
require_once 'conexiondb.php';



// Verificar si el usuario está autenticado y si hay productos en el carrito
//$carrito guarda los productos del carrito, si no hay productos
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$carrito = $_SESSION['carrito'] ?? [];
if (!$carrito) {
    header('Location: carrito.php');
    exit();
}

// en $email se guarda el correo del usuario que ha iniciado sesion
// y se busca su id en la base de datos
// $res guarda el resultado de la consulta
//$user guarda el resultado de la consulta en un array asociativo
// y $usuario_id guarda el id del usuario
// si no se encuentra el usuario, se redirige al carrito
// si se encuentra, se guarda su id en $usuario_id
$email = $_SESSION['email'];
$res = $conexion->query("SELECT id FROM usuarios WHERE email = '" . $conexion->real_escape_string($email) . "' LIMIT 1");
$user = $res->fetch_assoc();
$usuario_id = $user ? $user['id'] : null;

if (!$usuario_id) {
    header('Location: carrito.php');
    exit();
}

// aqui se va a preparar el pedido y calcular el total
// $productos guarda los productos del carrito con su id, precio, cantidad y subtotal
// $total guarda el total del pedido
//$ids guarda los ids de los productos del carrito como una cadena separada por comas
//$res guarda el resultado de la consulta a la base de datos para obtener los productos del carrito
// while recorre los resultados de la consulta y calcula el subtotal de cada producto
//$row guarda cada fila del resultado de la consulta
//el total se calcula sumando los subtotales de cada producto
$total = 0;
$productos = [];
$ids = implode(',', array_map('intval', array_keys($carrito)));
$res = $conexion->query("SELECT * FROM productos WHERE id IN ($ids)");
while ($row = $res->fetch_assoc()) {
    $cantidad = $carrito[$row['id']];
    $subtotal = $cantidad * $row['precio'];
    $productos[] = [
        'id' => $row['id'],
        'precio' => $row['precio'],
        'cantidad' => $cantidad,
        'subtotal' => $subtotal
    ];
    $total += $subtotal;
}

// con esta consulta se guarda el pedido en la tabla "pedidos"
$stmt = $conexion->prepare("INSERT INTO pedidos (usuario_id, fecha, total) VALUES (?, NOW(), ?)");
$stmt->bind_param('id', $usuario_id, $total);
$stmt->execute();
$pedido_id = $stmt->insert_id;
$stmt->close();

// con esta consulta se guardan los detalles en la tabla "detalle_pedido"
$stmt = $conexion->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
foreach ($productos as $prod) {
    $stmt->bind_param('iiid', $pedido_id, $prod['id'], $prod['cantidad'], $prod['precio']);
    $stmt->execute();
}
$stmt->close();

// con unset se limpia el carrito de la sesión
unset($_SESSION['carrito']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pedido procesado - Cooperativa Fenicia</title>
  <link href="estilos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="icon" type="image/png" href="fotos/logo.png">
</head>
<body class="d-flex flex-column min-vh-100">
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
      <div class="container">
        <a class="navbar-brand" href="index.php">Cooperativa Fenicios</a>
      </div>
    </nav>
  </header>
  <main class="flex-grow-1 py-4">
    <div class="container">
      <div class="alert alert-success text-center">
        <h2>¡Pedido realizado con éxito!</h2>
        <p>Tu pedido ha sido procesado correctamente. Gracias por confiar en nosotros.</p>
        <a href="producto.php" class="btn btn-success mt-3">Seguir comprando</a>
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