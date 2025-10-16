/**
 * Sistema de Autenticação - JavaScript
 * Gerencia login, logout e interface do usuário
 */

class AuthSystem {
    constructor() {
        this.init();
    }

    init() {
        // Verificar se existe botão de logout
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            this.setupLogout(logoutBtn);
        }

        // Verificar se existe formulário de login
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            this.setupLoginForm(loginForm);
        }

        // Verificar se existe formulário de cadastro
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            this.setupRegisterForm(registerForm);
        }

        // Adicionar estilos dinâmicos
        this.addDynamicStyles();
    }

    /**
     * Configurar logout
     */
    setupLogout(logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.logout();
        });
    }

    /**
     * Executar logout
     */
    async logout() {
        const logoutBtn = document.getElementById('logoutBtn');
        
        try {
            // Mostrar loading
            const originalContent = logoutBtn.innerHTML;
            logoutBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-2"></i>Saindo...';
            logoutBtn.classList.add('disabled');

            const formData = new FormData();
            formData.append('action', 'logout');

            const response = await fetch('../app/core/user.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Redirecionar imediatamente sem mensagem
                window.location.href = 'index.php?url=home';
            } else {
                // Em caso de erro, apenas recarregar a página
                window.location.reload();
            }

        } catch (error) {
            console.error('Erro no logout:', error);
            // Em caso de erro, recarregar a página
            window.location.reload();
        }
    }

    /**
     * Configurar formulário de login
     */
    setupLoginForm(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.processLogin(form);
        });
    }

    /**
     * Processar login
     */
    async processLogin(form) {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        try {
            this.setButtonLoading(submitBtn, true);
            
            const response = await fetch('../app/core/user.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Redirecionar imediatamente sem mensagem
                window.location.href = data.redirect || 'index.php?url=home';
            } else {
                // Apenas logar erro no console
                console.log('Erro no login:', data.message);
            }

        } catch (error) {
            console.error('Erro no login:', error);
        } finally {
            this.setButtonLoading(submitBtn, false);
        }
    }

    /**
     * Configurar formulário de registro
     */
    setupRegisterForm(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.processRegister(form);
        });
    }

    /**
     * Processar registro
     */
    async processRegister(form) {
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');

        try {
            this.setButtonLoading(submitBtn, true);

            const response = await fetch('../app/core/user.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Redirecionar imediatamente sem mensagem
                window.location.href = data.redirect || 'index.php?url=home';
            } else {
                // Apenas logar erro no console
                console.log('Erro no registro:', data.message);
            }

        } catch (error) {
            console.error('Erro no registro:', error);
        } finally {
            this.setButtonLoading(submitBtn, false);
        }
    }

    /**
     * Controlar estado de loading do botão
     */
    setButtonLoading(button, isLoading) {
        const btnText = button.querySelector('.btn-text');
        const btnLoading = button.querySelector('.btn-loading');

        if (isLoading) {
            if (btnText) btnText.classList.add('d-none');
            if (btnLoading) btnLoading.classList.remove('d-none');
            button.disabled = true;
        } else {
            if (btnText) btnText.classList.remove('d-none');
            if (btnLoading) btnLoading.classList.add('d-none');
            button.disabled = false;
        }
    }

    /**
     * Adicionar estilos dinâmicos
     */
    addDynamicStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .user-section .dropdown-menu {
                min-width: 200px;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }
            
            .user-section .dropdown-item:hover {
                background-color: #3F0071 !important;
                color: #fff !important;
                transition: all 0.2s ease;
            }

            .btn-loading {
                pointer-events: none;
            }

            .form-control.is-invalid {
                border-color: #dc3545;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.9.4M6.5 8.2v.01m-.01 0a.01.01 0 1 1 0 0Z'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }

            .form-control.is-valid {
                border-color: #198754;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.94-.94 1.77-1.77.94-.94L6.23 3 4.77 1.54 3.83.6 2.89 1.54l-.94.94zm0 0L1.36 5.8l-.94-.94L0 5.29l.42.43z'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Verificar se usuário está logado (para uso em outras páginas)
     */
    static async checkAuthStatus() {
        try {
            const response = await fetch('../app/core/User.php?check_auth=1');
            const data = await response.json();
            return data.logged_in || false;
        } catch (error) {
            console.error('Erro ao verificar autenticação:', error);
            return false;
        }
    }

    /**
     * Redirecionar para login se não estiver autenticado
     */
    static requireAuth(redirectUrl = 'index.php?url=login') {
        this.checkAuthStatus().then(isLoggedIn => {
            if (!isLoggedIn) {
                window.location.href = redirectUrl;
            }
        });
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    new AuthSystem();
});

// Exportar para uso global
window.AuthSystem = AuthSystem;