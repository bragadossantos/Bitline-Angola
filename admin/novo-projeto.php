<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$tituloPagina     = 'Novo Projeto';
$paginaAdminAtiva = 'portfolio';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/guardar_projeto.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">

  <div class="form-group">
    <label for="titulo">Título do projeto *</label>
    <input type="text" id="titulo" name="titulo" placeholder="ex: Rede empresarial para XYZ Lda." required>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="categoria">Categoria</label>
      <input type="text" id="categoria" name="categoria" placeholder="ex: Redes, Cibersegurança, Software...">
    </div>
    <div class="form-group">
      <label for="cliente">Cliente (opcional)</label>
      <input type="text" id="cliente" name="cliente" placeholder="Nome da empresa cliente">
    </div>
  </div>

  <div class="form-group">
    <label for="descricao">Descrição *</label>
    <textarea id="descricao" name="descricao" rows="5" placeholder="O que foi feito, qual o problema resolvido, resultado obtido..." required></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="imagem">Imagem do projeto</label>
      <input type="file" id="imagem" name="imagem" accept="image/*">
    </div>
    <div class="form-group">
      <label for="ordem">Ordem de exibição</label>
      <input type="number" id="ordem" name="ordem" value="0">
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="destaque" value="1"> Marcar como projeto em destaque</label>
  </div>

  <div class="admin-form-acoes">
    <a href="portfolio.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Projeto</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
