<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/depoimentos.php');
    exit;
}
if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/depoimentos.php');
    exit;
}

$pdo = getConnection();
$id  = (int) ($_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM depoimentos WHERE id = :id');
$stmt->execute([':id' => $id]);
$atual = $stmt->fetch();

if (!$atual) {
    $_SESSION['admin_erro'] = 'Depoimento não encontrado.';
    header('Location: ../admin/depoimentos.php');
    exit;
}

$nome      = trim($_POST['nome'] ?? '');
$empresa   = trim($_POST['empresa'] ?? '');
$texto     = trim($_POST['texto'] ?? '');
$avaliacao = max(1, min(5, (int) ($_POST['avaliacao'] ?? 5)));
$publicado = isset($_POST['publicado']) ? 1 : 0;

if ($nome === '' || $texto === '') {
    $_SESSION['admin_erro'] = 'Preenche o nome e o texto do depoimento.';
    header('Location: ../admin/editar-depoimento.php?id=' . $id);
    exit;
}

$nomeFoto = $atual['foto'];
if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $nova = processarUploadGenerico($_FILES['foto'], UPLOAD_DIR_DEPOIMENTOS, 'depoimento_');
    if ($nova === null) {
        $_SESSION['admin_erro'] = 'A foto enviada é inválida. Usa JPG, PNG ou WEBP até 4MB.';
        header('Location: ../admin/editar-depoimento.php?id=' . $id);
        exit;
    }
    if (!empty($atual['foto']) && file_exists(UPLOAD_DIR_DEPOIMENTOS . $atual['foto'])) {
        @unlink(UPLOAD_DIR_DEPOIMENTOS . $atual['foto']);
    }
    $nomeFoto = $nova;
}
if (isset($_POST['remover_foto']) && empty($_FILES['foto']['name'])) {
    if (!empty($atual['foto']) && file_exists(UPLOAD_DIR_DEPOIMENTOS . $atual['foto'])) {
        @unlink(UPLOAD_DIR_DEPOIMENTOS . $atual['foto']);
    }
    $nomeFoto = null;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE depoimentos SET nome = :nome, empresa = :empresa, texto = :texto,
         avaliacao = :avaliacao, foto = :foto, publicado = :publicado WHERE id = :id'
    );
    $stmt->execute([
        ':nome' => $nome, ':empresa' => $empresa ?: null, ':texto' => $texto,
        ':avaliacao' => $avaliacao, ':foto' => $nomeFoto, ':publicado' => $publicado, ':id' => $id,
    ]);

    $_SESSION['admin_sucesso'] = 'Depoimento atualizado!';
    header('Location: ../admin/depoimentos.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao atualizar depoimento: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao atualizar o depoimento.';
    header('Location: ../admin/editar-depoimento.php?id=' . $id);
    exit;
}
