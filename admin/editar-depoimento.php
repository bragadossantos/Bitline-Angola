<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();
$id  = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM depoimentos WHERE id = :id');
$stmt->execute([':id' => $id]);
$dep = $stmt->fetch();

if (!$dep) {
    $_SESSION['admin_erro'] = 'Depoimento não encontrado.';
    header('Location: depoimentos.php');
    exit;
}

$tituloPagina     = 'Editar Depoimento';
$paginaAdminAtiva = 'depoimentos';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/atualizar_depoimento.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
  <input type="hidden" name="id" value="<?= (int) $dep['id'] ?>">

  <div class="form-row">
    <div class="form-group">
      <label for="nome">Nome do cliente *</label>
      <input type="text" id="nome" name="nome" value="<?= limpar($dep['nome']) ?>" required>
    </div>
    <div class="form-group">
      <label for="empresa">Empresa (opcional)</label>
      <input type="text" id="empresa" name="empresa" value="<?= limpar($dep['empresa'] ?? '') ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="texto">Depoimento *</label>
    <textarea id="texto" name="texto" rows="4" required><?= limpar($dep['texto']) ?></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="avaliacao">Avaliação</label>
      <select id="avaliacao" name="avaliacao">
        <?php for ($i = 5; $i >= 1; $i--): ?>
          <option value="<?= $i ?>" <?= (int) $dep['avaliacao'] === $i ? 'selected' : '' ?>><?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?> (<?= $i ?>)</option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="form-group">
      <label for="foto">Substituir foto</label>
      <input type="file" id="foto" name="foto" accept="image/*">
      <?php if (!empty($dep['foto'])): ?>
        <div class="admin-imagem-atual">
          <img src="../<?= UPLOAD_URL_DEPOIMENTOS . limpar($dep['foto']) ?>" alt="">
          <label><input type="checkbox" name="remover_foto" value="1"> Remover foto atual</label>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="publicado" value="1" <?= $dep['publicado'] ? 'checked' : '' ?>> Publicado no site</label>
  </div>

  <div class="admin-form-acoes">
    <a href="depoimentos.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Alterações</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
