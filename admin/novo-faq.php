<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$tituloPagina     = 'Nova Pergunta FAQ';
$paginaAdminAtiva = 'faq';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/guardar_faq.php" method="POST">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">

  <div class="form-group">
    <label for="pergunta">Pergunta *</label>
    <input type="text" id="pergunta" name="pergunta" placeholder="ex: Quanto tempo demora um projeto?" required>
  </div>

  <div class="form-group">
    <label for="resposta">Resposta *</label>
    <textarea id="resposta" name="resposta" rows="4" required></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="categoria">Categoria (opcional, agrupa as perguntas)</label>
      <input type="text" id="categoria" name="categoria" placeholder="ex: Geral, Suporte, Orçamentos...">
    </div>
    <div class="form-group">
      <label for="ordem">Ordem de exibição</label>
      <input type="number" id="ordem" name="ordem" value="0">
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="publicado" value="1" checked> Publicar imediatamente no site</label>
  </div>

  <div class="admin-form-acoes">
    <a href="faq.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Pergunta</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
