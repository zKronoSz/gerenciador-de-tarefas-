<?php 

session_start();
require_once __DIR__ . '/../../src/Database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $db = new \Src\Database();
    $pdo = $db->getConnection();

    // Contar tarefas concluídas
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE usuario_id = ? AND status = 'concluido'");
    $stmt->execute([$userId]);
    $concluidas = (int) $stmt->fetchColumn();

    // Contar tarefas pendentes (status diferente de 'concluido')
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE usuario_id = ? AND status != 'concluido'");
    $stmt->execute([$userId]);
    $pendentes = (int) $stmt->fetchColumn();

    // Para projetos ativos, você pode definir lógica, ou retornar 0 por enquanto
    $projetos = 0;

    echo json_encode([
        'concluidas' => $concluidas,
        'pendentes' => $pendentes,
        'projetos' => $projetos,
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}