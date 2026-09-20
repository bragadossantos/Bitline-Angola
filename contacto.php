<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina    = 'Contacto';
$descricaoPagina = 'Fala com a equipa da Bitline Angola. Contactos Unitel e Africell, email e localização em Luanda.';
$paginaAtiva     = 'contacto';
require __DIR__ . '/includes/header.php';
?>

  <section id="contacto-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">Fala Connosco</span>
      <h1 class="section-title">Vamos construir algo juntos</h1>
      <p class="section-sub">Preenche o formulário ou contacta-nos diretamente. Respondemos em menos de 24 horas úteis.</p>
    </div>
  </section>

  <section id="contacto-principal">
    <div class="contacto-grid reveal">

      <div class="contacto-info">
        <h3>Informações de Contacto</h3>
        <p class="contacto-info-sub">Escolhe o canal que preferires — estamos disponíveis por chamada, WhatsApp ou email.</p>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-solid fa-phone"></i></div>
          <div>
            <span class="contacto-item-label">Unitel</span>
            <a href="tel:+244<?= CONTACTO_UNITEL ?>"><?= formatarTelefone(CONTACTO_UNITEL) ?></a>
          </div>
        </div>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-solid fa-phone"></i></div>
          <div>
            <span class="contacto-item-label">Africell</span>
            <a href="tel:+244<?= CONTACTO_AFRICELL ?>"><?= formatarTelefone(CONTACTO_AFRICELL) ?></a>
          </div>
        </div>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-brands fa-whatsapp"></i></div>
          <div>
            <span class="contacto-item-label">WhatsApp</span>
            <a href="https://wa.me/244<?= CONTACTO_UNITEL ?>" target="_blank">Enviar mensagem</a>
          </div>
        </div>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-solid fa-envelope"></i></div>
          <div>
            <span class="contacto-item-label">Email</span>
            <a href="mailto:bitlineangola@outlook.com">bitlineangola@outlook.com</a>
          </div>
        </div>

        <div class="contacto-item">
          <div class="contacto-icon"><i class="fa-solid fa-location-dot"></i></div>
          <div>
            <span class="contacto-item-label">Localização</span>
            <span>Luanda, Angola</span>
          </div>
        </div>

        <div class="contacto-horario">
          <h5>Horário de Atendimento</h5>
          <p>Segunda a Sexta: 08h00 – 18h00</p>
          <p>Suporte de emergência: 24/7 para clientes com contrato SLA</p>
        </div>
      </div>

      <div class="contacto-form-box">
        <h3>Envia-nos uma mensagem</h3>
        <?php mostrarMensagemFormularioSemJS(); ?>
        <form class="contact-form-full" id="contactFormFull" action="actions/processar_contacto.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
          <input type="text" name="website_url_check" style="display:none" tabindex="-1" autocomplete="off" />

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
            <label for="email">Email profissional *</label>
            <input type="email" id="email" name="email" placeholder="nome@empresa.co.ao" required>
          </div>

          <div class="form-group">
            <label for="assunto">Assunto <span style="color: var(--grey); font-weight: 400;">— escolhe o serviço mais próximo do que precisas</span></label>
            <select id="assunto" name="assunto">
              <option value="Consultoria Geral">Consultoria Geral</option>
              <option value="Redes & Infraestrutura">Redes & Infraestrutura</option>
              <option value="Cibersegurança">Cibersegurança</option>
              <option value="Cloud & Servidores">Cloud & Servidores</option>
              <option value="Desenvolvimento de Software">Desenvolvimento de Software</option>
              <option value="Criação de Bases de Dados">Criação de Bases de Dados</option>
              <option value="Marketing Digital">Marketing Digital</option>
              <option value="Formação em TI">Formação em TI</option>
              <option value="Consultoria em TI">Consultoria em TI</option>
              <option value="Suporte Técnico">Suporte Técnico</option>
            </select>
          </div>

          <div class="form-group">
            <label for="mensagem">Mensagem *</label>
            <textarea id="mensagem" name="mensagem" rows="5" placeholder="Conta-nos um pouco sobre o teu projeto ou necessidade..." required></textarea>
          </div>

          <button type="submit">Enviar Mensagem</button>
        </form>
        <div id="formResponseFull" style="display: none;"></div>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
