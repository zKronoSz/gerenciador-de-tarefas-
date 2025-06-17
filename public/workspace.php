<?php
session_start();

if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");  // login.php está na mesma pasta public/
    exit;
}

$userName = $_SESSION['user_name'];
$userId = $_SESSION['user_id'];

require_once __DIR__ . '/../src/Database.php';  // Ajuste só se src estiver fora de public

use Src\Database;

try {
    $db = new Database(); 
    $pdo = $db->getConnection();
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Define página ativa (se você usa isso para renderizar conteúdo dinâmico)
$page = $_GET['page'] ?? 'workspace';

if (isset($_GET['success'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
}

if (isset($_GET['error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>





<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Workspace - <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <!-- Ícones Lucide -->
  <script src="https://unpkg.com/lucide@latest"></script>
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    .sidebar { width: 250px; min-height: 100vh; background-color: #f8f9fa; }
    .workspace-content { flex: 1; background-color: #fff; padding: 1.5rem; }
    .star-icon { color: #ffc107; }
    .pointer { cursor: pointer; }
  </style>
</head>

<body>
 <script>
  document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.status-checkbox');

    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        const tarefaId = this.dataset.id;
        const novoStatus = this.checked ? 'concluido' : 'pendente';

        fetch('tarefas/alterar_status_tarefa.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: tarefaId, status: novoStatus })
        })
        .then(async response => {
          const contentType = response.headers.get("Content-Type");
          if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Erro ${response.status}: ${errorText}`);
          }

          if (contentType && contentType.includes("application/json")) {
            return response.json();
          } else {
            throw new Error("Resposta não é JSON");
          }
        })
        .then(data => {
          if (!data.success) {
            alert('Erro ao atualizar status: ' + data.message);
            this.checked = !this.checked;
          } else {
            location.reload();
          }
        })
        .catch(error => {
          alert('Erro ao atualizar status: ' + error.message);
          this.checked = !this.checked;
        });
      });
    });
  });
</script>

  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar border-end p-3">
      <h5><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?> - Workspace</h5>
      <small class="text-muted">Público • Espaço de trabalho</small>
      <hr />
      <div class="list-group">
        <a href="?page=dashboard" class="list-group-item list-group-item-action <?= $page === 'dashboard' ? 'active' : '' ?>">📥 Caixa de entrada</a>
        <a href="?page=minhas_tarefas" class="list-group-item list-group-item-action <?= $page === 'minhas_tarefas' ? 'active' : '' ?>">📋 Minhas tarefas</a>
      </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="workspace-content d-flex flex-column">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
          <i data-lucide="star" class="star-icon"></i> <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
        </h3>
        <div class="d-flex align-items-center gap-3">
          <form class="d-flex me-3" role="search" method="GET" action="">
            <input class="form-control me-2" type="search" name="search" placeholder="Pesquisar..." aria-label="Search" />
            <button class="btn btn-outline-primary" type="submit">Buscar</button>
          </form>
          <a href="profile.php" class="btn btn-outline-dark d-flex align-items-center">
            <i data-lucide="user" class="me-1"></i> <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
          </a>
        </div>
      </div>

      <?php if ($page === 'dashboard'): ?>
        <div class="dashboard-content">

          <h4>
            Tarefas Concluídas
            <form method="POST" action="tarefas/apagar_concluidas.php" style="display:inline-block; margin-left: 15px;">
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que quer apagar todas as tarefas concluídas?');">
                Limpar tudo
              </button>
            </form>
          </h4>
          <?php
            $stmtConcluidas = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = :usuario_id AND status = 'concluido' ORDER BY data_entrega DESC");
            $stmtConcluidas->execute(['usuario_id' => $userId]);
            $tarefasConcluidas = $stmtConcluidas->fetchAll(PDO::FETCH_ASSOC);

            $stmtPendentesCount = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE usuario_id = :usuario_id AND status = 'pendente'");
            $stmtPendentesCount->execute(['usuario_id' => $userId]);
            $countPendentes = $stmtPendentesCount->fetchColumn();

            $stmtConcluidasCount = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE usuario_id = :usuario_id AND status = 'concluido'");
            $stmtConcluidasCount->execute(['usuario_id' => $userId]);
            $countConcluidas = $stmtConcluidasCount->fetchColumn();
          ?>

          <?php if (empty($tarefasConcluidas)): ?>
            <p>Nenhuma tarefa concluída ainda.</p>
          <?php else: ?>
            <ul class="list-group mb-4">
              <?php foreach ($tarefasConcluidas as $tarefa): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span><?= htmlspecialchars($tarefa['titulo'], ENT_QUOTES, 'UTF-8') ?> - <small><?= htmlspecialchars($tarefa['data_entrega'], ENT_QUOTES, 'UTF-8') ?></small></span>
                  <i data-lucide="check-circle" class="text-success"></i>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <h4>Resumo de Tarefas</h4>
          <canvas id="graficoTarefas" style="max-width: 350px; max-height: 300px;"></canvas>

          <script>
            const ctx = document.getElementById('graficoTarefas').getContext('2d');
            ctx.canvas.width = 350;
            ctx.canvas.height = 300;

            const graficoTarefas = new Chart(ctx, {
              type: 'doughnut',
              data: {
                labels: ['Pendentes', 'Concluídas'],
                datasets: [{
                  data: [<?= (int)$countPendentes ?>, <?= (int)$countConcluidas ?>],
                  backgroundColor: ['#dc3545', '#28a745']
                }]
              },
              options: {
                responsive: true,
                plugins: {
                  legend: { position: 'bottom' }
                }
              }
            });
          </script>
        </div>

      <?php elseif ($page === 'minhas_tarefas'): ?>
        <div class="minhas-tarefas-content">
          <h4>Minhas Tarefas</h4>
          <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCriarTarefa">
            <i data-lucide="plus" class="me-1"></i> Criar Nova Tarefa
          </button>

          <table class="table table-striped">
            <thead>
              <tr>
                <th>Concluído</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Data de Entrega</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE usuario_id = :usuario_id");
              $stmt->execute(['usuario_id' => $userId]);
              $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

              foreach ($tarefas as $tarefa):
              ?>
              <tr>
                <td class="text-center">
                  <input type="checkbox" class="status-checkbox" data-id="<?= (int)$tarefa['id'] ?>" <?= ($tarefa['status'] === 'concluido') ? 'checked' : '' ?> />
                </td>
                <td><?= htmlspecialchars($tarefa['titulo'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($tarefa['descricao'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($tarefa['data_entrega'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(ucfirst($tarefa['prioridade']), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= ($tarefa['status'] === 'concluido') ? 'Concluída' : 'Pendente' ?></td>
                <td>
                  <a href="tarefas/editar_tarefa.php?id=<?= (int)$tarefa['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                  <a href="tarefas/deletar_tarefa.php?id=<?= (int)$tarefa['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmar exclusão?')">Excluir</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <!-- Modal para criar nova tarefa -->
          <div class="modal fade" id="modalCriarTarefa" tabindex="-1" aria-labelledby="modalCriarTarefaLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <form method="POST" action="tarefas/tarefasPessoa.php"  onsubmit="return false;">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalCriarTarefaLabel">Criar Nova Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                  </div>
                  <div class="modal-body">
                    <div id="formMessage" style="margin-bottom: 1rem;"></div>
                    <div class="mb-3">
                      <label for="titulo" class="form-label">Título</label>
                      <input type="text" class="form-control" id="titulo" name="titulo" required />
                    </div>
                    <div class="mb-3">
                      <label for="descricao" class="form-label">Descrição</label>
                      <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="dataEntrega" class="form-label">Data de Entrega</label>
                      <input type="date" class="form-control" id="dataEntrega" name="data_entrega" required />
                    </div>
                    <div class="mb-3">
                      <label for="prioridade" class="form-label">Prioridade</label>
                      <select class="form-select" id="prioridade" name="prioridade" required>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                      </select>
                    </div>
                    <input type="hidden" name="usuario_id" value="<?= (int)$userId ?>" />
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const form = document.querySelector('#modalCriarTarefa form');
              const formMessage = document.getElementById('formMessage');

              form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(form);
                fetch(form.action, {
                  method: 'POST',
                  body: formData
                })
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    formMessage.innerHTML = '<div class="alert alert-success">Tarefa criada com sucesso!</div>';
                    form.reset();
                    setTimeout(() => {
                      window.location.reload();
                    }, 1500);
                  } else {
                    formMessage.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                  }
                })
                .catch(() => {
                  formMessage.innerHTML = '<div class="alert alert-danger">Erro ao criar tarefa.</div>';
                });
              });
            });
          </script>

        </div>

      <?php else: ?>
        <p>Ahhhh olá</p>
      <?php endif; ?>

    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>lucide.replace();</script>
</body>
</html>