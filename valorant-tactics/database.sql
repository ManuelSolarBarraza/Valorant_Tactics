CREATE DATABASE IF NOT EXISTS valorant_tactics CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE valorant_tactics;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS entrenamientos;
DROP TABLE IF EXISTS estrategias_tacticas;
DROP TABLE IF EXISTS estadisticas_jugador;
DROP TABLE IF EXISTS partidas_scrims;
DROP TABLE IF EXISTS mapas;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles_competitivos;
DROP TABLE IF EXISTS roles;
SET FOREIGN_KEY_CHECKS = 1;
CREATE TABLE roles (id_rol INT AUTO_INCREMENT PRIMARY KEY, nombre_rol VARCHAR(50) NOT NULL UNIQUE) ENGINE=InnoDB;
CREATE TABLE roles_competitivos (id_rol_competitivo INT AUTO_INCREMENT PRIMARY KEY,nombre_rol_competitivo VARCHAR(40) NOT NULL UNIQUE) ENGINE=InnoDB;
CREATE TABLE usuarios (id_usuario INT AUTO_INCREMENT PRIMARY KEY,nombre_completo VARCHAR(100) NOT NULL,nickname_valorant VARCHAR(50) NOT NULL,email VARCHAR(100) NOT NULL UNIQUE,password_hash VARCHAR(255) NOT NULL,id_rol INT NOT NULL,id_rol_competitivo INT NULL,fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_usuarios_roles FOREIGN KEY (id_rol) REFERENCES roles(id_rol),CONSTRAINT fk_usuarios_tacticos FOREIGN KEY (id_rol_competitivo) REFERENCES roles_competitivos(id_rol_competitivo)) ENGINE=InnoDB;
CREATE TABLE mapas (id_mapa INT AUTO_INCREMENT PRIMARY KEY,nombre_mapa VARCHAR(50) NOT NULL UNIQUE,activo_competitivo TINYINT(1) NOT NULL DEFAULT 0) ENGINE=InnoDB;
CREATE TABLE partidas_scrims (id_partida INT AUTO_INCREMENT PRIMARY KEY,fecha DATE NOT NULL,id_mapa INT NOT NULL,equipo_rival VARCHAR(100) NOT NULL,resultado ENUM('Victoria','Derrota','Empate') NOT NULL,rondas_ganadas INT NOT NULL,rondas_perdidas INT NOT NULL,observaciones TEXT NULL,FOREIGN KEY (id_mapa) REFERENCES mapas(id_mapa)) ENGINE=InnoDB;
CREATE TABLE estadisticas_jugador (id_stat INT AUTO_INCREMENT PRIMARY KEY,id_partida INT NOT NULL,id_usuario INT NOT NULL,id_rol_jugado INT NOT NULL,id_rol_competitivo INT NULL,agente_jugado VARCHAR(50) NOT NULL,asesinatos INT NOT NULL,muertes INT NOT NULL,asistencias INT NOT NULL,acs INT NOT NULL,porcentaje_hs DECIMAL(5,2) NOT NULL,UNIQUE KEY uq_partida_jugador(id_partida,id_usuario),FOREIGN KEY (id_partida) REFERENCES partidas_scrims(id_partida) ON DELETE CASCADE,FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),FOREIGN KEY (id_rol_jugado) REFERENCES roles(id_rol),CONSTRAINT fk_stats_tacticos FOREIGN KEY (id_rol_competitivo) REFERENCES roles_competitivos(id_rol_competitivo)) ENGINE=InnoDB;
CREATE TABLE estrategias_tacticas (id_estrategia INT AUTO_INCREMENT PRIMARY KEY,titulo VARCHAR(150) NOT NULL,id_mapa INT NOT NULL,bando ENUM('Atacante','Defensor') NOT NULL,agente_clave VARCHAR(50) NOT NULL,descripcion TEXT NOT NULL,link_vod VARCHAR(255) NULL,id_creador INT NOT NULL,fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY (id_mapa) REFERENCES mapas(id_mapa),FOREIGN KEY (id_creador) REFERENCES usuarios(id_usuario)) ENGINE=InnoDB;
CREATE TABLE entrenamientos (id_entrenamiento INT AUTO_INCREMENT PRIMARY KEY,id_jugador INT NOT NULL,id_coach INT NOT NULL,titulo_rutina VARCHAR(150) NOT NULL,descripcion_rutina TEXT NOT NULL,fecha_limite DATE NOT NULL,estado ENUM('Pendiente','Completado') DEFAULT 'Pendiente',FOREIGN KEY(id_jugador) REFERENCES usuarios(id_usuario),FOREIGN KEY(id_coach) REFERENCES usuarios(id_usuario)) ENGINE=InnoDB;
INSERT INTO roles(nombre_rol) VALUES ('Coach'),('Jugador Titular'),('Suplente'),('Parche'),('Tryout');
INSERT INTO roles_competitivos(nombre_rol_competitivo) VALUES ('Duelista'),('Iniciador'),('Flex'),('Smoker'),('Centinela');
INSERT INTO mapas(nombre_mapa,activo_competitivo) VALUES ('Ascent',0),('Bind',1),('Haven',1),('Split',0),('Lotus',1),('Sunset',1),('Icebox',0),('Abyss',1),('Breeze',0),('Fracture',0),('Pearl',0),('Corrode',1),('Summit',1);
INSERT INTO usuarios(nombre_completo,nickname_valorant,email,password_hash,id_rol,id_rol_competitivo) VALUES
('Entrenador Demo','CoachDemo','coach@valorantteam.cl','$2y$12$zfDYUTwf2vbP3.9I9ArxTOCRzgmYGMgsNoBYatLOI0mqCw4aCqzNa',1,NULL),
('Jugador Demo','PlayerDemo','jugador@valorantteam.cl','$2y$12$zfDYUTwf2vbP3.9I9ArxTOCRzgmYGMgsNoBYatLOI0mqCw4aCqzNa',2,1),
('Aspirante Demo','TryoutDemo','tryout@valorantteam.cl','$2y$12$zfDYUTwf2vbP3.9I9ArxTOCRzgmYGMgsNoBYatLOI0mqCw4aCqzNa',5,3),
('Jugador Dos','SovaMain','jugador2@valorantteam.cl','$2y$12$zfDYUTwf2vbP3.9I9ArxTOCRzgmYGMgsNoBYatLOI0mqCw4aCqzNa',2,2),
('Jugador Tres','SmokeMain','jugador3@valorantteam.cl','$2y$12$zfDYUTwf2vbP3.9I9ArxTOCRzgmYGMgsNoBYatLOI0mqCw4aCqzNa',2,4),
('Jugador Cuatro','SentinelMain','jugador4@valorantteam.cl','$2y$12$zfDYUTwf2vbP3.9I9ArxTOCRzgmYGMgsNoBYatLOI0mqCw4aCqzNa',2,5);
INSERT INTO partidas_scrims(fecha,id_mapa,equipo_rival,resultado,rondas_ganadas,rondas_perdidas,observaciones) VALUES
('2026-10-03',1,'Aurora','Victoria',13,9,'Control del mapa consistente'),
('2026-10-05',2,'Zenith','Derrota',10,13,'Revisar retomas'),
('2026-10-07',3,'Nova','Victoria',13,7,'Buenas ejecuciones'),
('2026-10-09',4,'Eclipse','Derrota',11,13,'Mejorar manejo de ventajas');
INSERT INTO estadisticas_jugador(id_partida,id_usuario,id_rol_jugado,id_rol_competitivo,agente_jugado,asesinatos,muertes,asistencias,acs,porcentaje_hs) VALUES
(1,2,2,1,'Jett',13,10,4,180,19.00),
(1,4,2,2,'Sova',16,11,5,201,22.00),
(1,5,2,4,'Omen',19,12,6,222,25.00),
(1,6,2,5,'Killjoy',22,13,7,243,28.00),
(1,3,5,3,'Raze',25,14,8,264,31.00),
(2,2,2,1,'Jett',14,11,5,185,20.00),
(2,4,2,2,'Sova',17,12,6,206,23.00),
(2,5,2,4,'Omen',20,13,7,227,26.00),
(2,6,2,5,'Killjoy',23,14,8,248,29.00),
(2,3,5,3,'Raze',26,15,9,269,32.00),
(3,2,2,1,'Jett',15,12,6,190,21.00),
(3,4,2,2,'Sova',18,13,7,211,24.00),
(3,5,2,4,'Omen',21,14,8,232,27.00),
(3,6,2,5,'Killjoy',24,15,9,253,30.00),
(3,3,5,3,'Raze',27,16,10,274,33.00),
(4,2,2,1,'Jett',16,13,7,195,22.00),
(4,4,2,2,'Sova',19,14,8,216,25.00),
(4,5,2,4,'Omen',22,15,9,237,28.00),
(4,6,2,5,'Killjoy',25,16,10,258,31.00),
(4,3,5,3,'Raze',28,17,11,279,34.00);
INSERT INTO estrategias_tacticas(titulo,id_mapa,bando,agente_clave,descripcion,link_vod,id_creador) VALUES
('Entrada coordinada A',1,'Atacante','Sova','Reconocimiento previo, entrada con utilidad y cobertura de ángulos.',NULL,1),
('Retoma organizada B',2,'Defensor','Omen','Agrupar al equipo, esperar utilidad y retomar en conjunto.',NULL,1);
INSERT INTO entrenamientos(id_jugador,id_coach,titulo_rutina,descripcion_rutina,fecha_limite,estado) VALUES
(2,1,'Disciplina de crosshair','Practicar colocación de mira durante 20 minutos y registrar avances.','2026-12-10','Pendiente'),
(3,1,'Revisión de VOD','Analizar tres errores de posicionamiento en una scrim.','2026-12-12','Pendiente');
