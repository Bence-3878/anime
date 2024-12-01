<?php
declare(strict_types=1);

class Database {
    private static ?PDO $connection = null;

    private function __construct() {}
    private function __clone() {}

    public static function connect(): PDO {
        if (self::$connection === null) {
            try {
                $host = 'localhost';
                $dbname = 'hazi';
                $username = 'root';
                $password = '';

                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_FOUND_ROWS => true
                ];

                self::$connection = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                error_log('Database Connection Error: ' . $e->getMessage());
                throw new Exception('Database connection failed');
            }
        }
        return self::$connection;
    }
}
?>
