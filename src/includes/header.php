<?php
session_start();
?>
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
        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@ejemplo.com'): ?>
          <li class="nav-item"><a class="nav-link" href="admin/modificaciones.php">Modificaciones</a></li>
        <?php endif; ?>
        <li class="nav-item">
          <?php if (isset($_SESSION['usuario']) && isset($_SESSION['email'])): ?>
            <a class="nav-link" href="carrito.php">Carrito</a>
          <?php else: ?>
            <a class="nav-link" href="#" onclick="mostrarModalRegistro();return false;">Carrito</a>
          <?php endif; ?>
        </li>
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
<script>
function mostrarModalRegistro() {
  var modal = new bootstrap.Modal(document.getElementById('modalAvisoRegistro'));
  modal.show();
}
</script>

<!-- MODAL SOLO MENSAJE REGISTRO (debe estar presente en todas las páginas con navbar) -->
<div class="modal fade" id="modalAvisoRegistro" tabindex="-1" aria-labelledby="modalAvisoRegistroLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAvisoRegistroLabel">Aviso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        Para añadir al carrito necesitas registrarte.
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link" href="/src/admin/modificaciones.php">Modificaciones</a>
    </li>
<?php endif; ?>

<?php
session_start();
var_dump($_SESSION);
?>