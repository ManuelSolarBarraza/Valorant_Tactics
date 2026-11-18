-- VALORANT TACTICS v2 — Actualización NO destructiva desde la instalación original.
-- IMPORTANTE: Exporta la base de datos desde phpMyAdmin ANTES de ejecutarlo.
-- Ejecutar UNA SOLA VEZ, con la base valorant_tactics seleccionada.
-- Si ya intentaste migrar y hubo un error, no la repitas a ciegas: revisa qué instrucciones se ejecutaron.
-- No contiene DROP TABLE ni DELETE; conserva partidas, estadísticas, usuarios y tácticas.
USE valorant_tactics;

CREATE TABLE IF NOT EXISTS roles_competitivos (
  id_rol_competitivo INT AUTO_INCREMENT PRIMARY KEY,
  nombre_rol_competitivo VARCHAR(40) NOT NULL UNIQUE
) ENGINE=InnoDB;
INSERT INTO roles_competitivos (id_rol_competitivo,nombre_rol_competitivo) VALUES
 (1,'Duelista'),(2,'Iniciador'),(3,'Flex'),(4,'Smoker'),(5,'Centinela')
ON DUPLICATE KEY UPDATE nombre_rol_competitivo=VALUES(nombre_rol_competitivo);

ALTER TABLE usuarios ADD COLUMN id_rol_competitivo INT NULL;
ALTER TABLE usuarios ADD CONSTRAINT fk_usuarios_tacticos FOREIGN KEY (id_rol_competitivo) REFERENCES roles_competitivos(id_rol_competitivo);
ALTER TABLE estadisticas_jugador ADD COLUMN id_rol_competitivo INT NULL;
ALTER TABLE estadisticas_jugador ADD CONSTRAINT fk_stats_tacticos FOREIGN KEY (id_rol_competitivo) REFERENCES roles_competitivos(id_rol_competitivo);
ALTER TABLE mapas ADD COLUMN activo_competitivo TINYINT(1) NOT NULL DEFAULT 0;

INSERT INTO mapas(nombre_mapa,activo_competitivo) VALUES
 ('Ascent',0),('Bind',1),('Haven',1),('Split',0),('Lotus',1),('Sunset',1),('Icebox',0),
 ('Abyss',1),('Breeze',0),('Fracture',0),('Pearl',0),('Corrode',1),('Summit',1)
ON DUPLICATE KEY UPDATE nombre_mapa=VALUES(nombre_mapa);
UPDATE mapas SET activo_competitivo=CASE WHEN nombre_mapa IN
 ('Abyss','Bind','Corrode','Haven','Lotus','Summit','Sunset') THEN 1 ELSE 0 END;

-- Los cinco jugadores demo tienen roles asignados; los jugadores reales no se clasifican por su agente automáticamente.
UPDATE usuarios SET id_rol_competitivo=CASE email
 WHEN 'jugador@valorantteam.cl' THEN 1
 WHEN 'jugador2@valorantteam.cl' THEN 2
 WHEN 'jugador3@valorantteam.cl' THEN 4
 WHEN 'jugador4@valorantteam.cl' THEN 5
 WHEN 'tryout@valorantteam.cl' THEN 3
 ELSE id_rol_competitivo END
WHERE id_rol_competitivo IS NULL AND email IN
 ('jugador@valorantteam.cl','jugador2@valorantteam.cl','jugador3@valorantteam.cl','jugador4@valorantteam.cl','tryout@valorantteam.cl');

-- Completa únicamente las estadísticas de las cuentas demo, sin reinterpretar datos reales.
UPDATE estadisticas_jugador s JOIN usuarios u ON u.id_usuario=s.id_usuario
SET s.id_rol_competitivo=u.id_rol_competitivo
WHERE s.id_rol_competitivo IS NULL AND u.email IN
 ('jugador@valorantteam.cl','jugador2@valorantteam.cl','jugador3@valorantteam.cl','jugador4@valorantteam.cl','tryout@valorantteam.cl');

-- Resultado esperado: 13 mapas, exactamente 7 activos, tabla roles_competitivos,
-- campos id_rol_competitivo en usuarios y estadisticas_jugador.
