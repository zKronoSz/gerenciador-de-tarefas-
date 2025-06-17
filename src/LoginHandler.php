<?php
namespace Src;

class LoginHandler {
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function validateCredentials(string $email, string $password): bool {
        $stmt = $this->pdo->prepare('SELECT id, senha FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['senha'])) {
            return true;
        }
        return false;
    }

    public function getUserByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare('SELECT id, nome, email FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }
}