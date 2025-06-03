<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Página de Inicio</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <!-- Header -->
  <header class="bg-primary text-white text-center py-4">
    <h1>Bienvenido a Nuestra Plataforma</h1>
  </header>

  <!-- Main Content -->
  <main class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
      <h2 class="text-center mb-4">Iniciar Sesión</h2>
      <form>
        <div class="mb-3">
          <label for="usuario" class="form-label">Usuario</label>
          <input type="text" class="form-control" id="usuario" placeholder="Introduce tu usuario">
        </div>
        <div class="mb-3">
          <label for="contrasena" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="contrasena" placeholder="Introduce tu contraseña">
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Entrar</button>
          <button type="button" class="btn btn-outline-secondary">Entrar como visitante</button>
        </div>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <p>&copy; 2025 Nuestra Plataforma. Todos los derechos reservados.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
