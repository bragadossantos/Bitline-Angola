<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();
$id  = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM faq WHERE id = :id');
$stmt->execute([':id' => $id]);
$faq = $stmt->fetch();

if (!$faq) {
    $_SESSION['admin_erro'] = 'Pergunta não encontrada.';
    header('Location: faq.php');
    exit;
}

$tituloPagina     = 'Editar Pergunta FAQ';
$paginaAdminAtiva = 'faq';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/atualizar_faq.php" method="POST">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
  <input type="hidden" name="id" value="<?= (int) $faq['id'] ?>">

  <div class="form-group">
    <label for="pergunta">Pergunta *</label>
    <input type="text" id="pergunta" name="pergunta" value="<?= limpar($faq['pergunta']) ?>" required>
  </div>

  <div class="form-group">
    <label for="resposta">Resposta *</label>
    <textarea id="resposta" name="resposta" rows="4" required><?= limpar($faq['resposta']) ?></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="categoria">Categoria</label>
      <input type="text" id="categoria" name="categoria" value="<?= limpar($faq['categoria'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="ordem">Ordem de exibição</label>
      <input type="number" id="ordem" name="ordem" value="<?= (int) $faq['ordem'] ?>">
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="publicado" value="1" <?= $faq['publicado'] ? 'checked' : '' ?>> Publicado no site</label>
  </div>

  <div class="admin-form-acoes">
    <a href="faq.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Alterações</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
