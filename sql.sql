CREATE DATABASE IF NOT EXISTS jairoapi_envios_repositorio
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE jairoapi_envios_repositorio;

CREATE TABLE IF NOT EXISTS envios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinatario VARCHAR(150) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO envios (destinatario, direccion, descripcion) VALUES
('Carlos Pérez', 'Carrera 10 # 20-15, Cali', 'Documentos administrativos'),
('María Gómez', 'Calle 8 # 15-22, Bogotá', 'Ropa y accesorios'),
('Andrés Rodríguez', 'Carrera 45 # 30-18, Medellín', 'Equipo electrónico'),
('Laura Martínez', 'Calle 12 # 9-40, Barranquilla', 'Libros y material educativo'),
('Juan Torres', 'Carrera 7 # 50-12, Pereira', 'Productos de oficina'),
('Ana López', 'Calle 25 # 18-33, Armenia', 'Regalos personales'),
('Diego Sánchez', 'Carrera 6 # 11-09, Manizales', 'Repuestos para computador'),
('Sofía Ramírez', 'Calle 40 # 22-17, Cartagena', 'Artículos para el hogar'),
('Miguel Castro', 'Carrera 19 # 14-26, Bucaramanga', 'Accesorios tecnológicos'),
('Valentina Herrera', 'Calle 5 # 32-10, Cali', 'Papelería y útiles escolares');
