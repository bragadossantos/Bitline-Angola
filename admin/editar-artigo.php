<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();
$id  = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM artigos WHERE id = :id');
$stmt->execute([':id' => $id]);
$artigo = $stmt->fetch();

if (!$artigo) {
    $_SESSION['admin_erro'] = 'Artigo não encontrado.';
    header('Location: artigos.php');
    exit;
}

$tituloPagina     = 'Editar Artigo';
$paginaAdminAtiva = 'artigos';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/atualizar_artigo.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
  <input type="hidden" name="id" value="<?= (int) $artigo['id'] ?>">

  <div class="form-group">
    <label for="titulo">Título *</label>
    <input type="text" id="titulo" name="titulo" value="<?= limpar($artigo['titulo']) ?>" required>
  </div>

  <div class="form-group">
    <label for="slug">Slug (URL)</label>
    <input type="text" id="slug" name="slug" value="<?= limpar($artigo['slug']) ?>">
  </div>

  <div class="form-group">
    <label for="resumo">Resumo *</label>
    <textarea id="resumo" name="resumo" rows="2" maxlength="300" required><?= limpar($artigo['resumo']) ?></textarea>
  </div>

  <div class="form-group">
    <label for="conteudo">Conteúdo *</label>
    <textarea id="conteudo" name="conteudo" rows="14" required><?= htmlspecialchars($artigo['conteudo'], ENT_QUOTES, 'UTF-8') ?></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="imagem">Substituir imagem de capa</label>
      <input type="file" id="imagem" name="imagem" accept="image/*">
      <?php if (!empty($artigo['imagem'])): ?>
        <div class="admin-imagem-atual">
          <img src="../<?= UPLOAD_URL . limpar($artigo['imagem']) ?>" alt="">
          <label><input type="checkbox" name="remover_imagem" value="1"> Remover imagem atual</label>
        </div>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label for="status">Estado</label>
      <select id="status" name="status">
        <option value="rascunho" <?= $artigo['status'] === 'rascunho' ? 'selected' : '' ?>>Rascunho</option>
        <option value="publicado" <?= $artigo['status'] === 'publicado' ? 'selected' : '' ?>>Publicado</option>
      </select>
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="destaque" value="1" <?= $artigo['destaque'] ? 'checked' : '' ?>> Marcar como artigo em destaque</label>
  </div>

  <div class="admin-form-acoes">
    <a href="artigos.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Alterações</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
