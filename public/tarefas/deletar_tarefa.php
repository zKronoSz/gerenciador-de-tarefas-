<?php //apaga as tarefas 

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit;
}

require_once __DIR__ . '/../../src/Database.php';

$userId = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$tarefaId = (int)$_GET['id'];

try {
    $db = new Src\Database();
    $pdo = $db->getConnection();

    // Só apaga se a tarefa pertencer ao usuário logado (segurança)
    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute(['id' => $tarefaId, 'usuario_id' => $userId]);

    header("Location: ../workspace.php?page=minhas_tarefas");  // pra voltar para minhas tarefas 
    exit;
} catch (Exception $e) {
    die("Erro ao apagar tarefa: " . $e->getMessage());
}
?>