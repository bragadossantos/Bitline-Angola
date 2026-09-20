<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$tituloPagina     = 'Novo Depoimento';
$paginaAdminAtiva = 'depoimentos';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/guardar_depoimento.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">

  <div class="form-row">
    <div class="form-group">
      <label for="nome">Nome do cliente *</label>
      <input type="text" id="nome" name="nome" placeholder="Nome completo" required>
    </div>
    <div class="form-group">
      <label for="empresa">Empresa (opcional)</label>
      <input type="text" id="empresa" name="empresa" placeholder="Nome da empresa">
    </div>
  </div>

  <div class="form-group">
    <label for="texto">Depoimento *</label>
    <textarea id="texto" name="texto" rows="4" placeholder="O que o cliente disse sobre o vosso trabalho..." required></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="avaliacao">Avaliação (1 a 5 estrelas)</label>
      <select id="avaliacao" name="avaliacao">
        <option value="5" selected>★★★★★ (5)</option>
        <option value="4">★★★★☆ (4)</option>
        <option value="3">★★★☆☆ (3)</option>
        <option value="2">★★☆☆☆ (2)</option>
        <option value="1">★☆☆☆☆ (1)</option>
      </select>
    </div>
    <div class="form-group">
      <label for="foto">Foto do cliente (opcional)</label>
      <input type="file" id="foto" name="foto" accept="image/*">
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="publicado" value="1" checked> Publicar imediatamente no site</label>
  </div>

  <div class="admin-form-acoes">
    <a href="depoimentos.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Depoimento</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
