<?php
final class EntrenamientoController {
 public static function index():void {requireLogin();$items=EntrenamientoModel::all(isCoach()?null:(int)user()['id_usuario']);$players=isCoach()?UsuarioModel::players():[];render('entrenamientos/index',compact('items','players'));}
 public static function store():never {requireCoach();verifyCsrf();$id=positiveInt($_POST['id_jugador']??0);$title=clean('titulo_rutina',150);$desc=clean('descripcion_rutina',10000);$date=clean('fecha_limite',10);$players=array_map('intval',array_column(UsuarioModel::players(),'id_usuario'));if(!in_array($id,$players,true)||!$title||!$desc||!validDate($date))throw new RuntimeException('Datos de entrenamiento inválidos.');EntrenamientoModel::add($id,(int)user()['id_usuario'],$title,$desc,$date);flash('Entrenamiento asignado.');redirect('entrenamientos');}
 public static function complete():never {requireLogin();verifyCsrf();if(isCoach())throw new RuntimeException('Solo el jugador puede completar su propia tarea.');EntrenamientoModel::complete((int)($_POST['id']??0),(int)user()['id_usuario']);flash('Entrenamiento actualizado.');redirect('entrenamientos');}
 public static function delete():never {requireCoach();verifyCsrf();EntrenamientoModel::remove((int)($_POST['id']??0));flash('Entrenamiento eliminado.');redirect('entrenamientos');}
}
