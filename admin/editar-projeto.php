<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();
$id  = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM portfolio WHERE id = :id');
$stmt->execute([':id' => $id]);
$projeto = $stmt->fetch();

if (!$projeto) {
    $_SESSION['admin_erro'] = 'Projeto não encontrado.';
    header('Location: portfolio.php');
    exit;
}

$tituloPagina     = 'Editar Projeto';
$paginaAdminAtiva = 'portfolio';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/atualizar_projeto.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
  <input type="hidden" name="id" value="<?= (int) $projeto['id'] ?>">

  <div class="form-group">
    <label for="titulo">Título do projeto *</label>
    <input type="text" id="titulo" name="titulo" value="<?= limpar($projeto['titulo']) ?>" required>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="categoria">Categoria</label>
      <input type="text" id="categoria" name="categoria" value="<?= limpar($projeto['categoria'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label for="cliente">Cliente (opcional)</label>
      <input type="text" id="cliente" name="cliente" value="<?= limpar($projeto['cliente'] ?? '') ?>">
    </div>
  </div>

  <div class="form-group">
    <label for="descricao">Descrição *</label>
    <textarea id="descricao" name="descricao" rows="5" required><?= limpar($projeto['descricao']) ?></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="imagem">Substituir imagem</label>
      <input type="file" id="imagem" name="imagem" accept="image/*">
      <?php if (!empty($projeto['imagem'])): ?>
        <div class="admin-imagem-atual">
          <img src="../<?= UPLOAD_URL_PORTFOLIO . limpar($projeto['imagem']) ?>" alt="">
          <label><input type="checkbox" name="remover_imagem" value="1"> Remover imagem atual</label>
        </div>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <label for="ordem">Ordem de exibição</label>
      <input type="number" id="ordem" name="ordem" value="<?= (int) $projeto['ordem'] ?>">
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="destaque" value="1" <?= $projeto['destaque'] ? 'checked' : '' ?>> Marcar como projeto em destaque</label>
  </div>

  <div class="admin-form-acoes">
    <a href="portfolio.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Alterações</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
