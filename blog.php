<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$pdo = getConnection();

// --- Pesquisa e paginação ---
$termo         = isset($_GET['q']) ? trim($_GET['q']) : '';
$paginaAtual   = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$porPagina     = 6;
$offset        = ($paginaAtual - 1) * $porPagina;

$condicao = "WHERE status = 'publicado'";
$parametros = [];
if ($termo !== '') {
    $condicao .= ' AND (titulo LIKE :termo OR resumo LIKE :termo OR conteudo LIKE :termo)';
    $parametros[':termo'] = '%' . $termo . '%';
}

// Total para paginação
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM artigos $condicao");
$stmtTotal->execute($parametros);
$totalArtigos = (int) $stmtTotal->fetchColumn();
$totalPaginas = max(1, (int) ceil($totalArtigos / $porPagina));

$sql = "SELECT id, titulo, slug, resumo, imagem, criado_em, visualizacoes
        FROM artigos
        $condicao
        ORDER BY criado_em DESC
        LIMIT :limite OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($parametros as $chave => $valor) {
    $stmt->bindValue($chave, $valor);
}
$stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$artigos = $stmt->fetchAll();

$tituloPagina    = 'Blog';
$descricaoPagina = 'Notícias, artigos técnicos e novidades da Bitline Angola sobre redes, cibersegurança, cloud e tecnologia.';
$paginaAtiva     = 'blog';
require __DIR__ . '/includes/header.php';
?>

  <section id="blog-hero">
    <div class="page-hero-content reveal">
      <span class="section-label">Blog Bitline Angola</span>
      <h1 class="section-title">Notícias, ideias e conhecimento técnico</h1>
      <p class="section-sub">Tudo o que precisas de saber sobre tecnologia, segurança e infraestrutura em Angola.</p>

      <form class="blog-search" action="blog.php" method="GET">
        <input type="text" name="q" placeholder="Pesquisar artigos..." value="<?= limpar($termo) ?>">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </form>
    </div>
  </section>

  <section id="blog-lista">
    <?php if (empty($artigos)): ?>
      <p class="blog-empty reveal">
        <?= $termo !== '' ? 'Nenhum artigo encontrado para "' . limpar($termo) . '".' : 'Ainda não há artigos publicados. Volta em breve!' ?>
      </p>
    <?php else: ?>
      <div class="blog-grid reveal">
        <?php foreach ($artigos as $artigo): ?>
          <a href="artigo.php?slug=<?= urlencode($artigo['slug']) ?>" class="blog-card">
            <div class="blog-card-img">
              <?php if (!empty($artigo['imagem'])): ?>
                <img src="<?= UPLOAD_URL . limpar($artigo['imagem']) ?>" alt="<?= limpar($artigo['titulo']) ?>" loading="lazy">
              <?php else: ?>
                <div class="blog-card-placeholder"><i class="fa-solid fa-newspaper"></i></div>
              <?php endif; ?>
            </div>
            <div class="blog-card-body">
              <span class="blog-card-date"><?= formatarData($artigo['criado_em']) ?></span>
              <h3><?= limpar($artigo['titulo']) ?></h3>
              <p><?= limpar($artigo['resumo']) ?></p>
              <span class="blog-card-link">Ler artigo <i class="fa-solid fa-arrow-right"></i></span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($totalPaginas > 1): ?>
        <div class="blog-paginacao reveal">
          <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
            <a href="blog.php?pagina=<?= $p ?><?= $termo !== '' ? '&q=' . urlencode($termo) : '' ?>"
               class="<?= $p === $paginaAtual ? 'ativo' : '' ?>"><?= $p ?></a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
