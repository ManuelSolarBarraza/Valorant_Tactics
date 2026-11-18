<?php
final class DashboardController {
 public static function index():void {
   requireLogin();
   $role=isset($_GET['role'])&&$_GET['role']!==''?(int)$_GET['role']:null;
   if($role!==null&&!in_array($role,[2,3,4,5],true))$role=null;
   $competitive=isset($_GET['competitive'])&&$_GET['competitive']!==''?(int)$_GET['competitive']:null;
   $roles=UsuarioModel::rolesCompetitivos();
   if($competitive!==null&&!in_array($competitive,array_map('intval',array_column($roles,'id_rol_competitivo')),true))$competitive=null;
   $showHistory=($_GET['history']??'')==='1';
   $summary=ScrimModel::dashboard();$ranking=isCoach()?ScrimModel::ranking($role,$competitive):[];
   $mine=isCoach()?[]:ScrimModel::mine((int)user()['id_usuario']);
   render('dashboard/index',compact('summary','ranking','role','competitive','roles','showHistory','mine'));
 }
}
