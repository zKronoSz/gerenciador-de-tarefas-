<?php
session_start();

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/LoginHandler.php';

use Src\Database;
use Src\LoginHandler;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $error = urlencode("Por favor, preencha todos os campos");
        header("Location: login.html?error=$error");
        exit;
    }

    $database = new Database();
    $pdo = $database->getConnection();

    $loginHandler = new LoginHandler($pdo);

    if ($loginHandler->validateCredentials($email, $password)) {
        $user = $loginHandler->getUserByEmail($email);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];   // <-- Salva o nome na sessão
        $_SESSION['user_email'] = $user['email'];

        header("Location: ../public/workspace.php"); 
        exit;
    } else {
        $error = urlencode("Credenciais inválidas");
        header("Location: login.php?error=$error");
        exit;
    }
} else {
    header("Location: login.html");
    exit;
}