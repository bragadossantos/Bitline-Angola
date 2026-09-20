<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();

$filtro = $_GET['status'] ?? 'todos';
$sql = 'SELECT a.*, ad.nome AS autor_nome FROM artigos a LEFT JOIN admins ad ON ad.id = a.autor_id';
if (in_array($filtro, ['rascunho', 'publicado'], true)) {
    $sql .= ' WHERE a.status = :status';
}
$sql .= ' ORDER BY a.criado_em DESC';

$stmt = $pdo->prepare($sql);
if (in_array($filtro, ['rascunho', 'publicado'], true)) {
    $stmt->bindValue(':status', $filtro);
}
$stmt->execute();
$artigos = $stmt->fetchAll();

$tituloPagina     = 'Artigos';
$paginaAdminAtiva = 'artigos';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-toolbar">
  <div class="admin-filtros">
    <a href="artigos.php" class="<?= $filtro === 'todos' ? 'ativo' : '' ?>">Todos</a>
    <a href="artigos.php?status=publicado" class="<?= $filtro === 'publicado' ? 'ativo' : '' ?>">Publicados</a>
    <a href="artigos.php?status=rascunho" class="<?= $filtro === 'rascunho' ? 'ativo' : '' ?>">Rascunhos</a>
  </div>
  <a href="novo-artigo.php" class="admin-btn-primario"><i class="fa-solid fa-plus"></i> Novo Artigo</a>
</div>

<div class="admin-panel">
  <?php if (empty($artigos)): ?>
    <p class="admin-vazio">Nenhum artigo encontrado. <a href="novo-artigo.php">Criar novo artigo</a>.</p>
  <?php else: ?>
    <table class="admin-tabela admin-tabela-completa">
      <thead>
        <tr>
          <th>Imagem</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Estado</th>
          <th>Vistas</th>
          <th>Criado em</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($artigos as $a): ?>
          <tr>
            <td>
              <?php if (!empty($a['imagem'])): ?>
                <img class="admin-thumb" src="../<?= UPLOAD_URL . limpar($a['imagem']) ?>" alt="">
              <?php else: ?>
                <div class="admin-thumb admin-thumb-vazio"><i class="fa-solid fa-image"></i></div>
              <?php endif; ?>
            </td>
            <td>
              <strong><?= limpar($a['titulo']) ?></strong>
              <?php if ($a['destaque']): ?><span class="badge badge-azul">Destaque</span><?php endif; ?><br>
              <a href="../artigo.php?slug=<?= urlencode($a['slug']) ?>" target="_blank" class="admin-link-ver">Ver no site <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
            </td>
            <td><?= limpar($a['autor_nome'] ?? '—') ?></td>
            <td><span class="badge badge-<?= $a['status'] === 'publicado' ? 'verde' : 'amarelo' ?>"><?= $a['status'] === 'publicado' ? 'Publicado' : 'Rascunho' ?></span></td>
            <td><?= (int) $a['visualizacoes'] ?></td>
            <td><?= formatarData($a['criado_em']) ?></td>
            <td class="admin-acoes">
              <a href="editar-artigo.php?id=<?= $a['id'] ?>" title="Editar"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="../actions/eliminar_artigo.php" class="admin-acoes-form" onsubmit="return confirm('Tens a certeza que queres eliminar este artigo? Esta ação não pode ser desfeita.');">
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= $a['id'] ?>">
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
