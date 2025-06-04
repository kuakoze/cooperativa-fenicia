<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Página de Inicio - Cooperativa Agrícola</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Colores personalizados */
    .bg-verde {
      background-color: #4CAF50; /* Verde principal */
    }
    .bg-verde-oscuro {
      background-color: #388E3C; /* Verde más oscuro */
    }
    .bg-verde-claro {
      background-color: #81C784; /* Verde claro */
    }
    .text-verde {
      color: #388E3C;
    }
    .text-verde-claro {
      color: #81C784;
    }
    
    /* Hover personalizado para el botón "Entrar" */
    .btn-verde:hover {
      background-color: #388E3C; /* Verde más oscuro en hover */
      color: white; /* Texto blanco en hover */
      border-color: #388E3C; /* Borde en hover */
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header class="bg-verde text-white text-center py-4">
    <h1>Bienvenido a la Cooperativa Agrícola</h1>
  </header>

  <!-- Main Content -->
  <main class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%; border: 1px solid #81C784;">
      <h2 class="text-center mb-4 text-verde">Iniciar Sesión</h2>
      <form>
        <div class="mb-3">
          <label for="usuario" class="form-label text-verde">Usuario</label>
          <input type="text" class="form-control" id="usuario" placeholder="Introduce tu usuario">
        </div>
        <div class="mb-3">
          <label for="contrasena" class="form-label text-verde">Contraseña</label>
          <input type="password" class="form-control" id="contrasena" placeholder="Introduce tu contraseña">
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn bg-verde text-white btn-verde">Entrar</button>
          <!-- Aquí se coloca el enlace al "Quiénes Somos" -->
          <a href="index.php" class="btn btn-outline-success">Entrar como visitante</a>
        </div>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-verde-oscuro text-white text-center py-3 mt-auto">
    <p>&copy; 2025 Cooperativa Agrícola. Todos los derechos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
