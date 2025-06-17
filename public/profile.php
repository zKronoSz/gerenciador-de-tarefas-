<?php 
session_start();

require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/ProfileLogic.php';

use Src\Database;
use Src\ProfileLogic;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$database = new Database();
$profileLogic = new ProfileLogic($database);
$user = $profileLogic->getUserProfileData($_SESSION['user_id']);

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataToUpdate = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
    ];

    if ($profileLogic->updateProfile($_SESSION['user_id'], $dataToUpdate)) {
        $_SESSION['user_name'] = $dataToUpdate['name'];
        $_SESSION['user_email'] = $dataToUpdate['email'];
        header("Location: profile.php?success=true");
        exit();
    } else {
        header("Location: profile.php?error=update_failed");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Perfil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
  <div class="d-flex">
    <div class="bg-dark text-white p-3 vh-100" style="width: 250px;">
      <h4>Sidebar</h4>
    </div>

    <div class="flex-grow-1">
      <nav class="navbar navbar-light bg-white shadow-sm px-4">
        <span class="navbar-brand mb-0 h1">Dashboard</span>
      </nav>

      <main class="p-4">
        <div class="container">

          <!-- Cartão de Perfil -->
          <div class="card mb-4">
            <div class="card-body d-flex align-items-center">
              <div class="me-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 32px;">
                  <?php echo strtoupper($user['initial'] ?? ''); ?>
                </div>
              </div>
              <div class="flex-grow-1">
                <h4 class="mb-0"><?php echo htmlspecialchars($user['name'] ?? ''); ?></h4>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>

          <!-- pegar a data-->
              <small class="text-muted">
              <?php
              $dt = new DateTime($user['criado_em']);
              $fmt = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'America/Sao_Paulo', IntlDateFormatter::GREGORIAN, "LLLL 'de' yyyy");
              $dataFormatada = ucfirst($fmt->format($dt));
              echo 'Membro desde ' . $dataFormatada;
              ?>
              </small>

              </div>
              <div>
                <button class="btn btn-outline-secondary" id="toggleEditBtn">Editar Perfil</button>
              </div>
            </div>
          </div>

          <!-- Formulário de Edição -->
          <form id="profileForm" method="POST" action="profile.php">
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="name" class="form-label">Nome</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" disabled>
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
              </div>
            </div>

            <div class="d-flex justify-content-end gap-2" id="formActions" style="display: none;">
              <button type="button" class="btn btn-secondary" id="cancelEditBtn">Cancelar</button>
              <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
          </form>

          <!-- Contato -->
          <div class="card mt-4">
            <div class="card-header">Contato</div>
            <div class="card-body">
              <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    const toggleEditBtn = document.getElementById('toggleEditBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const formInputs = document.querySelectorAll('#profileForm input');
    const formActions = document.getElementById('formActions');

    toggleEditBtn.addEventListener('click', () => {
      formInputs.forEach(input => input.disabled = false);
      formActions.style.display = 'flex';
      toggleEditBtn.style.display = 'none';
    });

    cancelEditBtn.addEventListener('click', () => {
      window.location.reload();
    });
  </script>
</body>
</html>