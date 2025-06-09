<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <li class="nav-item">
        <a class="nav-link" href="/src/admin/modificaciones.php">Modificaciones</a>
    </li>
<?php endif; ?>

<?php
session_start();
var_dump($_SESSION);
?>