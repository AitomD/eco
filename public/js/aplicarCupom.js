 function mostrarAlerta() {
        const alertElement = document.getElementById('alert-info');
        if (alertElement) {
            alertElement.classList.remove('d-none');

            // Auto-fechar após 5 segundos
            setTimeout(() => {
                alertElement.classList.add('d-none');
            }, 5000);
        }
    }

    function fecharAlerta() {
        const alertElement = document.getElementById('alert-info');
        if (alertElement) {
            alertElement.classList.add('d-none');
        }
    }

    function aplicarCupom(codigo) {
        // Desabilitar o botão durante o processamento
        const botao = event.target;
        const textoOriginal = botao.innerHTML;
        botao.disabled = true;
        botao.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Aplicando...';

        // Aplicar o cupom via AJAX
        const formData = new FormData();
        formData.append('ajax_cupom', '1');
        formData.append('codigo_cupom', codigo);

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.sucesso) {
                    // Cupom aplicado com sucesso
                    botao.innerHTML = '<i class="bi bi-check-circle me-1"></i>Aplicado!';
                    botao.classList.remove('btn-primary');
                    botao.classList.add('btn-success');

                    // Redirecionar para o carrinho após 1 segundo
                    setTimeout(() => {
                        window.location.href = 'index.php?url=carrinho';
                    }, 1000);
                } else {
                    // Erro ao aplicar cupom
                    alert(data.mensagem || 'Erro ao aplicar cupom');
                    botao.innerHTML = textoOriginal;
                    botao.disabled = false;
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro de conexão. Tente novamente.');
                botao.innerHTML = textoOriginal;
                botao.disabled = false;
            });
    }

    // Adicionar estilos para os botões durante o carregamento
    document.addEventListener('DOMContentLoaded', function () {
        const style = document.createElement('style');
        style.textContent = `
        .btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .coupon-card {
            transition: transform 0.2s ease;
        }
        .coupon-card:hover {
            transform: translateY(-2px);
        }
    `;
        document.head.appendChild(style);
    });

   function copiarCodigo(codigo, elemento) {
    // 'elemento' já é o div.code-container (passado via 'this' no HTML)
    
    // 1. Usa a API moderna de Clipboard para copiar
    navigator.clipboard.writeText(codigo).then(function() {
        
        // 2. Seleção dos Elementos
        const hintElement = elemento.querySelector('.copy-hint');
        const originalText = hintElement.innerText; // Guarda o texto original

        // 3. Aplica Feedback Visual
        
        // A) Altera o texto de feedback
        hintElement.innerText = "Copiado! 🎉";
        hintElement.classList.add('text-success'); 
        
        // B) Altera a borda do contêiner principal (elemento)
        elemento.classList.add('border-success');
        elemento.classList.add('text-success'); 
        
        // 4. Restaura o estado original após 2 segundos
        setTimeout(() => {
            hintElement.innerText = originalText;
            hintElement.classList.remove('text-success');
            
            // Remove as classes de sucesso do contêiner principal
            elemento.classList.remove('border-success');
            elemento.classList.remove('text-success'); 
        }, 2000);

    }).catch(function(err) {
        console.error('Erro ao copiar: ', err);
        alert('Não foi possível copiar o código automaticamente.');
    });
}