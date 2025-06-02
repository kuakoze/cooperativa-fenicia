function verificarUsuario($db, $email, $password) {
    try {
        // Buscar el usuario por email
        $stmt = $db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar la contraseña usando password_verify
            if (password_verify($password, $usuario['password'])) {
                // Usuario autenticado correctamente
                return $usuario; // o return true;
            } else {
                return false; // Contraseña incorrecta
            }
        } else {
            return false; // Usuario no encontrado
        }
    } catch (PDOException $e) {
        echo "Error en la verificación: " . $e->getMessage();
        return false;
    }
}


