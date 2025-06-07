CREATE DATABASE weekendhouse CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE weekendhouse;

CREATE TABLE usuario (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(100) NOT NULL,
  tipo ENUM('visitante', 'propietario', 'admin') NOT NULL DEFAULT 'visitante',
  fecha_registro DATE NOT NULL DEFAULT CURRENT_DATE
);

CREATE TABLE casa_rural (
  id_casa INT AUTO_INCREMENT PRIMARY KEY,
  id_propietario INT NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  descripcion TEXT,
  ubicacion VARCHAR(255),
  precio_noche FLOAT NOT NULL,
  servicios TEXT,
  disponibilidad TEXT,
  FOREIGN KEY (id_propietario) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

CREATE TABLE reserva (
  id_reserva INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_casa INT NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'pendiente',
  fecha_reserva DATE NOT NULL DEFAULT CURRENT_DATE,
  importe_total FLOAT NOT NULL,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
  FOREIGN KEY (id_casa) REFERENCES casa_rural(id_casa) ON DELETE CASCADE
);
