<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina    = 'Política de Privacidade';
$descricaoPagina = 'Como a Bitline Angola recolhe, usa e protege os dados pessoais partilhados através deste site.';
$paginaAtiva     = '';
require __DIR__ . '/includes/header.php';
?>

  <section id="contacto-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">Transparência</span>
      <h1 class="section-title">Política de Privacidade</h1>
      <p class="section-sub">Última atualização: <?= date('d/m/Y') ?></p>
    </div>
  </section>

  <section id="faq-lista">
    <div class="faq-grupo reveal" style="max-width: 780px; margin: 0 auto;">

      <p style="color: var(--white-dim); line-height: 1.9; margin-bottom: 28px;">
        A Bitline Angola respeita a privacidade de quem visita este site. Este documento explica, de forma simples,
        que dados recolhemos, para que servem, e como os protegemos.
      </p>

      <h2 class="faq-categoria">1. Que dados recolhemos</h2>
      <p style="color: var(--white-dim); line-height: 1.9; margin-bottom: 20px;">
        Só recolhemos dados quando preenches um dos nossos formulários, de forma voluntária:
      </p>
      <ul style="color: var(--white-dim); line-height: 1.9; margin: 0 0 24px 24px;">
        <li><strong>Formulário de Contacto:</strong> nome, email, telefone (opcional) e a mensagem que escreves.</li>
        <li><strong>Formulário de Candidatura (Trabalha Connosco):</strong> nome, email, telefone, área de interesse, mensagem e, se optares por enviar, o teu currículo (CV).</li>
      </ul>
      <p style="color: var(--white-dim); line-height: 1.9; margin-bottom: 24px;">
        Não recolhemos dados de pagamento, nem pedimos informações sensíveis (saúde, religião, etc.) em nenhum formulário do site.
      </p>

      <h2 class="faq-categoria">2. Para que usamos estes dados</h2>
      <ul style="color: var(--white-dim); line-height: 1.9; margin: 0 0 24px 24px;">
        <li>Responder aos teus pedidos de contacto ou orçamento;</li>
        <li>Avaliar candidaturas enviadas através da página Trabalha Connosco;</li>
        <li>Entrar em contacto contigo relativamente a um serviço que solicitaste.</li>
      </ul>
      <p style="color: var(--white-dim); line-height: 1.9; margin-bottom: 24px;">
        Não usamos os teus dados para fins de marketing sem o teu consentimento, nem os vendemos ou partilhamos com terceiros.
      </p>

      <h2 class="faq-categoria">3. Onde ficam guardados</h2>
      <p style="color: var(--white-dim); line-height: 1.9; margin-bottom: 24px;">
        Os dados são guardados numa base de dados própria da Bitline Angola, protegida por password e acessível apenas
        à nossa equipa autorizada. Não usamos serviços externos de terceiros para armazenar estes dados.
      </p>

      <h2 class="faq-categoria">4. Cookies</h2>
      <p style="color: var(--white-dim); line-height: 1.9; margin-bottom: 24px;">
        Este site utiliza apenas um cookie técnico de sessão, necessário para o funcionamento normal do site
        (por exemplo, para os formulários funcionarem corretamente). Não utilizamos cookies de publicidade
        nem de rastreamento de terceiros.
      </p>

      <h2 class="faq-categoria">5. Os teus direitos</h2>
      <p style="color: var(--white-dim); line-height: 1.9; margin-bottom: 24px;">
        Podes, a qualquer momento, pedir-nos para veres, corrigires ou eliminares os dados que nos forneceste.
        Para isso, basta contactar-nos através de qualquer um dos canais abaixo.
      </p>

      <h2 class="faq-categoria">6. Contacto</h2>
      <p style="color: var(--white-dim); line-height: 1.9;">
        Para qualquer questão sobre esta política ou sobre os teus dados, contacta-nos:
      </p>
      <ul style="color: var(--white-dim); line-height: 1.9; margin: 12px 0 0 24px;">
        <li>Email: <?= CONTACTO_EMAIL ?></li>
        <li>Unitel: <?= formatarTelefone(CONTACTO_UNITEL) ?></li>
        <li>Africell: <?= formatarTelefone(CONTACTO_AFRICELL) ?></li>
      </ul>

    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
