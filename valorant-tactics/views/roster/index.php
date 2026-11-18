<div class="page-heading"><div><div class="eyebrow">EQUIPO / ADMINISTRACIÓN</div><h1>Gestión de <em>plantilla</em></h1><p>El estado en el equipo es independiente del rol competitivo. Los cambios no reescriben las scrims.</p></div></div>
<section class="panel"><div class="panel-head"><h2>Jugadores registrados</h2><span class="meta-tag"><?=count($players)?> JUGADORES</span></div>
<?php if(!$players):?><p>No hay jugadores registrados.</p><?php endif;?>
<div class="roster-grid">
<?php foreach($players as $p):?><form method="post" action="<?=e(url('roster-guardar'))?>" class="roster-card">
<?=csrfField()?><input type="hidden" name="id_usuario" value="<?=e($p['id_usuario'])?>">
<div><strong><?=e($p['nickname_valorant'])?></strong><small><?=e($p['nombre_completo'])?> · <?=e($p['email'])?></small></div>
<label>Estado en plantilla<select name="id_rol" required><?php foreach([2=>'Titular',3=>'Suplente',4=>'Parche',5=>'Tryout'] as $id=>$label):?><option value="<?=$id?>" <?=(int)$p['id_rol']===$id?'selected':''?>><?=e($label)?></option><?php endforeach;?></select></label>
<label>Rol competitivo principal<select name="id_rol_competitivo"><option value="0">Sin asignar</option><?php foreach($roles as $r):?><option value="<?=e($r['id_rol_competitivo'])?>" <?=(int)$p['id_rol_competitivo']===(int)$r['id_rol_competitivo']?'selected':''?>><?=e($r['nombre_rol_competitivo'])?></option><?php endforeach;?></select></label>
<button class="btn btn-primary small">Guardar jugador</button></form><?php endforeach;?></div></section>
<section class="panel"><h2>Cómo funciona</h2><p>El rol principal describe la función habitual del jugador. Al registrar una scrim puedes indicar otro rol competitivo y otro estado para reflejar lo que ocurrió realmente en esa partida.</p><p class="muted">Los nuevos usuarios registrados en la web empiezan como Tryout, sin rol competitivo asignado. El Coach puede clasificarlos desde aquí.</p></section>
