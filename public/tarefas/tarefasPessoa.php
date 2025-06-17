<?php //esse cria as tarefas, e le o enviado via AJAX (fetch) que esta no workspace logo abaixo do bady um script


session_start();
header('Content-Type: application/json'); // <-- ESSENCIAL para o fetch funcionar corretamente
require_once __DIR__ . '/../../src/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
        exit;
    }

    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data_entrega = $_POST['data_entrega'] ?? '';  
    $prioridade = $_POST['prioridade'] ?? '';
    $usuario_id = $_SESSION['user_id'];

    if (empty($titulo) || empty($data_entrega) || empty($prioridade)) {
        echo json_encode(['success' => false, 'message' => 'Campos obrigatórios faltando.']);
        exit;
    }

    $status = 'aberto';

    try {
        $db = new \Src\Database();
        $pdo = $db->getConnection();

        $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, data_entrega, prioridade, status, usuario_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $descricao, $data_entrega, $prioridade, $status, $usuario_id]);

        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        error_log("Erro ao salvar tarefa: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar tarefa.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit;
}