<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - NoName</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <!-- Corrigido href para ir ao teste.html -->
        <a href="teste.html" class="d-inline-flex align-items-center mb-3 text-muted text-decoration-none">
          <!-- ícone seta -->
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l4.147 4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 1 1 .708.708L3.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
          </svg>
          Voltar ao inicio
        </a>

        <div class="card shadow-sm">
          <div class="card-header text-center bg-primary text-white">
            <div class="d-flex justify-content-center mb-3">
              <div class="rounded bg-gradient p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <span class="fs-4 fw-bold">NoName</span>
              </div>
            </div>
            <h3>Entrar na sua conta</h3>
            <p class="mb-0 text-white-50">Entre com seu email e senha para acessar o dashboard</p>
          </div>
          <div class="card-body">

            <!-- Corrigido o action para login.php -->
             
            <form method="POST" action="/gerenciadorTarefas/src/loginlogica.php" novalidate>
              <div class="mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required
                style="right:100px ; width: 100%;"
                />
              </div>

              <div class="mb-5 position-relative">

                <label for="password" class="form-label">Senha</label>
                    <input 
                         type="password" 
                         class="form-control pe-5" 
                         id="password" 
                         name="password" 
                         placeholder="Digite sua senha"  required>
                    
                 <button 
                        type="button" 
                        class="btn btn-sm btn-outline-secondary position-absolute top-50 translate-middle-y" 
                        id="togglePassword" 
                        style="top: 85px; right: 10px; width: 20px; height: 20px; padding: 0;" 
                        aria-label="Mostrar ou ocultar senha">

    <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
      <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
      <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/>
    </svg>
  </button>
</div>

              <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>

            <div class="mt-4 text-center">
              <p class="text-muted mb-0">
                Não tem uma conta?
                <a href="cadastro.php" class="text-primary text-decoration-none fw-medium">Cadastre-se</a>
              </p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Script visibilidade da senha e erro -->
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
    });

    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    if (error) {
      alert(decodeURIComponent(error));
    }
  </script>
</body>
</html>