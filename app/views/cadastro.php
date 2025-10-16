<div class="back-to-home">
        <a href="index.php?url=home">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
            </svg>
            Voltar ao Início
        </a>
    </div>

    <div class="register-container" data-aos="zoom-in" data-aos-duration="1500">
        <div class="register-header">
            <h2>Criar nova conta</h2>
            <p>Junte-se a nós e comece suas compras</p>
        </div>

        <form action="../app/core/user.php" method="POST" id="registerForm" novalidate>
            <input type="hidden" name="action" value="register">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="firstName" name="firstName"
                        placeholder="Seu nome" required>
                    <div class="invalid-feedback">
                        Nome é obrigatório
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="lastName" class="form-label">Sobrenome</label>
                    <input type="text" class="form-control" id="lastName" name="lastName"
                        placeholder="Seu sobrenome" required>
                    <div class="invalid-feedback">
                        Sobrenome é obrigatório
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="seu@email.com" required>
                <div class="invalid-feedback">
                    Email válido é obrigatório
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Crie uma senha forte" required>
                <div class="password-strength">
                    <div class="strength-bar" id="strengthBar"></div>
                    <div class="strength-text" id="strengthText">Digite uma senha</div>
                </div>
                <div class="invalid-feedback">
                    Senha deve ter pelo menos 8 caracteres
                </div>
            </div>

            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                    placeholder="Digite a senha novamente" required>
                <div class="invalid-feedback">
                    As senhas não coincidem
                </div>
            </div>

            <div class="mb-3">
                <label for="birthDate" class="form-label">Data de Nascimento</label>
                <input type="date" class="form-control" id="birthDate" name="birthDate" required>
                <div class="invalid-feedback">
                    Data de nascimento é obrigatória
                </div>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Gênero</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="">Selecione (opcional)</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                    <option value="prefiro_nao_dizer">Prefiro não dizer</option>
                </select>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required
                    style="background: rgba(0, 0, 0, 0.3); border-color: #3F0071;">
                <label class="form-check-label" for="terms" style="color: #B0B0B0; font-size: 0.9rem;">
                    Eu aceito os <a href="#" style="color: #610094;">Termos de Uso</a> e
                    <a href="#" style="color: #610094;">Política de Privacidade</a>
                </label>
                <div class="invalid-feedback">
                    Você deve aceitar os termos para continuar
                </div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter"
                    style="background: rgba(0, 0, 0, 0.3); border-color: #3F0071;">
                <label class="form-check-label" for="newsletter" style="color: #B0B0B0; font-size: 0.9rem;">
                    Quero receber ofertas e novidades por email
                </label>
            </div>

            <button type="submit" class="btn btn-register" id="submitBtn">
                <span class="btn-text">Criar Conta</span>
                <span class="btn-loading d-none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Criando...
                </span>
            </button>
        </form>

        <div class="divider">
            <span>ou</span>
        </div>

        <div class="register-links">
            <p style="color: #B0B0B0; font-size: 0.9rem;">
                Já tem uma conta?
                <a href="index.php?url=login" style="color: #610094; font-weight: bold;">Faça login</a>
            </p>
        </div>
    </div>
    
    <script>
        // Elementos do formulário
        const form = document.getElementById('registerForm');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');

        // Verificar força da senha
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];

            if (password.length >= 8) strength++;
            else feedback.push('pelo menos 8 caracteres');

            if (/[a-z]/.test(password)) strength++;
            else feedback.push('letras minúsculas');

            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('letras maiúsculas');

            if (/[0-9]/.test(password)) strength++;
            else feedback.push('números');

            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('símbolos especiais');

            return {
                strength,
                feedback
            };
        }

        // Atualizar indicador de força da senha
        password.addEventListener('input', function() {
            const pwd = this.value;
            const result = checkPasswordStrength(pwd);

            strengthBar.className = 'strength-bar';

            if (pwd.length === 0) {
                strengthText.textContent = 'Digite uma senha';
                return;
            }

            switch (result.strength) {
                case 0:
                case 1:
                    strengthBar.classList.add('weak');
                    strengthText.textContent = 'Fraca - ' + result.feedback.slice(0, 2).join(', ');
                    strengthText.style.color = '#dc3545';
                    break;
                case 2:
                    strengthBar.classList.add('medium');
                    strengthText.textContent = 'Média - adicione ' + result.feedback.slice(0, 2).join(', ');
                    strengthText.style.color = '#ffc107';
                    break;
                case 3:
                    strengthBar.classList.add('strong');
                    strengthText.textContent = 'Forte - ' + (result.feedback.length > 0 ? 'adicione ' + result.feedback[0] : 'boa senha!');
                    strengthText.style.color = '#28a745';
                    break;
                case 4:
                case 5:
                    strengthBar.classList.add('very-strong');
                    strengthText.textContent = 'Muito forte - excelente!';
                    strengthText.style.color = '#28a745';
                    break;
            }
        });

        // Validar confirmação de senha
        confirmPassword.addEventListener('input', function() {
            if (password.value !== this.value) {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        });

        // Validação em tempo real
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            let isValid = true;

            if (field.required && !value) {
                isValid = false;
            } else if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(value);
            } else if (field.id === 'password' && value) {
                isValid = value.length >= 8;
            } else if (field.id === 'confirmPassword' && value) {
                isValid = value === password.value;
            }

            if (isValid) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            } else {
                field.classList.add('is-invalid');
                field.classList.remove('is-valid');
            }

            return isValid;
        }

        // Validação do formulário
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            let isFormValid = true;

            // Validar todos os campos
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isFormValid = false;
                }
            });

            // Validar termos de uso
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                terms.classList.add('is-invalid');
                isFormValid = false;
            } else {
                terms.classList.remove('is-invalid');
            }

            // Validar idade mínima (16 anos)
            const birthDate = new Date(document.getElementById('birthDate').value);
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();

            if (age < 16 || (age === 16 && monthDiff < 0)) {
                document.getElementById('birthDate').classList.add('is-invalid');
                const feedback = document.getElementById('birthDate').nextElementSibling;
                feedback.textContent = 'Você deve ter pelo menos 16 anos';
                isFormValid = false;
            }

            if (isFormValid) {
                // Mostrar loading
                submitBtn.disabled = true;
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');

                // Preparar dados do formulário
                const formData = new FormData(form);

                // Enviar requisição AJAX
                fetch('../app/core/user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Resetar botão
                    submitBtn.disabled = false;
                    btnText.classList.remove('d-none');
                    btnLoading.classList.add('d-none');

                    if (data.success) {
                        // Redirecionar imediatamente sem mensagem
                        window.location.href = data.redirect || 'index.php?url=home';
                    } else {
                        // Apenas logar erro no console, sem mostrar mensagem
                        console.log('Erro no cadastro:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    
                    // Resetar botão
                    submitBtn.disabled = false;
                    btnText.classList.remove('d-none');
                    btnLoading.classList.add('d-none');
                });
            } else {
                // Focar no primeiro campo inválido
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });

        // Máscara para telefone
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');

                if (value.length <= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                    value = value.replace(/(\d{2})(\d{4})/, '($1) $2');
                    value = value.replace(/(\d{2})/, '($1');
                }

                e.target.value = value;
            });
        }

        // Efeitos visuais nos inputs
        const allInputs = document.querySelectorAll('.form-control');
        allInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>
