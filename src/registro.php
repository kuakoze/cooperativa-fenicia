<?php
// Iniciar sesión y conectar a la base de datos
session_start();
require_once 'conexion.php'; // Asegúrate de incluir tu archivo de conexión a la base de datos

// Variable para mostrar mensajes de error o éxito
$mensaje = "";

// Comprobar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];

    // Validar que no estén vacíos
    if (!empty($nombre) && !empty($apellidos) && !empty($email) && !empty($password)) {
        // Cifrar la contraseña
        $hashPassword = password_hash($password, PASSWORD_BCRYPT);

        // Preparar la consulta para insertar el usuario en la base de datos
        $stmt = $db->prepare("INSERT INTO usuario (nombre, apellidos, email, password, direccion, telefono) 
                              VALUES (:nombre, :apellidos, :email, :password, :direccion, :telefono)");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashPassword);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':telefono', $telefono);

        try {
            // Ejecutar la consulta
            $stmt->execute();
            $mensaje = "¡Registro exitoso! Ahora puedes iniciar sesión.";
        } catch (PDOException $e) {
            $mensaje = "Error al registrar: " . $e->getMessage();
        }
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Aquí puedes agregar tu CSS -->
</head>
<body>
    <div class="form-container">
        <h2>Registro de Usuario</h2>
        
        <!-- Mostrar mensaje de error o éxito -->
        <?php if ($mensaje != ""): ?>
            <p class="mensaje"><?php echo $mensaje; ?></p>
        <?php endif; ?>

        <!-- Formulario de registro -->
        <form action="registro.php" method="POST">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div>
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion">
            </div>

            <div>
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono">
            </div>

            <div>
                <button type="submit">Registrar</button>
            </div>
        </form>

        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
    </div>
</body>
</html>
