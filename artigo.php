<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$pdo  = getConnection();
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if ($slug === '') {
    header('Location: blog.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT a.*, ad.nome AS autor_nome
     FROM artigos a
     LEFT JOIN admins ad ON ad.id = a.autor_id
     WHERE a.slug = :slug AND a.status = \'publicado\'
     LIMIT 1'
);
$stmt->execute([':slug' => $slug]);
$artigo = $stmt->fetch();

if (!$artigo) {
    http_response_code(404);
    $tituloPagina = 'Artigo não encontrado';
    $paginaAtiva  = 'blog';
    require __DIR__ . '/includes/header.php';
    echo '<section id="blog-404"><div class="reveal"><h2 class="section-title">Artigo não encontrado</h2>
          <p class="section-sub">O artigo que procuras pode ter sido removido ou o link está incorreto.</p>
          <a href="blog.php" class="btn-primary" style="margin-top:24px;">Voltar ao Blog</a></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

// Contabiliza visualização (best-effort, não é crítico se falhar)
try {
    $pdo->prepare('UPDATE artigos SET visualizacoes = visualizacoes + 1 WHERE id = :id')
        ->execute([':id' => $artigo['id']]);
} catch (Throwable $e) {
    // silencioso
}

// Artigos relacionados (últimos 3, excluindo o atual)
$stmtRel = $pdo->prepare(
    'SELECT titulo, slug, imagem, criado_em
     FROM artigos
     WHERE status = \'publicado\' AND id != :id
     ORDER BY criado_em DESC
     LIMIT 3'
);
$stmtRel->execute([':id' => $artigo['id']]);
$relacionados = $stmtRel->fetchAll();

$tituloPagina    = $artigo['titulo'];
$descricaoPagina = $artigo['resumo'];
$paginaAtiva     = 'blog';
require __DIR__ . '/includes/header.php';
?>

  <section id="artigo-single">
    <div class="artigo-container reveal">
      <div class="artigo-breadcrumb">
        <a href="index.php">Início</a> <i class="fa-solid fa-chevron-right"></i>
        <a href="blog.php">Blog</a> <i class="fa-solid fa-chevron-right"></i>
        <span><?= limpar($artigo['titulo']) ?></span>
      </div>

      <span class="section-label">Artigo</span>
      <h1 class="artigo-titulo"><?= limpar($artigo['titulo']) ?></h1>

      <div class="artigo-meta">
        <span><i class="fa-solid fa-user"></i> <?= limpar($artigo['autor_nome'] ?? 'Equipa Bitline') ?></span>
        <span><i class="fa-solid fa-calendar"></i> <?= formatarData($artigo['criado_em']) ?></span>
        <span><i class="fa-solid fa-clock"></i> <?= tempoLeitura($artigo['conteudo']) ?> min de leitura</span>
        <span><i class="fa-solid fa-eye"></i> <?= (int) $artigo['visualizacoes'] ?> visualizações</span>
      </div>

      <?php if (!empty($artigo['imagem'])): ?>
        <div class="artigo-imagem">
          <img src="<?= UPLOAD_URL . limpar($artigo['imagem']) ?>" alt="<?= limpar($artigo['titulo']) ?>">
        </div>
      <?php endif; ?>

      <div class="artigo-conteudo">
        <?= $artigo['conteudo'] // conteúdo HTML gerido pelo painel administrativo ?>
      </div>

      <div class="artigo-partilhar">
        <span>Partilhar:</span>
        <a href="https://wa.me/?text=<?= urlencode($artigo['titulo'] . ' - ' . SITE_URL . '/artigo.php?slug=' . $artigo['slug']) ?>" target="_blank" aria-label="Partilhar no WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(SITE_URL . '/artigo.php?slug=' . $artigo['slug']) ?>" target="_blank" aria-label="Partilhar no Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(SITE_URL . '/artigo.php?slug=' . $artigo['slug']) ?>&text=<?= urlencode($artigo['titulo']) ?>" target="_blank" aria-label="Partilhar no X"><i class="fa-brands fa-x-twitter"></i></a>
      </div>
    </div>

    <?php if (!empty($relacionados)): ?>
      <div class="artigo-relacionados reveal">
        <h3>Artigos relacionados</h3>
        <div class="blog-grid">
          <?php foreach ($relacionados as $rel): ?>
            <a href="artigo.php?slug=<?= urlencode($rel['slug']) ?>" class="blog-card">
              <div class="blog-card-img">
                <?php if (!empty($rel['imagem'])): ?>
                  <img src="<?= UPLOAD_URL . limpar($rel['imagem']) ?>" alt="<?= limpar($rel['titulo']) ?>" loading="lazy">
                <?php else: ?>
                  <div class="blog-card-placeholder"><i class="fa-solid fa-newspaper"></i></div>
                <?php endif; ?>
              </div>
              <div class="blog-card-body">
                <span class="blog-card-date"><?= formatarData($rel['criado_em']) ?></span>
                <h3><?= limpar($rel['titulo']) ?></h3>
                <span class="blog-card-link">Ler artigo <i class="fa-solid fa-arrow-right"></i></span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
