<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$pdo = getConnection();

$projetos = $pdo->query(
    'SELECT * FROM portfolio ORDER BY destaque DESC, ordem ASC, criado_em DESC'
)->fetchAll();

$depoimentos = $pdo->query(
    'SELECT * FROM depoimentos WHERE publicado = 1 ORDER BY ordem ASC, criado_em DESC'
)->fetchAll();

$tituloPagina    = 'Portefólio';
$descricaoPagina = 'Conhece alguns dos projetos que a Bitline Angola já desenvolveu, e o que os nossos clientes dizem sobre trabalhar connosco.';
$paginaAtiva     = 'portfolio';
require __DIR__ . '/includes/header.php';
?>

  <section id="blog-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">O Nosso Trabalho</span>
      <h1 class="section-title">Portefólio</h1>
      <p class="section-sub">Projetos reais, para empresas reais. Cada solução é pensada para o problema específico do cliente.</p>
    </div>
  </section>

  <section id="portfolio-lista">
    <?php if (empty($projetos)): ?>
      <p class="blog-empty reveal">Estamos a preparar o nosso portefólio. Volta em breve para veres os projetos que já entregámos!</p>
    <?php else: ?>
      <div class="portfolio-grid reveal">
        <?php foreach ($projetos as $p): ?>
          <div class="portfolio-card <?= $p['destaque'] ? 'portfolio-destaque' : '' ?>">
            <div class="portfolio-card-img">
              <?php if (!empty($p['imagem'])): ?>
                <img src="<?= UPLOAD_URL_PORTFOLIO . limpar($p['imagem']) ?>" alt="<?= limpar($p['titulo']) ?>" loading="lazy">
              <?php else: ?>
                <div class="portfolio-card-placeholder"><i class="fa-solid fa-diagram-project"></i></div>
              <?php endif; ?>
              <?php if (!empty($p['categoria'])): ?>
                <span class="portfolio-tag"><?= limpar($p['categoria']) ?></span>
              <?php endif; ?>
            </div>
            <div class="portfolio-card-body">
              <h3><?= limpar($p['titulo']) ?></h3>
              <?php if (!empty($p['cliente'])): ?>
                <span class="portfolio-cliente"><i class="fa-solid fa-building"></i> <?= limpar($p['cliente']) ?></span>
              <?php endif; ?>
              <p><?= limpar($p['descricao']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <?php if (!empty($depoimentos)): ?>
  <section id="depoimentos">
    <div class="section-header reveal">
      <span>O QUE DIZEM DE NÓS</span>
      <h2>Depoimentos de Clientes</h2>
      <p>A confiança de quem já trabalhou connosco é a nossa melhor referência.</p>
    </div>

    <div class="depoimentos-grid reveal">
      <?php foreach ($depoimentos as $d): ?>
        <div class="depoimento-card">
          <div class="depoimento-estrelas">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <i class="fa-solid fa-star <?= $i <= (int) $d['avaliacao'] ? 'ativa' : '' ?>"></i>
            <?php endfor; ?>
          </div>
          <p class="depoimento-texto">"<?= limpar($d['texto']) ?>"</p>
          <div class="depoimento-autor">
            <?php if (!empty($d['foto'])): ?>
              <img src="<?= UPLOAD_URL_DEPOIMENTOS . limpar($d['foto']) ?>" alt="<?= limpar($d['nome']) ?>" class="depoimento-foto">
            <?php else: ?>
              <div class="depoimento-foto depoimento-foto-inicial"><?= mb_strtoupper(mb_substr($d['nome'], 0, 1)) ?></div>
            <?php endif; ?>
            <div>
              <strong><?= limpar($d['nome']) ?></strong>
              <?php if (!empty($d['empresa'])): ?><span><?= limpar($d['empresa']) ?></span><?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <section id="portfolio-cta">
    <div class="contact-home-box reveal" style="grid-template-columns: 1fr; text-align: center;">
      <div class="contact-home-text" style="max-width: 640px; margin: 0 auto;">
        <span class="section-subtitle">O TEU PROJETO PODE SER O PRÓXIMO</span>
        <h2>Vamos construir algo juntos?</h2>
        <p>Fala connosco e conta-nos o que precisas. A primeira consultoria é gratuita.</p>
        <a href="contacto.php" class="btn-primary" style="margin-top: 10px;">Falar com a Bitline <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
