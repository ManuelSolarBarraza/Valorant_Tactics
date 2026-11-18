<?php
final class PerfilController {
 public static function edit():void {requireLogin();$profile=UsuarioModel::byId((int)user()['id_usuario']);render('perfil/editar',compact('profile'));}
 public static function save():never {requireLogin();verifyCsrf();$id=(int)user()['id_usuario'];$n=clean('nombre_completo',100);$nick=clean('nickname_valorant',50);$email=clean('email',100);$pass=(string)($_POST['password']??'');if(!$n||!$nick||!filter_var($email,FILTER_VALIDATE_EMAIL)||($pass!==''&&strlen($pass)<8))throw new RuntimeException('Revisa tus datos; la nueva contraseña requiere 8 caracteres.');try{UsuarioModel::update($id,$n,$nick,$email,$pass?:null);}catch(PDOException $e){if($e->getCode()==='23000')throw new RuntimeException('Este correo ya está en uso.');throw $e;}$u=UsuarioModel::byId($id);unset($u['password_hash']);$_SESSION['user']=$u;flash('Perfil actualizado.');redirect('perfil');}
}
