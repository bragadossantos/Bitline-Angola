<?php
/**
 * Secção "Blog em destaque" — puxa os últimos 3 artigos publicados.
 */
$pdoBlogHome = getConnection();
$stmtBlogHome = $pdoBlogHome->prepare(
    'SELECT id, titulo, slug, resumo, imagem, criado_em
     FROM artigos
     WHERE status = \'publicado\'
     ORDER BY criado_em DESC
     LIMIT 3'
);
$stmtBlogHome->execute();
$artigosDestaqueHome = $stmtBlogHome->fetchAll();
?>

<!-- =====================================================
                    BLOG EM DESTAQUE
====================================================== -->

<section class="blog-home">

    <div class="container">

        <div class="section-header reveal">
            <span>O NOSSO BLOG</span>
            <h2>Notícias, ideias e conhecimento técnico</h2>
            <p>Artigos e novidades partilhados pela equipa Bitline Angola.</p>
        </div>

        <?php if (empty($artigosDestaqueHome)): ?>
            <p class="blog-empty reveal">Ainda não há artigos publicados. Volta em breve!</p>
        <?php else: ?>
            <div class="blog-grid reveal">
                <?php foreach ($artigosDestaqueHome as $artigo): ?>
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
            <div class="blog-cta reveal">
                <a href="blog.php" class="btn-secondary">Ver todos os artigos</a>
            </div>
        <?php endif; ?>

    </div>

</section>
