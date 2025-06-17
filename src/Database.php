<?php // a lincagem com o banco

namespace Src;

class Database {
    private $pdo;

    public function __construct() {
        $this->loadEnv();
        $host = getenv('DB_HOST');
        $db = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            $this->pdo = new \PDO($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        } catch (\PDOException $e) {
            die("Erro na conexão com o banco: " . $e->getMessage());
        }
    }

    private function loadEnv() {
        $envPath = __DIR__ . '/../.env';
        if (!file_exists($envPath)) {
            die("Arquivo .env não encontrado");
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}