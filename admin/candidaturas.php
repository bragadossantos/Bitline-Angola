<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

$pdo = getConnection();

// --- Ações rápidas: mudar estado / eliminar ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'], $_POST['id'])) {
    if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
        $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
        header('Location: candidaturas.php');
        exit;
    }

    $id = (int) $_POST['id'];

    if ($_POST['acao'] === 'mudar_estado' && in_array($_POST['status'] ?? '', ['nova', 'vista', 'aceite', 'rejeitada'], true)) {
        $pdo->prepare('UPDATE candidaturas SET status = :status WHERE id = :id')
            ->execute([':status' => $_POST['status'], ':id' => $id]);
    } elseif ($_POST['acao'] === 'eliminar') {
        $stmt = $pdo->prepare('SELECT cv_ficheiro FROM candidaturas WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $c = $stmt->fetch();
        $pdo->prepare('DELETE FROM candidaturas WHERE id = :id')->execute([':id' => $id]);
        if ($c && !empty($c['cv_ficheiro']) && file_exists(UPLOAD_DIR_CANDIDATURAS . $c['cv_ficheiro'])) {
            @unlink(UPLOAD_DIR_CANDIDATURAS . $c['cv_ficheiro']);
        }
        $_SESSION['admin_sucesso'] = 'Candidatura eliminada.';
    }

    header('Location: candidaturas.php');
    exit;
}

$filtro = $_GET['filtro'] ?? 'todas';
$sql = 'SELECT * FROM candidaturas';
if (in_array($filtro, ['nova', 'vista', 'aceite', 'rejeitada'], true)) {
    $sql .= ' WHERE status = ' . $pdo->quote($filtro);
}
$sql .= ' ORDER BY criado_em DESC';
$candidaturas = $pdo->query($sql)->fetchAll();

$tituloPagina     = 'Candidaturas';
$paginaAdminAtiva = 'candidaturas';
require __DIR__ . '/includes/admin-header.php';
?>

<div class="admin-toolbar">
  <div class="admin-filtros">
    <a href="candidaturas.php" class="<?= $filtro === 'todas' ? 'ativo' : '' ?>">Todas</a>
    <a href="candidaturas.php?filtro=nova" class="<?= $filtro === 'nova' ? 'ativo' : '' ?>">Novas</a>
    <a href="candidaturas.php?filtro=vista" class="<?= $filtro === 'vista' ? 'ativo' : '' ?>">Vistas</a>
    <a href="candidaturas.php?filtro=aceite" class="<?= $filtro === 'aceite' ? 'ativo' : '' ?>">Aceites</a>
    <a href="candidaturas.php?filtro=rejeitada" class="<?= $filtro === 'rejeitada' ? 'ativo' : '' ?>">Rejeitadas</a>
  </div>
</div>

<div class="admin-panel">
  <?php if (empty($candidaturas)): ?>
    <p class="admin-vazio">Ainda não há candidaturas recebidas.</p>
  <?php else: ?>
    <div class="admin-contactos-lista">
      <?php foreach ($candidaturas as $c): ?>
        <div class="admin-contacto-card <?= $c['status'] === 'nova' ? 'nao-lido' : '' ?>">
          <div class="admin-contacto-topo">
            <div>
              <strong><?= limpar($c['nome']) ?></strong>
              <span class="badge badge-<?= ['nova' => 'azul', 'vista' => 'amarelo', 'aceite' => 'verde', 'rejeitada' => 'vermelho'][$c['status']] ?>">
                <?= ucfirst($c['status']) ?>
              </span>
              <div class="admin-contacto-sub">
                <a href="mailto:<?= limpar($c['email']) ?>"><i class="fa-solid fa-envelope"></i> <?= limpar($c['email']) ?></a>
                <?php if (!empty($c['telefone'])): ?><span><i class="fa-solid fa-phone"></i> <?= limpar($c['telefone']) ?></span><?php endif; ?>
                <?php if (!empty($c['area_interesse'])): ?><span><i class="fa-solid fa-tag"></i> <?= limpar($c['area_interesse']) ?></span><?php endif; ?>
                <span><i class="fa-solid fa-clock"></i> <?= formatarData($c['criado_em']) ?></span>
                <?php if (!empty($c['cv_ficheiro'])): ?>
                  <a href="../<?= UPLOAD_URL_CANDIDATURAS . limpar($c['cv_ficheiro']) ?>" target="_blank"><i class="fa-solid fa-file-pdf"></i> Ver CV</a>
                <?php endif; ?>
              </div>
            </div>
            <div class="admin-contacto-acoes">
              <form method="POST" action="candidaturas.php" class="admin-select-form">
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="hidden" name="acao" value="mudar_estado">
                <select name="status" onchange="this.form.submit()">
                  <option value="nova" <?= $c['status'] === 'nova' ? 'selected' : '' ?>>Nova</option>
                  <option value="vista" <?= $c['status'] === 'vista' ? 'selected' : '' ?>>Vista</option>
                  <option value="aceite" <?= $c['status'] === 'aceite' ? 'selected' : '' ?>>Aceite</option>
                  <option value="rejeitada" <?= $c['status'] === 'rejeitada' ? 'selected' : '' ?>>Rejeitada</option>
                </select>
              </form>
              <form method="POST" action="candidaturas.php" onsubmit="return confirm('Eliminar esta candidatura?');">
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="hidden" name="acao" value="eliminar">
                <button type="submit" class="admin-eliminar" title="Eliminar"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </div>
          <?php if (!empty($c['mensagem'])): ?>
            <p class="admin-contacto-mensagem"><?= nl2br(limpar($c['mensagem'])) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/admin-footer.php'; ?>
