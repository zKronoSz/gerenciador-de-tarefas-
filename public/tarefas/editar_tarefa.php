<?php //edita as tarefas 

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

    // Buscar dados atuais da tarefa para preencher o formulário
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute(['id' => $tarefaId, 'usuario_id' => $userId]);
    $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tarefa) {
        die("Tarefa não encontrada ou você não tem permissão para editar.");
    }

    // Se o formulário foi enviado para atualizar a tarefa
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titulo = $_POST['titulo'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $data_entrega = $_POST['data_entrega'] ?? '';
        $prioridade = $_POST['prioridade'] ?? '';
        $status = $_POST['status'] ?? 'aberto';

        // Validação básica melhorada
        if (!$titulo || !$descricao || !$data_entrega || !$prioridade || !$status) {
            $error = "Por favor, preencha todos os campos obrigatórios.";
            // Mantém os valores no formulário
            $tarefa['titulo'] = $titulo;
            $tarefa['descricao'] = $descricao;
            $tarefa['data_entrega'] = $data_entrega;
            $tarefa['prioridade'] = $prioridade;
            $tarefa['status'] = $status;
        } elseif (!DateTime::createFromFormat('Y-m-d', $data_entrega)) {
            $error = "Data de entrega inválida.";
            $tarefa['titulo'] = $titulo;
            $tarefa['descricao'] = $descricao;
            $tarefa['data_entrega'] = $data_entrega;
            $tarefa['prioridade'] = $prioridade;
            $tarefa['status'] = $status;
        } else {
            $stmt = $pdo->prepare("UPDATE tarefas SET titulo = :titulo, descricao = :descricao, data_entrega = :data_entrega, prioridade = :prioridade, status = :status WHERE id = :id AND usuario_id = :usuario_id");
            $stmt->execute([
                'titulo' => $titulo,
                'descricao' => $descricao,
                'data_entrega' => $data_entrega,
                'prioridade' => $prioridade,
                'status' => $status,
                'id' => $tarefaId,
                'usuario_id' => $userId,
            ]);
            header("Location: ../workspace.php?page=minhas_tarefas");
            exit;
        }
    }

} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Editar Tarefa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container py-4">
    <h2>Editar Tarefa</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required value="<?= htmlspecialchars($tarefa['titulo']) ?>" />
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="4" required><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="data_entrega" class="form-label">Data de Entrega</label>
            <input type="date" name="data_entrega" id="data_entrega" class="form-control" required value="<?= htmlspecialchars($tarefa['data_entrega']) ?>" />
        </div>
        <div class="mb-3">
            <label for="prioridade" class="form-label">Prioridade</label>
            <select name="prioridade" id="prioridade" class="form-select" required>
                <option value="baixa" <?= $tarefa['prioridade'] === 'baixa' ? 'selected' : '' ?>>Baixa</option>
                <option value="media" <?= $tarefa['prioridade'] === 'media' ? 'selected' : '' ?>>Média</option>
                <option value="alta" <?= $tarefa['prioridade'] === 'alta' ? 'selected' : '' ?>>Alta</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <option value="aberto" <?= $tarefa['status'] === 'aberto' ? 'selected' : '' ?>>Aberto</option>
                <option value="em_andamento" <?= $tarefa['status'] === 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                <option value="testando" <?= $tarefa['status'] === 'testando' ? 'selected' : '' ?>>Testando</option>
                <option value="revisar" <?= $tarefa['status'] === 'revisar' ? 'selected' : '' ?>>Revisar</option>
                <option value="concluido" <?= $tarefa['status'] === 'concluido' ? 'selected' : '' ?>>Concluído</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="../index.php?page=minhas_tarefas" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>