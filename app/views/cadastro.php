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

        <!-- Indicador de Etapas -->
        <div class="steps-indicator mb-4">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-title">Dados Pessoais</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-title">Endereço</div>
            </div>
        </div>

        <form action="../app/core/user.php" method="POST" id="registerForm" novalidate>
            <input type="hidden" name="action" value="register">
            
            <!-- ETAPA 1: Dados Pessoais -->
            <div class="form-step active" id="step-1">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="firstName" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="firstName" name="firstName"
                            placeholder="Seu nome" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="lastName" class="form-label">Sobrenome</label>
                        <input type="text" class="form-control" id="lastName" name="lastName"
                            placeholder="Seu sobrenome" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="seu@email.com" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="birthDate" class="form-label">Data de Nascimento</label>
                        <input type="date" class="form-control" id="birthDate" name="birthDate" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Crie uma senha forte" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="confirmPassword" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                            placeholder="Digite a senha novamente" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                        <div class="strength-text" id="strengthText"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-register" id="nextBtn">
                        Próximo
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-left: 8px;">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- ETAPA 2: Endereço -->
            <div class="form-step" id="step-2">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label for="cep" class="form-label">CEP</label>
                        <input type="text" class="form-control" id="cep" name="cep"
                            placeholder="00000-000" maxlength="9" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco"
                            placeholder="Rua, Avenida, etc." required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-2 mb-2">
                        <label for="numero" class="form-label">Número</label>
                        <input type="text" class="form-control" id="numero" name="numero"
                            placeholder="123" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label for="complemento" class="form-label">Complemento</label>
                        <input type="text" class="form-control" id="complemento" name="complemento"
                            placeholder="Apt, Bloco, etc. (opcional)">
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="bairro" class="form-label">Bairro</label>
                        <input type="text" class="form-control" id="bairro" name="bairro"
                            placeholder="Seu bairro" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade"
                            placeholder="Sua cidade" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" id="estado" name="estado" required>
                            <option value="">Selecione o estado</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms" style="color: #B0B0B0; font-size: 0.85rem;">
                        Eu aceito os <a href="#" style="color: #610094;">Termos de Uso</a> e
                        <a href="#" style="color: #610094;"></a>
                    </label>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter">
                    <label class="form-check-label" for="newsletter" style="color: #B0B0B0; font-size: 0.85rem;">
                        Quero receber ofertas e novidades por email
                    </label>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="prevBtn">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 8px;">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                        </svg>
                        Anterior
                    </button>
                    <button type="submit" class="btn btn-register" id="submitBtn">
                        <span class="btn-text">Criar Conta</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Criando...
                        </span>
                    </button>
                </div>
            </div>
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
    
    <style>
        /* Animação de shake para campos com erro */
        @keyframes shake {
            0%, 20%, 40%, 60%, 80% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-5px);
            }
            15%, 35%, 55%, 75% {
                transform: translateX(5px);
            }
        }
        
        /* Melhorar aparência das mensagens de erro */
        .invalid-feedback {
            display: block !important;
            font-size: 0.875rem;
            color: #dc3545;
            margin-top: 0.25rem;
            font-weight: 500;
        }
        
        /* Destacar campos com erro */
        .form-control.is-invalid,
        .form-check-input.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        /* Melhorar aparência dos campos válidos */
        .form-control.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        /* Animação suave para transições */
        .form-control, .form-check-input {
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>
    
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
        
        // Elementos de navegação entre etapas
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const steps = document.querySelectorAll('.step');
        const formSteps = document.querySelectorAll('.form-step');
        let currentStep = 1;

        // Função para mostrar etapa específica
        function showStep(stepNumber) {
            // Atualizar indicadores
            steps.forEach(step => {
                const stepNum = parseInt(step.dataset.step);
                step.classList.toggle('active', stepNum === stepNumber);
                step.classList.toggle('completed', stepNum < stepNumber);
            });

            // Mostrar/esconder etapas do formulário
            formSteps.forEach((step, index) => {
                step.classList.toggle('active', index + 1 === stepNumber);
            });

            currentStep = stepNumber;
        }

        // Validar etapa atual
        function validateCurrentStep() {
            const currentFormStep = document.querySelector(`#step-${currentStep}`);
            const inputs = currentFormStep.querySelectorAll('input[required], select[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });

            return isValid;
        }

        // Navegação - Botão Próximo
        nextBtn.addEventListener('click', function() {
            if (validateCurrentStep()) {
                if (currentStep < 2) {
                    showStep(currentStep + 1);
                }
            } else {
                // Focar no primeiro campo inválido e fazer scroll
                const firstInvalid = document.querySelector(`#step-${currentStep} .is-invalid`);
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Adicionar animação de shake no campo inválido
                    firstInvalid.style.animation = 'shake 0.5s';
                    setTimeout(() => {
                        firstInvalid.style.animation = '';
                    }, 500);
                }
            }
        });

        // Navegação - Botão Anterior
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });

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

        // Função para obter mensagens específicas de campos obrigatórios
        function getRequiredFieldMessage(fieldId) {
            const messages = {
                'firstName': 'Por favor, digite seu nome',
                'lastName': 'Por favor, digite seu sobrenome',
                'email': 'Por favor, digite seu email',
                'birthDate': 'Por favor, informe sua data de nascimento',
                'password': 'Por favor, crie uma senha',
                'confirmPassword': 'Por favor, confirme sua senha',
                'cep': 'Por favor, informe seu CEP',
                'endereco': 'Por favor, informe seu endereço',
                'numero': 'Por favor, informe o número',
                'bairro': 'Por favor, informe seu bairro',
                'cidade': 'Por favor, informe sua cidade',
                'estado': 'Por favor, selecione seu estado',
                'terms': 'Você deve aceitar os termos de uso'
            };
            return messages[fieldId] || 'Este campo é obrigatório';
        }

        // Validar confirmação de senha
        confirmPassword.addEventListener('input', function() {
            validateField(this);
        });

        // Validação em tempo real
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
                
                // Verificação especial para email duplicado
                if (this.id === 'email' && this.value.trim() && !this.classList.contains('is-invalid')) {
                    checkEmailExists(this);
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });

        // Função para verificar se email já existe
        function checkEmailExists(emailField) {
            const email = emailField.value.trim();
            
            if (!email || !emailField.classList.contains('is-valid')) {
                return;
            }
            
            // Mostrar indicador de verificação
            const feedback = emailField.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = 'Verificando disponibilidade...';
                feedback.style.color = '#6c757d';
                feedback.style.display = 'block';
            }
            
            // Fazer requisição para verificar email
            fetch('../app/core/user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=check_email&email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    emailField.classList.add('is-invalid');
                    emailField.classList.remove('is-valid');
                    
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.textContent = 'Este email já está cadastrado. Tente fazer login ou use outro email.';
                        feedback.style.color = '#dc3545';
                    }
                } else {
                    emailField.classList.remove('is-invalid');
                    emailField.classList.add('is-valid');
                    
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                        feedback.style.display = 'none';
                    }
                }
            })
            .catch(error => {
                console.log('Erro ao verificar email:', error);
                // Em caso de erro, não marcar como inválido
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.style.display = 'none';
                }
            });
        }

        function validateField(field) {
            const value = field.value.trim();
            let isValid = true;
            let errorMessage = '';

            if (field.required && !value) {
                isValid = false;
                errorMessage = getRequiredFieldMessage(field.id);
            } else if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Por favor, digite um email válido';
                }
            } else if (field.id === 'password' && value) {
                if (value.length < 8) {
                    isValid = false;
                    errorMessage = 'A senha deve ter pelo menos 8 caracteres';
                }
            } else if (field.id === 'confirmPassword' && value) {
                if (value !== password.value) {
                    isValid = false;
                    errorMessage = 'As senhas não coincidem';
                }
            } else if (field.id === 'cep' && value) {
                const cepRegex = /^\d{5}-\d{3}$/;
                if (!cepRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'CEP deve estar no formato 00000-000';
                }
            } else if (field.id === 'birthDate' && value) {
                const birthDate = new Date(value);
                const today = new Date();
                const age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                
                if (age < 16 || (age === 16 && monthDiff < 0)) {
                    isValid = false;
                    errorMessage = 'Você deve ter pelo menos 16 anos';
                }
            }

            const feedback = field.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = errorMessage;
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

            // Validar todos os campos de ambas as etapas
            const allRequiredInputs = form.querySelectorAll('input[required], select[required]');
            allRequiredInputs.forEach(input => {
                if (!validateField(input)) {
                    isFormValid = false;
                }
            });

            // Validar termos de uso
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                terms.classList.add('is-invalid');
                const feedback = terms.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = 'Você deve aceitar os termos de uso para continuar';
                }
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
                const birthDateField = document.getElementById('birthDate');
                birthDateField.classList.add('is-invalid');
                const feedback = birthDateField.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.textContent = 'Você deve ter pelo menos 16 anos';
                }
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
                        // Verificar se o erro é de email duplicado
                        if (data.message && data.message.toLowerCase().includes('email já está cadastrado')) {
                            // Marcar o campo email como inválido
                            const emailField = document.getElementById('email');
                            emailField.classList.add('is-invalid');
                            emailField.classList.remove('is-valid');
                            
                            // Mostrar mensagem de erro específica
                            const feedback = emailField.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = 'Este email já está cadastrado. Tente fazer login ou use outro email.';
                            }
                            
                            // Voltar para a primeira etapa se não estiver nela
                            if (currentStep !== 1) {
                                showStep(1);
                                setTimeout(() => {
                                    emailField.focus();
                                    emailField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    
                                    // Animação de shake
                                    emailField.style.animation = 'shake 0.5s';
                                    setTimeout(() => {
                                        emailField.style.animation = '';
                                    }, 500);
                                }, 300);
                            } else {
                                emailField.focus();
                                emailField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                
                                // Animação de shake
                                emailField.style.animation = 'shake 0.5s';
                                setTimeout(() => {
                                    emailField.style.animation = '';
                                }, 500);
                            }
                        } else {
                            // Outros erros - apenas logar no console
                            console.log('Erro no cadastro:', data.message);
                        }
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
                // Focar no primeiro campo inválido e mostrar onde está o erro
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                    // Determinar em qual etapa está o erro
                    const stepWithError = firstInvalid.closest('.form-step');
                    if (stepWithError) {
                        const stepNumber = parseInt(stepWithError.id.split('-')[1]);
                        
                        // Se o erro não está na etapa atual, navegar para a etapa com erro
                        if (stepNumber !== currentStep) {
                            showStep(stepNumber);
                            
                            // Aguardar um pouco para a transição da etapa completar
                            setTimeout(() => {
                                firstInvalid.focus();
                                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                
                                // Animação de shake
                                firstInvalid.style.animation = 'shake 0.5s';
                                setTimeout(() => {
                                    firstInvalid.style.animation = '';
                                }, 500);
                            }, 300);
                        } else {
                            // Se o erro está na etapa atual, focar diretamente
                            firstInvalid.focus();
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            
                            // Animação de shake
                            firstInvalid.style.animation = 'shake 0.5s';
                            setTimeout(() => {
                                firstInvalid.style.animation = '';
                            }, 500);
                        }
                    }
                }
            }
        });

        // Máscara para CEP
        const cepInput = document.getElementById('cep');
        if (cepInput) {
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length <= 8) {
                    value = value.replace(/(\d{5})(\d{1,3})/, '$1-$2');
                }
                
                e.target.value = value;
                
                // Buscar endereço quando CEP estiver completo
                if (value.replace(/\D/g, '').length === 8) {
                    buscarEnderecoPorCEP(value.replace(/\D/g, ''));
                }
            });
        }

        // Função para buscar endereço por CEP
        function buscarEnderecoPorCEP(cep) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro || '';
                        document.getElementById('bairro').value = data.bairro || '';
                        document.getElementById('cidade').value = data.localidade || '';
                        document.getElementById('estado').value = data.uf || '';
                        
                        // Focar no complemento se endereço foi preenchido
                        if (data.logradouro) {
                            document.getElementById('complemento').focus();
                        }
                    }
                })
                .catch(error => {
                    console.log('Erro ao buscar CEP:', error);
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

        // Melhorar interação dos checkboxes
        const checkboxes = document.querySelectorAll('.form-check-input');
        checkboxes.forEach(checkbox => {
            // Adicionar evento de clique na label para melhor usabilidade
            const label = checkbox.nextElementSibling;
            if (label && label.classList.contains('form-check-label')) {
                label.addEventListener('click', function(e) {
                    if (e.target.tagName !== 'A') { // Não interferir com links
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            }

            // Feedback visual ao marcar/desmarcar
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                }
            });
        });
    </script>
