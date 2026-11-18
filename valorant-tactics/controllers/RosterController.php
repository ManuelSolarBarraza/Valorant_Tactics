<?php
final class RosterController {
 public static function index():void {requireCoach();$players=UsuarioModel::players();$roles=UsuarioModel::rolesCompetitivos();render('roster/index',compact('players','roles'));}
 public static function save():never {
   requireCoach();verifyCsrf();$id=positiveInt($_POST['id_usuario']??null);
   $status=positiveInt($_POST['id_rol']??null);$competitive=positiveInt($_POST['id_rol_competitivo']??null);
   $validRoles=array_map('intval',array_column(UsuarioModel::rolesCompetitivos(),'id_rol_competitivo'));
   if(!$id||!in_array($status,[2,3,4,5],true)||($competitive!==0&&!in_array($competitive,$validRoles,true)))throw new RuntimeException('Estado o rol competitivo inválido.');
   $player=UsuarioModel::byId($id);
   if(!$player||(int)$player['id_rol']===1)throw new RuntimeException('Solo puedes modificar perfiles de jugador.');
   UsuarioModel::setRoster($id,$status,$competitive?:null);
   flash('Plantilla actualizada. El historial de scrims no ha sido modificado.');redirect('roster');
 }
}
