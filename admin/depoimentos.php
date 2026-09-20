<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();
$depoimentos = $pdo->query('SELECT * FROM depoimentos ORDER BY ordem ASC, criado_em DESC')->fetchAll();

$tituloPagina     = 'Depoimentos';
$paginaAdminAtiva = 'depoimentos';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-toolbar">
  <div></div>
  <a href="novo-depoimento.php" class="admin-btn-primario"><i class="fa-solid fa-plus"></i> Novo Depoimento</a>
</div>

<div class="admin-panel">
  <?php if (empty($depoimentos)): ?>
    <p class="admin-vazio">Ainda não há depoimentos. <a href="novo-depoimento.php">Adicionar o primeiro</a>.</p>
  <?php else: ?>
    <table class="admin-tabela admin-tabela-completa">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Empresa</th>
          <th>Avaliação</th>
          <th>Estado</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($depoimentos as $d): ?>
          <tr>
            <td><strong><?= limpar($d['nome']) ?></strong></td>
            <td><?= limpar($d['empresa'] ?: '—') ?></td>
            <td><?= str_repeat('★', (int) $d['avaliacao']) . str_repeat('☆', 5 - (int) $d['avaliacao']) ?></td>
            <td><span class="badge badge-<?= $d['publicado'] ? 'verde' : 'amarelo' ?>"><?= $d['publicado'] ? 'Publicado' : 'Oculto' ?></span></td>
            <td class="admin-acoes">
              <a href="editar-depoimento.php?id=<?= $d['id'] ?>" title="Editar"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="../actions/eliminar_depoimento.php" class="admin-acoes-form" onsubmit="return confirm('Eliminar este depoimento?');">
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= $d['id'] ?>">
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
