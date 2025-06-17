<?php // obs: esse codigo serve para apagar todas as tarefas concluidas que estao na caixa de entrar, no botao tarefas concluidas 
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Não autorizado');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../src/Database.php';  

    $db = new Src\Database();
    $pdo = $db->getConnection();

    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE usuario_id = :usuario_id AND status = 'concluido'");
    $stmt->execute(['usuario_id' => $userId]);

    header('Location: ../workspace.php?page=dashboard');
    exit;
} else {
    http_response_code(405);
    echo "Método não permitido";
}