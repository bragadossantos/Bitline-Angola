<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina    = 'Qual a Solução Ideal para Ti?';
$descricaoPagina = 'Responde a 3 perguntas rápidas e descobre qual o serviço da Bitline Angola mais indicado para a tua empresa.';
$paginaAtiva     = '';
require __DIR__ . '/includes/header.php';
?>

  <section id="contacto-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">Ferramenta Rápida</span>
      <h1 class="section-title">Qual a Solução Ideal para Ti?</h1>
      <p class="section-sub">Responde a 3 perguntas rápidas (menos de 1 minuto) e recebe uma recomendação personalizada.</p>
    </div>
  </section>

  <section id="quiz-secao">
    <div class="quiz-box reveal">

      <div class="quiz-progresso">
        <span class="quiz-passo-ativo" data-passo-num="1"></span>
        <span data-passo-num="2"></span>
        <span data-passo-num="3"></span>
      </div>

      <!-- Passo 1 -->
      <div class="quiz-passo" data-passo="1">
        <h3>1. Qual é o maior desafio da tua empresa neste momento?</h3>
        <div class="quiz-opcoes">
          <button type="button" class="quiz-opcao" data-valor="redes">🌐 A rede/internet está lenta ou instável</button>
          <button type="button" class="quiz-opcao" data-valor="ciberseguranca">🛡️ Preocupação com segurança e ciberataques</button>
          <button type="button" class="quiz-opcao" data-valor="dados">🗄️ Preciso de organizar e proteger os meus dados</button>
          <button type="button" class="quiz-opcao" data-valor="software">💻 Preciso de um sistema ou site novo</button>
          <button type="button" class="quiz-opcao" data-valor="suporte">🎧 Falta de suporte técnico confiável</button>
        </div>
      </div>

      <!-- Passo 2 -->
      <div class="quiz-passo" data-passo="2" style="display:none;">
        <h3>2. Qual é o tamanho da tua empresa?</h3>
        <div class="quiz-opcoes">
          <button type="button" class="quiz-opcao" data-valor="freelancer">Só eu / Freelancer</button>
          <button type="button" class="quiz-opcao" data-valor="pequena">Pequena equipa (até 10 pessoas)</button>
          <button type="button" class="quiz-opcao" data-valor="media">Empresa média (11–50 pessoas)</button>
          <button type="button" class="quiz-opcao" data-valor="grande">Empresa grande (50+ pessoas)</button>
        </div>
      </div>

      <!-- Passo 3 -->
      <div class="quiz-passo" data-passo="3" style="display:none;">
        <h3>3. Já têm alguma infraestrutura de TI?</h3>
        <div class="quiz-opcoes">
          <button type="button" class="quiz-opcao" data-valor="zero">Não, estamos a começar do zero</button>
          <button type="button" class="quiz-opcao" data-valor="desatualizada">Sim, mas está desatualizada</button>
          <button type="button" class="quiz-opcao" data-valor="ok">Sim, e funciona bem — só preciso de algo específico</button>
        </div>
      </div>

      <!-- Resultado -->
      <div class="quiz-resultado" data-resultado style="display:none;">
        <div class="quiz-resultado-icon"><i class="fa-solid fa-lightbulb"></i></div>
        <span class="section-label">Recomendação Bitline</span>
        <h2 id="quizResultadoTitulo">Serviço recomendado</h2>
        <p id="quizResultadoTexto"></p>
        <div class="quiz-resultado-acoes">
          <a href="#" id="quizResultadoCta" class="btn-primary">Falar sobre isto <i class="fa-solid fa-arrow-right"></i></a>
          <button type="button" class="btn-secondary" id="quizReiniciar">Recomeçar</button>
        </div>
      </div>

    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
