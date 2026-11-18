<?php
final class EstrategiaModel {
 public static function all(?int $map=null):array{$sql='SELECT t.*,m.nombre_mapa,u.nickname_valorant FROM estrategias_tacticas t JOIN mapas m ON m.id_mapa=t.id_mapa JOIN usuarios u ON u.id_usuario=t.id_creador'; if($map) $sql.=' WHERE t.id_mapa=?';$sql.=' ORDER BY t.fecha_creacion DESC,t.id_estrategia DESC';$s=db()->prepare($sql);$s->execute($map?[$map]:[]);return $s->fetchAll();}
 public static function find(int $id):?array{$s=db()->prepare('SELECT * FROM estrategias_tacticas WHERE id_estrategia=?');$s->execute([$id]);return $s->fetch()?:null;}
 public static function save(?int $id,array $a):void {if($id){$s=db()->prepare('UPDATE estrategias_tacticas SET titulo=?,id_mapa=?,bando=?,agente_clave=?,descripcion=?,link_vod=? WHERE id_estrategia=?');$s->execute([...$a,$id]);} else {$s=db()->prepare('INSERT INTO estrategias_tacticas(titulo,id_mapa,bando,agente_clave,descripcion,link_vod,id_creador) VALUES(?,?,?,?,?,?,?)');$s->execute($a);} }
 public static function remove(int $id):void{$s=db()->prepare('DELETE FROM estrategias_tacticas WHERE id_estrategia=?');$s->execute([$id]);}
}
