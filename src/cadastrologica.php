<?php
require_once __DIR__ . '/../src/database.php';
use Src\Database;

session_start();

function redirect_with_message(string $message, string $type = 'danger'): void {
    header("Location: /gerenciadorTarefas/public/cadastro.php?message=" . urlencode($message) . "&type=$type");
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$name || !$email || !$password) {
    redirect_with_message("Por favor, preencha todos os campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_with_message("E-mail inválido.");
}

if (strlen($password) < 8) {
    redirect_with_message("A senha deve ter pelo menos 8 caracteres.");
}

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        redirect_with_message("Este e-mail já está cadastrado.");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword]);

    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;

    // Redireciona para login.php corretamente dentro do projeto
    header("Location: /gerenciadorTarefas/public/login.php");
    exit;

} catch (PDOException $e) {
    redirect_with_message("Erro interno. Tente novamente mais tarde.");
}