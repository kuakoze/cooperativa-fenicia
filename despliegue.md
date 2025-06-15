
# Explicación del archivo `docker-compose.yml`

Este archivo `docker-compose.yml` define un entorno multi-contenedor para una aplicación PHP con base de datos MariaDB y PhpMyAdmin para administración visual.

---

## Versión de Compose

```yaml
version: '3.8'
```
Especifica la versión del esquema de Docker Compose. La versión 3.8 es ampliamente compatible y recomendada para proyectos modernos.

---

## Servicios

### 1. **web**

```yaml
web:
  build: .
  ports:
    - "8080:80"
  volumes:
    - ./src:/var/www/html
  depends_on:
    - db
```
- **build: .** – Construye la imagen desde el Dockerfile en el directorio actual.
- **ports: "8080:80"** – Expone el puerto 80 del contenedor (Apache) en el puerto 8080 de tu máquina local.
- **volumes: ./src:/var/www/html** – Sincroniza el código fuente local (`./src`) con la carpeta pública de Apache en el contenedor, permitiendo desarrollo en "directo"
- **depends_on: db** – Asegura que el servicio de base de datos esté iniciado antes de arrancar el servidor web.

---

### 2. **db**

```yaml
db:
  image: mariadb:10.5
  restart: always
  environment:
    MYSQL_ROOT_PASSWORD: admin
    MYSQL_DATABASE: cooperativa_fenicia
    MYSQL_USER: admin
    MYSQL_PASSWORD: admin
  volumes:
    - db_data:/var/lib/mysql
    - ./db/init.sql:/docker-entrypoint-initdb.d/init.sql
```
- **image: mariadb:10.5** – Usa la imagen oficial de MariaDB versión 10.5.
- **restart: always** – Reinicia el contenedor automáticamente en caso de fallo.
- **environment:** – Variables para inicializar la base de datos y credenciales.
- **volumes:**
  - `db_data:/var/lib/mysql` – Persiste los datos de la base de datos en un volumen nombrado, manteniendo la información aunque el contenedor sea eliminado.
  - `./db/init.sql:/docker-entrypoint-initdb.d/init.sql` – Ejecuta un script de inicialización (`init.sql`) al crear la base de datos por primera vez.

---

### 3. **phpmyadmin**

```yaml
phpmyadmin:
  image: phpmyadmin/phpmyadmin
  restart: always
  ports:
    - "8081:80"
  environment:
    PMA_HOST: db
    PMA_PORT: 3306
    PMA_USER: root
    PMA_PASSWORD: admin
  depends_on:
    - db
```
- **image: phpmyadmin/phpmyadmin** – Usa la imagen oficial de PhpMyAdmin.
- **ports: "8081:80"** – Expone PhpMyAdmin en el puerto 8081 de tu máquina local.
- **environment:** – Configura la conexión a la base de datos MariaDB.
- **depends_on: db** – Se asegura de que la base de datos esté disponible antes de iniciar PhpMyAdmin.

---

## Volúmenes

```yaml
volumes:
  db_data:
```
- **db_data:** – Volumen persistente para los archivos de la base de datos, asegurando que los datos no se pierdan al recrear el contenedor.

---


# Explicación del Dockerfile

Este Dockerfile crea una imagen lista para ejecutar aplicaciones PHP con Apache, habilitando la conexión a bases de datos MySQL y permitiendo personalizaciones en la configuración del servidor web mediante el uso de un archivo de Virtual Host personalizado y el módulo de reescritura de Apache.

---

## 1. Imagen Base

```dockerfile
FROM php:8.2-apache
```
Se utiliza como base la imagen oficial de PHP 8.2 con Apache. Esta imagen incluye PHP y el servidor web Apache preconfigurados.

---

## 2. Instalación de Extensiones PHP

```dockerfile
RUN docker-php-ext-install mysqli pdo pdo_mysql
```
Instala las extensiones de PHP necesarias para conectarse y trabajar con bases de datos MySQL:
- `mysqli`: Extensión para interactuar con bases de datos MySQL usando la interfaz mejorada.
- `pdo` y `pdo_mysql`: Proveen una capa de abstracción para bases de datos y permiten conectar PHP con MySQL a través de PDO.

---

## 3. Configuración Personalizada de Apache

```dockerfile
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
```
Copia el archivo de configuración personalizado del Virtual Host de Apache desde el repositorio (`.docker/apache/vhost.conf`) al archivo de configuración por defecto de Apache en el contenedor. Esto permite personalizar el comportamiento del servidor web (por ejemplo, rutas, permisos, redirecciones, etc.).

---

## 4. Habilitar Módulo de Reescritura

```dockerfile
RUN a2enmod rewrite
```
Activa el módulo `mod_rewrite` de Apache, necesario para permitir reescrituras de URLs. Esto es comúnmente utilizado en aplicaciones PHP modernas (por ejemplo, frameworks como Laravel, Symfony, etc.) para gestionar rutas amigables.

---
