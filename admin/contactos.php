<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();

// --- Ações rápidas: marcar como lido / eliminar (processadas na própria página) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'], $_POST['id'])) {
    if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
        $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
        header('Location: contactos.php');
        exit;
    }

    $id = (int) $_POST['id'];

    if ($_POST['acao'] === 'marcar_lido') {
        $pdo->prepare('UPDATE contactos SET lido = 1 WHERE id = :id')->execute([':id' => $id]);
    } elseif ($_POST['acao'] === 'eliminar') {
        $pdo->prepare('DELETE FROM contactos WHERE id = :id')->execute([':id' => $id]);
        $_SESSION['admin_sucesso'] = 'Mensagem eliminada.';
    }

    header('Location: contactos.php');
    exit;
}

$filtro = $_GET['filtro'] ?? 'todos';
$sql = 'SELECT * FROM contactos';
if ($filtro === 'nao-lidos') {
    $sql .= ' WHERE lido = 0';
}
$sql .= ' ORDER BY criado_em DESC';
$contactos = $pdo->query($sql)->fetchAll();

$tituloPagina     = 'Contactos';
$paginaAdminAtiva = 'contactos';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-toolbar">
  <div class="admin-filtros">
    <a href="contactos.php" class="<?= $filtro === 'todos' ? 'ativo' : '' ?>">Todos</a>
    <a href="contactos.php?filtro=nao-lidos" class="<?= $filtro === 'nao-lidos' ? 'ativo' : '' ?>">Não Lidos</a>
  </div>
</div>

<div class="admin-panel">
  <?php if (empty($contactos)): ?>
    <p class="admin-vazio">Ainda não há mensagens de contacto.</p>
  <?php else: ?>
    <div class="admin-contactos-lista">
      <?php foreach ($contactos as $c): ?>
        <div class="admin-contacto-card <?= $c['lido'] ? '' : 'nao-lido' ?>">
          <div class="admin-contacto-topo">
            <div>
              <strong><?= limpar($c['nome']) ?></strong>
              <?php if (!$c['lido']): ?><span class="badge badge-vermelho">Novo</span><?php endif; ?>
              <div class="admin-contacto-sub">
                <a href="mailto:<?= limpar($c['email']) ?>"><i class="fa-solid fa-envelope"></i> <?= limpar($c['email']) ?></a>
                <?php if (!empty($c['telefone'])): ?>
                  <span><i class="fa-solid fa-phone"></i> <?= limpar($c['telefone']) ?></span>
                <?php endif; ?>
                <span><i class="fa-solid fa-tag"></i> <?= limpar($c['assunto'] ?: 'Consultoria Geral') ?></span>
                <span><i class="fa-solid fa-clock"></i> <?= formatarData($c['criado_em']) ?></span>
              </div>
            </div>
            <div class="admin-contacto-acoes">
              <?php if (!$c['lido']): ?>
                <form method="POST" action="contactos.php">
                  <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                  <input type="hidden" name="id" value="<?= $c['id'] ?>">
                  <input type="hidden" name="acao" value="marcar_lido">
                  <button type="submit" title="Marcar como lido"><i class="fa-solid fa-check"></i></button>
                </form>
              <?php endif; ?>
              <form method="POST" action="contactos.php" onsubmit="return confirm('Eliminar esta mensagem?');">
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="hidden" name="acao" value="eliminar">
                <button type="submit" class="admin-eliminar" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </div>
          <p class="admin-contacto-mensagem"><?= nl2br(limpar($c['mensagem'])) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
