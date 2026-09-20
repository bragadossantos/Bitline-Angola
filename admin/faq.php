<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();
$faqs = $pdo->query('SELECT * FROM faq ORDER BY categoria ASC, ordem ASC, id ASC')->fetchAll();

$tituloPagina     = 'FAQ';
$paginaAdminAtiva = 'faq';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-toolbar">
  <div></div>
  <a href="novo-faq.php" class="admin-btn-primario"><i class="fa-solid fa-plus"></i> Nova Pergunta</a>
</div>

<div class="admin-panel">
  <?php if (empty($faqs)): ?>
    <p class="admin-vazio">Ainda não há perguntas frequentes. <a href="novo-faq.php">Adicionar a primeira</a>.</p>
  <?php else: ?>
    <table class="admin-tabela admin-tabela-completa">
      <thead>
        <tr>
          <th>Pergunta</th>
          <th>Categoria</th>
          <th>Ordem</th>
          <th>Estado</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($faqs as $f): ?>
          <tr>
            <td><strong><?= limpar($f['pergunta']) ?></strong></td>
            <td><?= limpar($f['categoria'] ?: 'Geral') ?></td>
            <td><?= (int) $f['ordem'] ?></td>
            <td><span class="badge badge-<?= $f['publicado'] ? 'verde' : 'amarelo' ?>"><?= $f['publicado'] ? 'Publicado' : 'Oculto' ?></span></td>
            <td class="admin-acoes">
              <a href="editar-faq.php?id=<?= $f['id'] ?>" title="Editar"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="../actions/eliminar_faq.php" class="admin-acoes-form" onsubmit="return confirm('Eliminar esta pergunta?');">
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= $f['id'] ?>">
                <button type="submit" class="admin-eliminar" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
