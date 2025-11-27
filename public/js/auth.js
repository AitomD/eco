/**
 * Sistema de Autenticação - JavaScript
 * Gerencia login, logout e interface do usuário
 */

class AuthSystem {
    constructor() {
        this.init();
    }

    init() {
        // CORREÇÃO: Usar querySelectorAll para pegar TODOS os botões de logout pela classe
        const logoutBtns = document.querySelectorAll('.logout-trigger');
        
        // Adiciona o evento a cada botão encontrado
        if (logoutBtns.length > 0) {
            logoutBtns.forEach(btn => {
                this.setupLogout(btn);
            });
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
    setupLogout(btn) {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            // CORREÇÃO: Passamos o botão clicado (btn) como argumento
            this.logout(btn);
        });
    }

    /**
     * Executar logout
     * CORREÇÃO: Recebe o botão específico para aplicar o efeito de loading nele
     */
    async logout(clickedBtn) {
        try {
            // Mostrar loading apenas no botão clicado
            const originalContent = clickedBtn.innerHTML;
            clickedBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin me-2"></i>Saindo...';
            clickedBtn.classList.add('disabled');
            // Prevenção extra para evitar cliques múltiplos
            clickedBtn.style.pointerEvents = 'none'; 

            const formData = new FormData();
            formData.append('action', 'logout');

            const response = await fetch('../app/core/user.php', {
                method: 'POST',
                body: formData
            });

            // Verificação se a resposta é JSON válido
            const data = await response.json();

            if (data.success) {
                window.location.href = 'index.php?url=home';
            } else {
                window.location.reload();
            }

        } catch (error) {
            console.error('Erro no logout:', error);
            // Remove o loading se der erro
            if(clickedBtn) {
                 clickedBtn.innerHTML = originalContent || 'Sair';
                 clickedBtn.classList.remove('disabled');
                 clickedBtn.style.pointerEvents = 'auto';
            }

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
                window.location.href = data.redirect || 'index.php?url=home';
            } else {
                console.log('Erro no login:', data.message);
                // Sugestão: Mostrar um alerta visual para o usuário aqui
                alert('Erro ao logar: ' + (data.message || 'Tente novamente'));
            }

        } catch (error) {
            console.error('Erro no login:', error);
        } finally {
            this.setButtonLoading(submitBtn, false);
        }
    }

    setupRegisterForm(form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.processRegister(form);
        });
    }

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
                window.location.href = data.redirect || 'index.php?url=home';
            } else {
                console.log('Erro no registro:', data.message);
                alert('Erro no cadastro: ' + (data.message || 'Tente novamente'));
            }

        } catch (error) {
            console.error('Erro no registro:', error);
        } finally {
            this.setButtonLoading(submitBtn, false);
        }
    }

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

    addDynamicStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .spin { animation: spin 1s linear infinite; }
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            .user-section .dropdown-menu { min-width: 200px; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); }
            .user-section .dropdown-item:hover { background-color: #3F0071 !important; color: #fff !important; transition: all 0.2s ease; }
            .btn-loading { pointer-events: none; }
            .logout-trigger { cursor: pointer; } 
        `;
        document.head.appendChild(style);
    }

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

    static requireAuth(redirectUrl = 'index.php?url=login') {
        this.checkAuthStatus().then(isLoggedIn => {
            if (!isLoggedIn) {
                window.location.href = redirectUrl;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new AuthSystem();
});

window.AuthSystem = AuthSystem;