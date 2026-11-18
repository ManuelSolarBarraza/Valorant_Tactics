<?php
final class EntrenamientoModel {
 public static function all(?int $player=null):array{$sql='SELECT t.*,j.nickname_valorant jugador,c.nickname_valorant coach FROM entrenamientos t JOIN usuarios j ON j.id_usuario=t.id_jugador JOIN usuarios c ON c.id_usuario=t.id_coach';if($player) $sql.=' WHERE t.id_jugador=?';$sql.=' ORDER BY (t.estado="Pendiente") DESC,t.fecha_limite ASC';$s=db()->prepare($sql);$s->execute($player?[$player]:[]);return $s->fetchAll();}
 public static function add(int $player,int $coach,string $title,string $desc,string $date):void{$s=db()->prepare('INSERT INTO entrenamientos(id_jugador,id_coach,titulo_rutina,descripcion_rutina,fecha_limite) VALUES(?,?,?,?,?)');$s->execute([$player,$coach,$title,$desc,$date]);}
 public static function complete(int $id,int $player):void{$s=db()->prepare("UPDATE entrenamientos SET estado='Completado' WHERE id_entrenamiento=? AND id_jugador=?");$s->execute([$id,$player]);}
 public static function remove(int $id):void{$s=db()->prepare('DELETE FROM entrenamientos WHERE id_entrenamiento=?');$s->execute([$id]);}
}
