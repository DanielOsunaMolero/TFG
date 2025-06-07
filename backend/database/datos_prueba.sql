INSERT INTO usuario (nombre, email, password, tipo) VALUES
('Carlos Moreno', 'carlos@weekend.com', '1234', 'propietario'),
('Lucía Torres', 'lucia@weekend.com', '1234', 'propietario'),
('Antonio Pérez', 'antonio@weekend.com', '1234', 'visitante'),
('Sandra Gómez', 'sandra@weekend.com', '1234', 'visitante'),
('Daniel Osuna', 'dosuna981@gmail.com', '1234', 'propietario');


INSERT INTO casa_rural (id_propietario, titulo, descripcion, ubicacion, precio_noche, servicios, disponibilidad)
VALUES
(9, 'La Cabaña del Lago', 'Cabaña de madera con vistas al lago, perfecta para desconectar.', 'Iznájar, Córdoba', 95, 'wifi,jacuzzi,parking,calefacción', '2024-06-15 a 2024-11-30'),
(9, 'Casa del Olivar', 'Acogedora casa rural entre olivos centenarios.', 'Zuheros, Córdoba', 85, 'wifi,piscina,chimenea,barbacoa,parking', '2024-07-01 a 2024-12-31');


INSERT INTO reserva (id_usuario, id_casa, fecha_inicio, fecha_fin, estado, importe_total)
VALUES
(3, 1, '2024-08-01', '2024-08-05', 'pendiente', 340),
(4, 2, '2024-09-10', '2024-09-12', 'confirmada', 190);
