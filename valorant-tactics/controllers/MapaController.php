<?php
final class MapaController {
 public static function index():void {requireLogin();$maps=ScrimModel::mapCatalog();render('mapas/index',compact('maps'));}
 public static function save():never {
   requireCoach();verifyCsrf();$ids=$_POST['mapas']??[];
   if(!is_array($ids)||count($ids)!==7)throw new RuntimeException('Selecciona exactamente 7 mapas para la rotación activa.');
   $ids=array_map('intval',$ids);$available=array_map('intval',array_column(ScrimModel::maps(),'id_mapa'));
   if(count(array_unique($ids))!==7||count(array_diff($ids,$available))!==0)throw new RuntimeException('La selección de mapas no es válida.');
   ScrimModel::saveMapPool($ids);flash('Rotación competitiva actualizada (7 mapas).');redirect('mapas');
 }
}
