<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();

$totalPublicados = (int) $pdo->query("SELECT COUNT(*) FROM artigos WHERE status = 'publicado'")->fetchColumn();
$totalRascunhos   = (int) $pdo->query("SELECT COUNT(*) FROM artigos WHERE status = 'rascunho'")->fetchColumn();
$totalVistas      = (int) $pdo->query('SELECT COALESCE(SUM(visualizacoes),0) FROM artigos')->fetchColumn();
$contactosNaoLidos = (int) $pdo->query('SELECT COUNT(*) FROM contactos WHERE lido = 0')->fetchColumn();

$ultimosArtigos = $pdo->query('SELECT id, titulo, status, criado_em FROM artigos ORDER BY criado_em DESC LIMIT 5')->fetchAll();
$ultimosContactos = $pdo->query('SELECT id, nome, email, assunto, criado_em, lido FROM contactos ORDER BY criado_em DESC LIMIT 5')->fetchAll();

$tituloPagina     = 'Dashboard';
$paginaAdminAtiva = 'dashboard';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-cards">
  <div class="admin-card">
    <div class="admin-card-icon" style="background:rgba(37,99,235,.15);color:var(--blue-glow);"><i class="fa-solid fa-newspaper"></i></div>
    <div><span class="admin-card-num"><?= $totalPublicados ?></span><span class="admin-card-label">Artigos Publicados</span></div>
  </div>
  <div class="admin-card">
    <div class="admin-card-icon" style="background:rgba(251,191,36,.15);color:#fbbf24;"><i class="fa-solid fa-pen"></i></div>
    <div><span class="admin-card-num"><?= $totalRascunhos ?></span><span class="admin-card-label">Rascunhos</span></div>
  </div>
  <div class="admin-card">
    <div class="admin-card-icon" style="background:rgba(74,222,128,.15);color:#4ade80;"><i class="fa-solid fa-eye"></i></div>
    <div><span class="admin-card-num"><?= $totalVistas ?></span><span class="admin-card-label">Visualizações Totais</span></div>
  </div>
  <div class="admin-card">
    <div class="admin-card-icon" style="background:rgba(248,113,113,.15);color:#f87171;"><i class="fa-solid fa-envelope"></i></div>
    <div><span class="admin-card-num"><?= $contactosNaoLidos ?></span><span class="admin-card-label">Contactos Não Lidos</span></div>
  </div>
</div>

<div class="admin-grid-2">
  <div class="admin-panel">
    <div class="admin-panel-header">
      <h3>Últimos Artigos</h3>
      <a href="artigos.php">Ver todos</a>
    </div>
    <?php if (empty($ultimosArtigos)): ?>
      <p class="admin-vazio">Ainda não criaste nenhum artigo. <a href="novo-artigo.php">Criar o primeiro</a>.</p>
    <?php else: ?>
      <table class="admin-tabela">
        <?php foreach ($ultimosArtigos as $a): ?>
          <tr>
            <td><?= limpar($a['titulo']) ?></td>
            <td><span class="badge badge-<?= $a['status'] === 'publicado' ? 'verde' : 'amarelo' ?>"><?= $a['status'] === 'publicado' ? 'Publicado' : 'Rascunho' ?></span></td>
            <td><?= formatarData($a['criado_em']) ?></td>
            <td><a href="editar-artigo.php?id=<?= $a['id'] ?>"><i class="fa-solid fa-pen"></i></a></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>

  <div class="admin-panel">
    <div class="admin-panel-header">
      <h3>Últimos Contactos</h3>
      <a href="contactos.php">Ver todos</a>
    </div>
    <?php if (empty($ultimosContactos)): ?>
      <p class="admin-vazio">Ainda não há mensagens de contacto.</p>
    <?php else: ?>
      <table class="admin-tabela">
        <?php foreach ($ultimosContactos as $c): ?>
          <tr>
            <td><?= limpar($c['nome']) ?><br><small><?= limpar($c['email']) ?></small></td>
            <td><?= limpar($c['assunto'] ?: 'Consultoria Geral') ?></td>
            <td><?= formatarData($c['criado_em']) ?></td>
            <td><?php if (!$c['lido']): ?><span class="badge badge-vermelho">Novo</span><?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
