-- TABLA USUARIOS
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    direccion VARCHAR(255),
    telefono VARCHAR(20),
    rol VARCHAR(20) NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB;

-- TABLA PRODUCTOS
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    imagen VARCHAR(255)
) ENGINE=InnoDB;

-- TABLA CATEGORÍAS
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- TABLA PRODUCTO_CATEGORIA 
CREATE TABLE IF NOT EXISTS producto_categoria (
    producto_id INT NOT NULL,
    categoria_id INT NOT NULL,
    PRIMARY KEY (producto_id, categoria_id),
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- TABLA PEDIDOS
CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- TABLA DETALLE_PEDIDO
CREATE TABLE IF NOT EXISTS detalle_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- TABLA MENSAJES DE CONTACTO
CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ... (todas las CREATE TABLE ...)

-- Insertar categorías de ejemplo
INSERT INTO categorias (nombre) VALUES
('Control de plagas'),
('fertilizantes'),
('fungicidas'),
('mallas'),
('plasticos'),
('polinizadores'),
('rafias'),
('otros');


-- Insertar productos de ejemplo
INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES
('Amblyseius californicus', 'Control biológico de plagas: ácaros.', 10.00, 100, 'fotos/control de plagas/californicus.jpg'),
('Chrysopa', 'Insecto depredador de plagas.', 12.00, 80, 'fotos/control de plagas/chrysopa.jpg'),
('Encarsia', 'Avispa parásita para control de mosca blanca.', 15.00, 60, 'fotos/control de plagas/encarsia.jpg'),
('Eretmocerus eremicus', 'Control biológico de mosca blanca.', 14.00, 70, 'fotos/control de plagas/eremicus.jpg'),
('Orius', 'Depredador de trips y otros insectos.', 13.00, 90, 'fotos/control de plagas/orius.jpg'),
('Amblyseius swirskii', 'Control de trips y mosca blanca.', 16.00, 50, 'fotos/control de plagas/swirskii.jpg'),

('Calpower', 'Fertilizante líquido de calcio.', 8.00, 120, 'fotos/FERTILIZANTES/calpower.jpg'),
('Fertimix', 'Mezcla de nutrientes para plantas.', 9.00, 110, 'fotos/FERTILIZANTES/fertimix.jpg'),
('Floraddor', 'Fertilizante para floración.', 11.00, 100, 'fotos/FERTILIZANTES/floraddor.jpg'),
('Orgaplant', 'Fertilizante orgánico.', 10.00, 90, 'fotos/FERTILIZANTES/orgaplant.jpg'),
('Scudor', 'Fertilizante especial para cultivos.', 12.00, 80, 'fotos/FERTILIZANTES/scudor.jpg'),

('Azufre', 'Fungicida natural.', 7.00, 150, 'fotos/fungicidas/azufre.jpg'),
('Codimur', 'Fungicida para control de mildiu.', 13.00, 70, 'fotos/fungicidas/codimur.jpg'),
('Cuprisul', 'Fungicida a base de cobre.', 14.00, 60, 'fotos/fungicidas/cuprisul.jpg'),
('Teldor', 'Fungicida para botritis.', 15.00, 50, 'fotos/fungicidas/teldor.jpg'),
('Tranil', 'Fungicida sistémico.', 16.00, 40, 'fotos/fungicidas/tranil.jpg'),

('Malla 1', 'Malla agrícola resistente.', 20.00, 30, 'fotos/mallas/malla1.png'),
('Malla 2', 'Malla para sombreo.', 18.00, 25, 'fotos/mallas/malla2.jpg'),
('Malla 3', 'Malla anti-insectos.', 22.00, 20, 'fotos/mallas/malla3.jpg'),

('Plástico agrícola 1', 'Plástico para invernadero.', 30.00, 15, 'fotos/plasticos/plastico1.png'),
('Plástico agrícola 2', 'Plástico resistente a rayos UV.', 32.00, 10, 'fotos/plasticos/plastico2.png'),

('Colmena grande', 'Polinizador natural para cultivos.', 50.00, 5, 'fotos/POLINIZADORES/colmena_grande.jpg'),
('Colmena invierno', 'Colmena adaptada para invierno.', 55.00, 3, 'fotos/POLINIZADORES/colmena_invierno.jpg'),
('Colmena verano', 'Colmena adaptada para verano.', 53.00, 4, 'fotos/POLINIZADORES/colmena_verano.jpg'),

('Rafia blanca', 'Rafia para atado de plantas.', 5.00, 200, 'fotos/rafia/rafia_blanco.jpg'),
('Rafia', 'Rafia resistente.', 5.50, 180, 'fotos/rafia/rafia.jpg'),

('Carro', 'Carro para transporte agrícola.', 60.00, 8, 'fotos/otros/carro.png'),
('Matabi', 'Pulverizador manual.', 25.00, 12, 'fotos/otros/matabi.png'),
('Novi', 'Herramienta multiusos.', 18.00, 15, 'fotos/otros/novi.png'),
('Podar', 'Tijeras de podar.', 10.00, 30, 'fotos/otros/podar.png'),
('Rueda', 'Rueda de repuesto.', 8.00, 20, 'fotos/otros/rueda.png'),
('Tijeras', 'Tijeras para cosecha.', 9.00, 25, 'fotos/otros/tijeras.png');

-- Asociar productos a categorías
INSERT INTO producto_categoria (producto_id, categoria_id) VALUES
-- Control de plagas (categoria_id = 1)
(1, 1), -- Amblyseius californicus
(2, 1), -- Chrysopa
(3, 1), -- Encarsia
(4, 1), -- Eretmocerus eremicus
(5, 1), -- Orius
(6, 1), -- Amblyseius swirskii

-- Fertilizantes (categoria_id = 2)
(7, 2), -- Calpower
(8, 2), -- Fertimix
(9, 2), -- Floraddor
(10, 2), -- Orgaplant
(11, 2), -- Scudor

-- Fungicidas (categoria_id = 3)
(12, 3), -- Azufre
(13, 3), -- Codimur
(14, 3), -- Cuprisul
(15, 3), -- Teldor
(16, 3), -- Tranil

-- Mallas (categoria_id = 4)
(17, 4), -- Malla 1
(18, 4), -- Malla 2
(19, 4), -- Malla 3

-- Plásticos (categoria_id = 5)
(20, 5), -- Plástico agrícola 1
(21, 5), -- Plástico agrícola 2

-- Polinizadores (categoria_id = 6)
(22, 6), -- Colmena grande
(23, 6), -- Colmena invierno
(24, 6), -- Colmena verano

-- Rafias (categoria_id = 7)
(25, 7), -- Rafia blanca
(26, 7), -- Rafia

-- Otros (categoria_id = 8)
(27, 8), -- Carro
(28, 8), -- Matabi
(29, 8), -- Novi
(30, 8), -- Podar
(31, 8), -- Rueda
(32, 8); -- Tijeras
-- Se crea el admin
INSERT INTO usuarios (nombre, apellidos, email, password, direccion, telefono, rol)
VALUES ('admin', 'Principal', 'admin@ejemplo.com', '123admin456', 'Direccion', '123456789', 'admin');


