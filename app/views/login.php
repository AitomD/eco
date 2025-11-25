<div class="back-to-home">
        <a href="index.php?url=home">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
            Voltar ao Início
        </a>
    </div>

    <div class="login-container" data-aos="zoom-in" data-aos-duration="1500">
        <div class="login-header">
            <h2>Bem-vindo de volta!</h2>
            <p>Faça login em sua conta</p>
        </div>

        <form action="../app/core/user.php" method="POST" id="loginForm">
            <input type="hidden" name="action" value="login">
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required>
                <div class="invalid-feedback" id="email-error"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                <div class="invalid-feedback" id="password-error"></div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" style="background: rgba(0, 0, 0, 0.3); border-color: #3F0071;">
                <label class="form-check-label" for="remember" style="color: #B0B0B0; font-size: 0.9rem;">
                    Lembrar-me
                </label>
            </div>

            <button type="submit" class="btn btn-login" id="loginBtn">
                <span class="btn-text">Entrar</span>
                <span class="btn-loading d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Entrando...
                </span>
            </button>
        </form>

        <div class="divider">
            <span>ou</span>
        </div>

        <div class="login-links">
            <p style="color: #B0B0B0; margin-top: 1rem; font-size: 0.9rem;">
                Não tem uma conta?
                <a href="index.php?url=cadastro" style="color: #610094; font-weight: bold;">Cadastre-se</a>
            </p>
        </div>
    </div>
    
    <script>
        // Função para mostrar loading no botão
        function setLoadingState(isLoading) {
            const btnText = document.querySelector('.btn-text');
            const btnLoading = document.querySelector('.btn-loading');
            const loginBtn = document.getElementById('loginBtn');
            
            if (isLoading) {
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
                loginBtn.disabled = true;
            } else {
                btnText.classList.remove('d-none');
                btnLoading.classList.add('d-none');
                loginBtn.disabled = false;
            }
        }

        // Validação e submissão do formulário
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            // Limpar erros anteriores
            document.querySelectorAll('.form-control').forEach(input => {
                input.classList.remove('is-invalid');
            });

            // Validações básicas
            let hasError = false;

            if (!email) {
                document.getElementById('email').classList.add('is-invalid');
                document.getElementById('email-error').textContent = 'Email é obrigatório';
                hasError = true;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                document.getElementById('email').classList.add('is-invalid');
                document.getElementById('email-error').textContent = 'Email inválido';
                hasError = true;
            }

            if (!password) {
                document.getElementById('password').classList.add('is-invalid');
                document.getElementById('password-error').textContent = 'Senha é obrigatória';
                hasError = true;
            }

            if (hasError) {
                return;
            }

            // Enviar requisição AJAX
            setLoadingState(true);

            const formData = new FormData();
            formData.append('action', 'login');
            formData.append('email', email);
            formData.append('password', password);
            if (remember) {
                formData.append('remember', '1');
            }

            fetch('../app/core/user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(false);
                
                if (data.success) {
                    // Redirecionar imediatamente sem mensagem
                    window.location.href = data.redirect || 'index.php?url=home';
                } else {
                    // Mostrar erro de credenciais inválidas
                    if (data.message && data.message.includes('Email ou senha incorretos')) {
                        document.getElementById('password').classList.add('is-invalid');
                        document.getElementById('password-error').textContent = 'Email ou senha incorretos';
                        document.getElementById('email').classList.add('is-invalid');
                        document.getElementById('email-error').textContent = '';
                    } else {
                        // Outros tipos de erro
                        console.log('Erro no login:', data.message);
                    }
                }
            })
            .catch(error => {
                setLoadingState(false);
                console.error('Erro:', error);
            });
        });

        // Efeito de focus nos inputs e limpeza de erros
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.transition = 'transform 0.2s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });

            // Limpar erros quando o usuário começar a digitar
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorElement = document.getElementById(this.id + '-error');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            });
        });

    </script>
