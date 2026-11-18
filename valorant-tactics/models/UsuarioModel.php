<?php
final class UsuarioModel {
    public static function byEmail(string $email): ?array {
        $s=db()->prepare('SELECT * FROM usuarios WHERE email=?'); $s->execute([$email]); return $s->fetch()?:null;
    }
    public static function byId(int $id): ?array {
        $s=db()->prepare('SELECT * FROM usuarios WHERE id_usuario=?'); $s->execute([$id]); return $s->fetch()?:null;
    }
    public static function rolesCompetitivos(): array {
        return db()->query('SELECT * FROM roles_competitivos ORDER BY id_rol_competitivo')->fetchAll();
    }
    public static function players(): array {
        return db()->query("SELECT u.id_usuario,u.nickname_valorant,u.nombre_completo,u.email,
            u.id_rol,u.id_rol_competitivo,r.nombre_rol,rc.nombre_rol_competitivo
            FROM usuarios u JOIN roles r ON r.id_rol=u.id_rol
            LEFT JOIN roles_competitivos rc ON rc.id_rol_competitivo=u.id_rol_competitivo
            WHERE u.id_rol IN (2,3,4,5) ORDER BY u.nickname_valorant")->fetchAll();
    }
    public static function register(string $name,string $nick,string $email,string $pass): void {
        $s=db()->prepare('INSERT INTO usuarios(nombre_completo,nickname_valorant,email,password_hash,id_rol) VALUES(?,?,?,?,5)');
        $s->execute([$name,$nick,$email,password_hash($pass,PASSWORD_BCRYPT)]);
    }
    public static function update(int $id,string $name,string $nick,string $email,?string $pass): void {
        if($pass!==null){$s=db()->prepare('UPDATE usuarios SET nombre_completo=?,nickname_valorant=?,email=?,password_hash=? WHERE id_usuario=?');$s->execute([$name,$nick,$email,password_hash($pass,PASSWORD_BCRYPT),$id]);}
        else {$s=db()->prepare('UPDATE usuarios SET nombre_completo=?,nickname_valorant=?,email=? WHERE id_usuario=?');$s->execute([$name,$nick,$email,$id]);}
    }
    public static function setRoster(int $userId,int $status,?int $competitive): bool {
        $s=db()->prepare('UPDATE usuarios SET id_rol=?,id_rol_competitivo=? WHERE id_usuario=? AND id_rol IN (2,3,4,5)');
        $s->execute([$status,$competitive,$userId]);
        return $s->rowCount()>0;
    }
}
