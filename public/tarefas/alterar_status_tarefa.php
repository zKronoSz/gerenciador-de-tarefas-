<?php // e pra check box, pra alterar para cocluido  

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['success' => false, 'message' => 'Não autorizado']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once __DIR__ . '/../../src/Database.php';
  $db = new Src\Database();
  $pdo = $db->getConnection();

  // Lê o JSON do corpo da requisição
  $inputJSON = file_get_contents('php://input');
  $input = json_decode($inputJSON, true);

  $id = isset($input['id']) ? filter_var($input['id'], FILTER_VALIDATE_INT) : null;
  $status = isset($input['status']) ? filter_var($input['status'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;
  $userId = $_SESSION['user_id'];

  $validStatus = ['pendente', 'concluido'];

  if ($id && in_array($status, $validStatus, true)) {
    $stmt = $pdo->prepare("UPDATE tarefas SET status = :status WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute(['status' => $status, 'id' => $id, 'usuario_id' => $userId]);

    echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso']);
  } else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos']);
  }
} else {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Método não permitido']);
}