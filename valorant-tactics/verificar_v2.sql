-- Consultas de SOLO LECTURA para verificar la migración v2 en phpMyAdmin.
USE valorant_tactics;
SELECT COUNT(*) AS mapas_en_catalogo,
       SUM(activo_competitivo = 1) AS mapas_activos
FROM mapas;
SELECT nombre_mapa FROM mapas WHERE activo_competitivo = 1 ORDER BY nombre_mapa;
SELECT COUNT(*) AS scrims_conservadas FROM partidas_scrims;
SELECT COUNT(*) AS estadisticas_conservadas FROM estadisticas_jugador;
SELECT COUNT(*) AS roles_competitivos FROM roles_competitivos;
SELECT u.nickname_valorant, r.nombre_rol AS estado_plantilla,
       COALESCE(rc.nombre_rol_competitivo,'Sin asignar') AS rol_competitivo
FROM usuarios u JOIN roles r ON r.id_rol=u.id_rol
LEFT JOIN roles_competitivos rc ON rc.id_rol_competitivo=u.id_rol_competitivo
ORDER BY u.id_usuario;
