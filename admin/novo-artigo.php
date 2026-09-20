<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$tituloPagina     = 'Novo Artigo';
$paginaAdminAtiva = 'novo-artigo';
require __DIR__ . '/includes/admin-header.php';
?>

<form class="admin-panel admin-form" action="../actions/guardar_artigo.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
  <div class="form-group">
    <label for="titulo">Título *</label>
    <input type="text" id="titulo" name="titulo" placeholder="Título do artigo" required>
  </div>

  <div class="form-group">
    <label for="slug">Slug (URL) — opcional, é gerado automaticamente a partir do título</label>
    <input type="text" id="slug" name="slug" placeholder="ex: novidades-ciberseguranca-2026">
  </div>

  <div class="form-group">
    <label for="resumo">Resumo (aparece nos cartões e listagem do blog) *</label>
    <textarea id="resumo" name="resumo" rows="2" maxlength="300" placeholder="Um resumo curto e apelativo do artigo..." required></textarea>
  </div>

  <div class="form-group">
    <label for="conteudo">Conteúdo * (podes usar HTML simples: &lt;p&gt;, &lt;h2&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;img&gt;...)</label>
    <textarea id="conteudo" name="conteudo" rows="14" placeholder="Escreve aqui o conteúdo completo do artigo..." required></textarea>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label for="imagem">Imagem de capa (JPG, PNG, WEBP — até 4MB)</label>
      <input type="file" id="imagem" name="imagem" accept="image/*">
    </div>
    <div class="form-group">
      <label for="status">Estado</label>
      <select id="status" name="status">
        <option value="rascunho">Rascunho</option>
        <option value="publicado">Publicado</option>
      </select>
    </div>
  </div>

  <div class="form-group form-checkbox">
    <label><input type="checkbox" name="destaque" value="1"> Marcar como artigo em destaque</label>
  </div>

  <div class="admin-form-acoes">
    <a href="artigos.php" class="admin-btn-secundario">Cancelar</a>
    <button type="submit" class="admin-btn-primario"><i class="fa-solid fa-floppy-disk"></i> Guardar Artigo</button>
  </div>
</form>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
