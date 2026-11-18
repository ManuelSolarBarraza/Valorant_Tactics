<?php
declare(strict_types=1);
function db(): PDO { return Database::connection(); }
function e(mixed $value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $page='dashboard', array $params=[]): string { return 'index.php?'.http_build_query(array_merge(['page'=>$page],$params)); }
function redirect(string $page='dashboard', array $params=[]): never { header('Location: '.url($page,$params)); exit; }
function user(): ?array { return $_SESSION['user'] ?? null; }
function isCoach(): bool { return (int)(user()['id_rol'] ?? 0) === 1; }
function requireLogin(): void { if (!user()) redirect('login'); }
function requireCoach(): void { requireLogin(); if (!isCoach()) { http_response_code(403); throw new RuntimeException('No tienes permiso para realizar esta acción.'); } }
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrfField(): string { return '<input type="hidden" name="csrf" value="'.e(csrf()).'">'; }
function verifyCsrf(): void { if (!hash_equals(csrf(),(string)($_POST['csrf'] ?? ''))) { http_response_code(419); throw new RuntimeException('Sesión del formulario vencida. Vuelve a intentarlo.'); } }
function flash(string $message, string $type='success'): void { $_SESSION['flash']=['message'=>$message,'type'=>$type]; }
function render(string $view, array $data=[]): void { extract($data,EXTR_SKIP); include __DIR__.'/../views/layouts/header.php'; include __DIR__.'/../views/'.$view.'.php'; include __DIR__.'/../views/layouts/footer.php'; }
function clean(string $field,int $max=255): string { return mb_substr(trim((string)($_POST[$field]??'')),0,$max); }
function positiveInt(mixed $value): int { $result=filter_var($value,FILTER_VALIDATE_INT); return ($result !== false && $result>=0) ? $result : -1; }
function validDate(string $value): bool { $d=DateTimeImmutable::createFromFormat('!Y-m-d',$value); return $d && $d->format('Y-m-d')===$value; }
function validVod(string $v): bool { if ($v==='') return true; $parts=parse_url($v); if (!$parts || ($parts['scheme']??'')!=='https') return false; $h=strtolower($parts['host']??''); return in_array($h,['youtube.com','www.youtube.com','youtu.be','twitch.tv','www.twitch.tv'],true); }
