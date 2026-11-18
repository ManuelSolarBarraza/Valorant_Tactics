<?php
declare(strict_types=1);
// Configura las credenciales como variables de entorno en el servidor o edita los valores locales.
final class Database {
    public static function connection(): PDO {
        static $pdo = null;
        if ($pdo instanceof PDO) return $pdo;
        $host = getenv('DB_HOST') ?: 'localhost';
        $name = getenv('DB_NAME') ?: 'valorant_tactics';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $pdo = new PDO('mysql:host='.$host.';dbname='.$name.';charset=utf8mb4', $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        return $pdo;
    }
}
