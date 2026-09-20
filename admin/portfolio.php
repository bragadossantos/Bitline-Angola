<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();
$projetos = $pdo->query('SELECT * FROM portfolio ORDER BY destaque DESC, ordem ASC, criado_em DESC')->fetchAll();

$tituloPagina     = 'Portefólio';
$paginaAdminAtiva = 'portfolio';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-toolbar">
  <div></div>
  <a href="novo-projeto.php" class="admin-btn-primario"><i class="fa-solid fa-plus"></i> Novo Projeto</a>
</div>

<div class="admin-panel">
  <?php if (empty($projetos)): ?>
    <p class="admin-vazio">Ainda não há projetos no portefólio. <a href="novo-projeto.php">Adicionar o primeiro</a>.</p>
  <?php else: ?>
    <table class="admin-tabela admin-tabela-completa">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Título</th>
          <th>Categoria</th>
          <th>Cliente</th>
          <th>Criado em</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projetos as $p): ?>
          <tr>
            <td>
              <?php if (!empty($p['imagem'])): ?>
                <img class="admin-thumb" src="../<?= UPLOAD_URL_PORTFOLIO . limpar($p['imagem']) ?>" alt="">
              <?php else: ?>
                <div class="admin-thumb admin-thumb-vazio"><i class="fa-solid fa-image"></i></div>
              <?php endif; ?>
            </td>
            <td>
              <strong><?= limpar($p['titulo']) ?></strong>
              <?php if ($p['destaque']): ?><span class="badge badge-azul">Destaque</span><?php endif; ?>
            </td>
            <td><?= limpar($p['categoria'] ?: '—') ?></td>
            <td><?= limpar($p['cliente'] ?: '—') ?></td>
            <td><?= formatarData($p['criado_em']) ?></td>
            <td class="admin-acoes">
              <a href="editar-projeto.php?id=<?= $p['id'] ?>" title="Editar"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="../actions/eliminar_projeto.php" class="admin-acoes-form" onsubmit="return confirm('Eliminar este projeto?');">
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
