<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
    <a href="teste.html" class="mb-4 text-decoration-none text-muted d-flex align-items-center">
        <!-- Ícone seta esquerda -->
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 1-.5.5H3.707l4.147 4.146a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 1 1 .708.708L3.707 7.5H14.5A.5.5 0 0 1 15 8z"/>
        </svg>
        Voltar ao início
    </a>

    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-header text-center bg-white border-bottom-0">
            <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 48px; height: 48px; background: linear-gradient(45deg, #4f46e5, #a78bfa); border-radius: 0.5rem;">
                <span class="text-white fw-bold fs-4">N</span>
            </div>
            <h2 class="card-title fs-4">Você pode criar uma<br />conta em segundos!</h2>
            <p class="text-muted mb-0">Comece a organizar seus projetos agora mesmo</p>
        </div>
        <div class="card-body">

            <form method="POST" action="../src/cadastrologica.php" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Nome completo</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1">
                            <!-- Ícone usuário -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                <path d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-6a6 6 0 0 0-4.473 10.242c.032-.225.07-.446.114-.658.198-1.012.76-1.785 1.59-2.465A3.983 3.983 0 0 1 8 7a3.983 3.983 0 0 1 3.772 2.12c.83.68 1.392 1.453 1.59 2.465.044.212.082.433.114.658A6 6 0 0 0 8 2z"/>
                            </svg>
                        </span>
                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail profissional</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required />
                </div>

                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Criar senha</label>
                    <input
                        type="password"
                        class="form-control pe-5"
                        id="password"
                        name="password"
                        placeholder="Mínimo de 8 caracteres"
                        required
                        minlength="8"
                    />
                    <button
                        type="button"
                        class="btn btn-outline-secondary btn-sm position-absolute top-50 end-0 translate-middle-y"
                        id="togglePassword"
                        style="width: 38px; height: 38px; padding: 0;"
                        aria-label="Mostrar ou ocultar senha"
                    >
                        <!-- Ícone olho aberto -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z"/>
                        </svg>
                    </button>
                    <div class="form-text">Pelo menos 8 caracteres</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Criar conta
                </button>
            </form>

            <div class="mt-3 text-center">
                <small class="text-muted">
                    Ao clicar no botão acima, você concorda com nossos
                    <a href="#" class="text-decoration-none">termos de serviço</a> e
                    <a href="#" class="text-decoration-none">política de privacidade</a>.
                </small>
            </div>

            <div class="mt-3 text-center">
                <small class="text-muted">
                    Já tem uma conta?
                    <a href="login.php" class="text-decoration-none">Entrar</a>
                </small>
            </div>
        </div>
    </div>
</div>

<script>
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', () => {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    togglePassword.innerHTML = `<!-- SVG olho fechado -->
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
        <path d="M13.359 11.238l2.829 2.829-.708.708-2.83-2.83A7.028 7.028 0 0 1 8 13.5c-4.5 0-8-5.5-8-5.5a12.733 12.733 0 0 1 3.44-4.8L1.146 2.146l.708-.708 12 12-.707.707z"/>
        <path d="M10.478 9.565a2.5 2.5 0 0 1-3.243-3.243l3.243 3.243z"/>
        <path d="M4.613 3.034A6.77 6.77 0 0 1 8 2.5c4.5 0 7.923 5.5 7.923 5.5a12.683 12.683 0 0 1-3.447 4.815l-1.89-1.89A3.478 3.478 0 0 0 10.479 9.57l-5.866-5.867z"/>
      </svg>`;
  } else {
    passwordInput.type = "password";
    togglePassword.innerHTML = `<!-- SVG olho aberto -->
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z"/>
        <path d="M8 5.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z"/>
      </svg>`;
  }
});
</script>
    <script>
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            const type = urlParams.get('type') || 'danger';

            if (message) {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} text-center`;
                alertDiv.textContent = message;
                alertDiv.style.maxWidth = '400px';
                alertDiv.style.margin = '0 auto 1rem';
                alertDiv.style.borderRadius = '0.25rem';
                document.querySelector('form').before(alertDiv);
            }
    </script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>