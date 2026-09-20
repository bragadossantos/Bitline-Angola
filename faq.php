<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$pdo = getConnection();
$faqs = $pdo->query(
    'SELECT * FROM faq WHERE publicado = 1 ORDER BY categoria ASC, ordem ASC, id ASC'
)->fetchAll();

// Agrupa por categoria (usa "Geral" quando não definida)
$grupos = [];
foreach ($faqs as $f) {
    $cat = $f['categoria'] ?: 'Geral';
    $grupos[$cat][] = $f;
}

$tituloPagina    = 'Perguntas Frequentes';
$descricaoPagina = 'Respostas às perguntas mais comuns sobre os serviços da Bitline Angola.';
$paginaAtiva     = 'faq';
require __DIR__ . '/includes/header.php';
?>

  <section id="blog-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">Dúvidas Comuns</span>
      <h1 class="section-title">Perguntas Frequentes</h1>
      <p class="section-sub">Não encontraste a tua resposta aqui? <a href="contacto.php" style="color: var(--blue-glow);">Fala connosco diretamente.</a></p>
    </div>
  </section>

  <section id="faq-lista">
    <?php if (empty($grupos)): ?>
      <p class="blog-empty reveal">Ainda não há perguntas frequentes publicadas.</p>
    <?php else: ?>
      <?php foreach ($grupos as $categoria => $itens): ?>
        <div class="faq-grupo reveal">
          <h2 class="faq-categoria"><?= limpar($categoria) ?></h2>
          <div class="faq-acordeao">
            <?php foreach ($itens as $f): ?>
              <div class="faq-item">
                <button class="faq-pergunta" type="button">
                  <span><?= limpar($f['pergunta']) ?></span>
                  <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-resposta">
                  <p><?= nl2br(limpar($f['resposta'])) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
