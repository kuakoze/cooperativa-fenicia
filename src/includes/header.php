<?php
session_start();
$isAdmin = (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@ejemplo.com');
?>
<?php if ($isAdmin): ?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
  <div class="container">
    <a class="navbar-brand" href="../index.php">Cooperativa Fenicia</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) == 'modificaciones.php' ? ' active' : '';?>" href="admin/modificaciones.php">Modificaciones</a></li>
        <li class="nav-item"><a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) == 'buzon.php' ? ' active' : '';?>" href="admin/buzon.php">Buzon</a></li>
        <li class="nav-item"><a class="nav-link<?php echo basename($_SERVER['PHP_SELF']) == 'pedidos.php' ? ' active' : '';?>" href="admin/pedidos.php">Pedidos</a></li>
      </ul>
      <div class="d-flex align-items-center">
        <span class="me-3 text-white fw-bold">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span>
        <form method="POST" action="logout.php" class="d-inline">
          <button type="submit" class="btn btn-outline-light">Cerrar sesión</button>
        </form>
      </div>
    </div>
  </div>
</nav>
<?php else: ?>
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
        <li class="nav-item"><a class="nav-link" href="contacto.php">Contacto</a></li>
        <li class="nav-item"><a class="nav-link" href="mi_cuenta.php">Mi cuenta</a></li>
        <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
      </ul>
      <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['usuario'])): ?>
          <span class="me-3 text-white fw-bold">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?>!</span>
          <form method="POST" action="logout.php" class="d-inline">
            <button type="submit" class="btn btn-outline-light">Cerrar sesión</button>
          </form>
        <?php else: ?>
          <a href="registro.php" class="btn btn-light text-success me-2">Registrarse</a>
          <a href="login.php" class="btn btn-outline-light">Iniciar sesión</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<?php endif; ?>