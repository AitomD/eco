<?php

class Database {
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $db   = "ecommerce";

    public static function conectar() {
        try {
            return new PDO(
                "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=utf8",
                self::$user,
                self::$pass
            );
        } catch (PDOException $e) {
            die("Erro ao conectar ao banco de dados e-commerce. Erro: " . $e->getMessage());
        }
    }
}

?>
