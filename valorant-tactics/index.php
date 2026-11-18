<?php
declare(strict_types=1);
ini_set('display_errors','0');
ini_set('log_errors','1');
if (session_status()===PHP_SESSION_NONE) {session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off','samesite'=>'Lax']);session_start();}
require_once __DIR__.'/config/database.php';require_once __DIR__.'/config/helpers.php';
spl_autoload_register(function(string $class):void {foreach(['models','controllers'] as $dir){$p=__DIR__.'/'.$dir.'/'.$class.'.php';if(is_file($p)){require_once $p;return;}}});
try {
 $page=(string)($_GET['page']??'dashboard');$method=$_SERVER['REQUEST_METHOD']??'GET';
 $routes=[
  'login'=>['GET'=>[AuthController::class,'login'],'POST'=>[AuthController::class,'login']],
  'registro'=>['GET'=>[AuthController::class,'register'],'POST'=>[AuthController::class,'register']],
  'logout'=>['POST'=>[AuthController::class,'logout']],
  'dashboard'=>['GET'=>[DashboardController::class,'index']],
  'scrims'=>['GET'=>[ScrimController::class,'index']],
  'scrim-ver'=>['GET'=>[ScrimController::class,'view']],
  'scrim-crear'=>['GET'=>[ScrimController::class,'create']],
  'scrim-guardar'=>['POST'=>[ScrimController::class,'store']],
  'scrim-eliminar'=>['POST'=>[ScrimController::class,'delete']],
  'scrim-rol-guardar'=>['POST'=>[ScrimController::class,'updateCompetitive']],
  'mapas'=>['GET'=>[MapaController::class,'index']],
  'mapas-guardar'=>['POST'=>[MapaController::class,'save']],
  'roster'=>['GET'=>[RosterController::class,'index']],
  'roster-guardar'=>['POST'=>[RosterController::class,'save']],
  'tacticas'=>['GET'=>[TacticaController::class,'index']],
  'tactica-editar'=>['GET'=>[TacticaController::class,'edit']],
  'tactica-guardar'=>['POST'=>[TacticaController::class,'save']],
  'tactica-eliminar'=>['POST'=>[TacticaController::class,'delete']],
  'entrenamientos'=>['GET'=>[EntrenamientoController::class,'index']],
  'entrenamiento-guardar'=>['POST'=>[EntrenamientoController::class,'store']],
  'entrenamiento-completar'=>['POST'=>[EntrenamientoController::class,'complete']],
  'entrenamiento-eliminar'=>['POST'=>[EntrenamientoController::class,'delete']],
  'perfil'=>['GET'=>[PerfilController::class,'edit']],
  'perfil-guardar'=>['POST'=>[PerfilController::class,'save']]
 ];
 if(!isset($routes[$page][$method])){http_response_code(404);throw new RuntimeException('Página no encontrada.');}
 call_user_func($routes[$page][$method]);
} catch (PDOException $e) {error_log((string)$e);http_response_code(500);$error='Error de base de datos. Revisa la configuración de conexión y la importación del SQL.';include __DIR__.'/views/layouts/error.php';}
catch (Throwable $e) {http_response_code(http_response_code()>=400?http_response_code():400);$error=$e->getMessage();include __DIR__.'/views/layouts/error.php';}
