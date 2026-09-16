<?php
// Configuración de conexión a MySQL
$host = "mysql-jairoapi.alwaysdata.net";
$user = "jairoapi";
$pass = "clase1234";
$db   = "jairoapi_envios_repositorio";

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Error de conexión a MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Crear la base de datos si no existe
$sql = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$conn->query($sql)) {
    die("Error al crear la base de datos: " . $conn->error);
}

if (!$conn->select_db($db)) {
    die("Error al seleccionar la base de datos: " . $conn->error);
}

// Crear tabla si no existe
$sql = "CREATE TABLE IF NOT EXISTS envios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinatario VARCHAR(150) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($sql)) {
    die("Error al crear la tabla: " . $conn->error);
}

// Insertar 10 registros de ejemplo solamente si la tabla está vacía
$result = $conn->query("SELECT COUNT(*) AS total FROM envios");
$row = $result->fetch_assoc();

if ((int)$row['total'] === 0) {
    $datos = [
        ["Carlos Pérez", "Carrera 10 # 20-15, Cali", "Documentos administrativos"],
        ["María Gómez", "Calle 8 # 15-22, Bogotá", "Ropa y accesorios"],
        ["Andrés Rodríguez", "Carrera 45 # 30-18, Medellín", "Equipo electrónico"],
        ["Laura Martínez", "Calle 12 # 9-40, Barranquilla", "Libros y material educativo"],
        ["Juan Torres", "Carrera 7 # 50-12, Pereira", "Productos de oficina"],
        ["Ana López", "Calle 25 # 18-33, Armenia", "Regalos personales"],
        ["Diego Sánchez", "Carrera 6 # 11-09, Manizales", "Repuestos para computador"],
        ["Sofía Ramírez", "Calle 40 # 22-17, Cartagena", "Artículos para el hogar"],
        ["Miguel Castro", "Carrera 19 # 14-26, Bucaramanga", "Accesorios tecnológicos"],
        ["Valentina Herrera", "Calle 5 # 32-10, Cali", "Papelería y útiles escolares"]
    ];

    $stmt = $conn->prepare("INSERT INTO envios (destinatario, direccion, descripcion) VALUES (?, ?, ?)");
    foreach ($datos as $dato) {
        $stmt->bind_param("sss", $dato[0], $dato[1], $dato[2]);
        $stmt->execute();
    }
    $stmt->close();
}
?>
