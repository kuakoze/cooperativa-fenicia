function crearTablas($db) {
    try {
        // Tabla usuario
        $comprobacion1 = $db->query("SHOW TABLES LIKE 'usuario'");
        if ($comprobacion1->rowCount() == 0) {
            $stmt = $db->prepare("CREATE TABLE usuario (
                id_usuario INT AUTO_INCREMENT PRIMARY KEY,
                nombre VARCHAR(100) NOT NULL,
                apellidos VARCHAR(100) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                direccion VARCHAR(255),
                telefono VARCHAR(20)
            ) ENGINE=InnoDB;");
            $stmt->execute();
        }

        // Tabla pedido
        $comprobacion2 = $db->query("SHOW TABLES LIKE 'pedido'");
        if ($comprobacion2->rowCount() == 0) {
            $stmt = $db->prepare("CREATE TABLE pedido (
                id_pedido INT AUTO_INCREMENT PRIMARY KEY,
                id_usuario INT NOT NULL,
                fecha DATETIME NOT NULL,
                coste DECIMAL(10,2) NOT NULL,
                FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
            ) ENGINE=InnoDB;");
            $stmt->execute();
        }

        // Tabla producto
        $comprobacion3 = $db->query("SHOW TABLES LIKE 'producto'");
        if ($comprobacion3->rowCount() == 0) {
            $stmt = $db->prepare("CREATE TABLE producto (
                id_producto INT AUTO_INCREMENT PRIMARY KEY,
                nombre_producto VARCHAR(255) NOT NULL,
                descripcion TEXT,
                precio DECIMAL(10,2) NOT NULL,
                id_categoria INT,
                FOREIGN KEY (id_categoria) REFERENCES categoria_productos(id_categoria) ON DELETE SET NULL
            ) ENGINE=InnoDB;");
            $stmt->execute();
        }

        // Tabla detalles_pedido
        $comprobacion4 = $db->query("SHOW TABLES LIKE 'detalles_pedido'");
        if ($comprobacion4->rowCount() == 0) {
            $stmt = $db->prepare("CREATE TABLE detalles_pedido (
                id_detalles INT AUTO_INCREMENT PRIMARY KEY,
                id_pedido INT NOT NULL,
                id_producto INT NOT NULL,
                cantidad INT NOT NULL,
                total DECIMAL(10,2) NOT NULL,
                FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido) ON DELETE CASCADE,
                FOREIGN KEY (id_producto) REFERENCES producto(id_producto) ON DELETE CASCADE
            ) ENGINE=InnoDB;");
            $stmt->execute();
        }

        // Tabla categoria_productos
        $comprobacion5 = $db->query("SHOW TABLES LIKE 'categoria_productos'");
        if ($comprobacion5->rowCount() == 0) {
            $stmt = $db->prepare("CREATE TABLE categoria_productos (
                id_categoria INT AUTO_INCREMENT PRIMARY KEY,
                nombre_categoria VARCHAR(100) NOT NULL
            ) ENGINE=InnoDB;");
            $stmt->execute();
        }

    } catch (PDOException $e) {
        echo "Error al crear las tablas: " . $e->getMessage();
    }
}