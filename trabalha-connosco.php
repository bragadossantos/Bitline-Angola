<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina    = 'Trabalha Connosco';
$descricaoPagina = 'Junta-te à equipa da Bitline Angola. Procuramos pessoas apaixonadas por tecnologia para crescer connosco.';
$paginaAtiva     = 'trabalha-connosco';
require __DIR__ . '/includes/header.php';
?>

  <section id="contacto-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">Junta-te à Equipa</span>
      <h1 class="section-title">Trabalha Connosco</h1>
      <p class="section-sub">Somos uma equipa jovem, apaixonada por tecnologia. Se gostas de aprender, resolver problemas reais e crescer com um projeto em expansão, queremos conhecer-te.</p>
    </div>
  </section>

  <section id="contacto-principal">
    <div class="contacto-grid reveal">

      <div class="contacto-info">
        <h3>Porquê trabalhar na Bitline?</h3>
        <p class="contacto-info-sub">Não somos uma empresa gigante — e isso é uma vantagem. Aqui participas em projetos reais desde o primeiro dia, aprendes com quem já passou pelo problema, e o teu trabalho tem impacto visível.</p>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-solid fa-graduation-cap"></i></div>
          <div>
            <span class="contacto-item-label">Aprendizagem</span>
            <span>Trabalho lado a lado com quem já tem experiência no mercado</span>
          </div>
        </div>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-solid fa-people-group"></i></div>
          <div>
            <span class="contacto-item-label">Ambiente</span>
            <span>Equipa jovem, próxima, sem burocracia desnecessária</span>
          </div>
        </div>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-solid fa-rocket"></i></div>
          <div>
            <span class="contacto-item-label">Crescimento</span>
            <span>Empresa em expansão — quem entra cedo, cresce com o projeto</span>
          </div>
        </div>

        <div class="contacto-horario">
          <h5>Áreas onde costumamos precisar de gente</h5>
          <p>Redes & Infraestrutura · Cibersegurança · Desenvolvimento de Software</p>
          <p>Bases de Dados · Suporte Técnico · Design & Marketing</p>
        </div>
      </div>

      <div class="contacto-form-box">
        <h3>Envia a tua candidatura</h3>
        <?php mostrarMensagemFormularioSemJS(); ?>
        <form class="contact-form-full" id="candidaturaForm" action="actions/processar_candidatura.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
          <input type="text" name="website_url_check" style="display:none" tabindex="-1" autocomplete="off">

          <div class="form-row">
            <div class="form-group">
              <label for="nome">Nome completo *</label>
              <input type="text" id="nome" name="nome" placeholder="O teu nome" required>
            </div>
            <div class="form-group">
              <label for="telefone">Telefone</label>
              <input type="text" id="telefone" name="telefone" placeholder="9XX XXX XXX">
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" placeholder="o-teu-email@exemplo.com" required>
          </div>

          <div class="form-group">
            <label for="area_interesse">Área de interesse</label>
            <select id="area_interesse" name="area_interesse">
              <option value="Redes & Infraestrutura">Redes & Infraestrutura</option>
              <option value="Cibersegurança">Cibersegurança</option>
              <option value="Desenvolvimento de Software">Desenvolvimento de Software</option>
              <option value="Bases de Dados">Bases de Dados</option>
              <option value="Suporte Técnico">Suporte Técnico</option>
              <option value="Design & Marketing">Design & Marketing</option>
              <option value="Outra">Outra</option>
            </select>
          </div>

          <div class="form-group">
            <label for="mensagem">Fala-nos um pouco sobre ti</label>
            <textarea id="mensagem" name="mensagem" rows="4" placeholder="Experiência, motivação, o que procuras..."></textarea>
          </div>

          <div class="form-group">
            <label for="cv">Currículo (PDF, até 4MB) — opcional</label>
            <input type="file" id="cv" name="cv" accept=".pdf">
          </div>

          <button type="submit">Enviar Candidatura</button>
        </form>
        <div id="formResponseCandidatura" style="display: none;"></div>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
