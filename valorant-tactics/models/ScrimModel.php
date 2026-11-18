<?php
final class ScrimModel {
 public static function maps(): array {return db()->query('SELECT * FROM mapas ORDER BY activo_competitivo DESC,nombre_mapa')->fetchAll();}
 public static function activeMapCount(): int {return (int)db()->query('SELECT COUNT(*) FROM mapas WHERE activo_competitivo=1')->fetchColumn();}
 public static function saveMapPool(array $ids): void {
    $pdo=db();$pdo->beginTransaction();
    try {
       $pdo->exec('UPDATE mapas SET activo_competitivo=0');
       $s=$pdo->prepare('UPDATE mapas SET activo_competitivo=1 WHERE id_mapa=?');
       foreach($ids as $id){$s->execute([$id]);}
       $pdo->commit();
    } catch(Throwable $e){$pdo->rollBack();throw $e;}
 }
 public static function mapCatalog(): array {
    return db()->query("SELECT m.*,COUNT(p.id_partida) scrims FROM mapas m
      LEFT JOIN partidas_scrims p ON p.id_mapa=m.id_mapa
      GROUP BY m.id_mapa ORDER BY m.activo_competitivo DESC,m.nombre_mapa")->fetchAll();
 }
 public static function games(): array {return db()->query('SELECT p.*,m.nombre_mapa FROM partidas_scrims p JOIN mapas m ON p.id_mapa=m.id_mapa ORDER BY p.fecha DESC,p.id_partida DESC')->fetchAll();}
 public static function stats(int $id): array {
    $s=db()->prepare("SELECT s.*,u.nickname_valorant,r.nombre_rol,rc.nombre_rol_competitivo
      FROM estadisticas_jugador s JOIN usuarios u ON u.id_usuario=s.id_usuario
      JOIN roles r ON r.id_rol=s.id_rol_jugado
      LEFT JOIN roles_competitivos rc ON rc.id_rol_competitivo=s.id_rol_competitivo
      WHERE s.id_partida=? ORDER BY s.acs DESC");$s->execute([$id]);return $s->fetchAll();
 }
 public static function setStatCompetitive(int $statId,int $gameId,?int $competitive): bool {
    $s=db()->prepare('UPDATE estadisticas_jugador SET id_rol_competitivo=? WHERE id_stat=? AND id_partida=?');
    $s->execute([$competitive,$statId,$gameId]);return $s->rowCount()>0;
 }
 public static function find(int $id): ?array {$s=db()->prepare('SELECT p.*,m.nombre_mapa FROM partidas_scrims p JOIN mapas m ON m.id_mapa=p.id_mapa WHERE p.id_partida=?');$s->execute([$id]);return $s->fetch()?:null;}
 public static function insert(array $game,array $stats): void {
   $pdo=db();$pdo->beginTransaction();
   try {
    $s=$pdo->prepare('INSERT INTO partidas_scrims(fecha,id_mapa,equipo_rival,resultado,rondas_ganadas,rondas_perdidas,observaciones) VALUES(?,?,?,?,?,?,?)');$s->execute($game);$id=(int)$pdo->lastInsertId();
    $s=$pdo->prepare('INSERT INTO estadisticas_jugador(id_partida,id_usuario,id_rol_jugado,id_rol_competitivo,agente_jugado,asesinatos,muertes,asistencias,acs,porcentaje_hs) VALUES(?,?,?,?,?,?,?,?,?,?)');
    foreach($stats as $row)$s->execute(array_merge([$id],$row));
    $pdo->commit();
   } catch(Throwable $ex) {$pdo->rollBack();throw $ex;}
 }
 public static function remove(int $id): void {$s=db()->prepare('DELETE FROM partidas_scrims WHERE id_partida=?');$s->execute([$id]);}
 public static function mine(int $id): array {$s=db()->prepare('SELECT p.fecha,p.equipo_rival,p.resultado,m.nombre_mapa,s.*,rc.nombre_rol_competitivo FROM estadisticas_jugador s JOIN partidas_scrims p ON p.id_partida=s.id_partida JOIN mapas m ON m.id_mapa=p.id_mapa LEFT JOIN roles_competitivos rc ON rc.id_rol_competitivo=s.id_rol_competitivo WHERE s.id_usuario=? ORDER BY p.fecha DESC');$s->execute([$id]);return $s->fetchAll();}
 public static function dashboard(): array {
  $total=db()->query("SELECT COUNT(*) total,COALESCE(SUM(resultado='Victoria'),0) wins FROM partidas_scrims")->fetch();
  $maps=db()->query("SELECT m.nombre_mapa,m.activo_competitivo,COUNT(p.id_partida) total,
    COALESCE(SUM(p.resultado='Victoria'),0) wins FROM mapas m LEFT JOIN partidas_scrims p
    ON m.id_mapa=p.id_mapa GROUP BY m.id_mapa ORDER BY m.activo_competitivo DESC,m.nombre_mapa")->fetchAll();
  return ['total'=>$total,'maps'=>$maps];
 }
 public static function ranking(?int $status=null,?int $competitive=null): array {
  $query='SELECT u.nickname_valorant,r.nombre_rol,COALESCE(rc.nombre_rol_competitivo,\'Sin asignar\') nombre_rol_competitivo,
   COUNT(*) partidas,ROUND(SUM(s.asesinatos+s.asistencias)/GREATEST(SUM(s.muertes),1),2) kda,
   ROUND(AVG(s.acs),1) acs,ROUND(AVG(s.porcentaje_hs),1) hs
   FROM estadisticas_jugador s JOIN usuarios u ON u.id_usuario=s.id_usuario
   JOIN roles r ON r.id_rol=s.id_rol_jugado
   LEFT JOIN roles_competitivos rc ON rc.id_rol_competitivo=s.id_rol_competitivo WHERE 1=1';
  $params=[];
  if($status!==null){$query.=' AND s.id_rol_jugado=?';$params[]=$status;}
  if($competitive!==null){$query.=' AND s.id_rol_competitivo=?';$params[]=$competitive;}
  $query.=' GROUP BY s.id_usuario,s.id_rol_jugado,s.id_rol_competitivo ORDER BY acs DESC';
  $s=db()->prepare($query);$s->execute($params);return $s->fetchAll();
 }
}
