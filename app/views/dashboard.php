<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../app/model/Estoque.php';

$id_user = $_SESSION['user_id'];
$estoque = new Estoque();
$resumo = $estoque->getResumoUsuario($id_user);
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
  <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Estoque</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Avaliaçes</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
    <!-- Gráfico -->
     <div class="container-fluid mt-4" style="width: 400px; height: 400px;">
    <canvas id="estoqueChart"></canvas>
    </div>
</div>

  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">Avaliaçes</div>
  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">...</div>
  <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div>
</div>
</div>


<!-- Script grafico estoque -->
<script>
const ctx = document.getElementById('estoqueChart').getContext('2d');

// Classe Estoque 
const entrada = <?= $resumo['ultima_entrada']['quantidade'] ?? 0 ?>;
const saida = <?= $resumo['ultima_saida']['quantidade'] ?? 0 ?>;
const saldo = <?= $resumo['estoque_total'] ?? 0 ?>;

const data = {
    labels: ['Última Entrada', 'Última Saída', 'Estoque Atual'],
    datasets: [{
        label: 'Estoque',
        data: [entrada, saida, saldo],
        backgroundColor: [
            'rgba(31, 111, 185, 0.7)', // azul
            'rgba(182, 19, 54, 0.7)', // vermelho
            'rgba(19, 204, 12, 0.7)'  // verde
        ],
        borderColor: [
            'rgba(31, 111, 185, 0.7)',
            'rgba(182, 19, 54, 0.7)',
            'rgba(19, 204, 12, 0.7)'
        ],
        borderWidth: 2
    }]
};

const config = {
    type: 'doughnut', // Rosquinha
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                  font: {
                    size: 18
                  }
                }
            },
            tooltip: {
                enabled: true
            }
        },
        cutout: '0%', // Deixa a rosquinha mais grossa
        animation: {
            animateRotate: true, // anima a rotação da rosquinha
            animateScale: true,  // anima o crescimento do gráfico
            duration: 500,      // duração da animação em ms
            easing: 'easeInOutSine' // tipos de animacao
        },
        hoverOffset: 30
    }
};

const estoqueChart = new Chart(ctx, config);
</script>
