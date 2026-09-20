<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/novo-depoimento.php');
    exit;
}
if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/novo-depoimento.php');
    exit;
}

$pdo = getConnection();

$nome      = trim($_POST['nome'] ?? '');
$empresa   = trim($_POST['empresa'] ?? '');
$texto     = trim($_POST['texto'] ?? '');
$avaliacao = max(1, min(5, (int) ($_POST['avaliacao'] ?? 5)));
$publicado = isset($_POST['publicado']) ? 1 : 0;

if ($nome === '' || $texto === '') {
    $_SESSION['admin_erro'] = 'Preenche o nome e o texto do depoimento.';
    header('Location: ../admin/novo-depoimento.php');
    exit;
}

$nomeFoto = null;
if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $nomeFoto = processarUploadGenerico($_FILES['foto'], UPLOAD_DIR_DEPOIMENTOS, 'depoimento_');
    if ($nomeFoto === null) {
        $_SESSION['admin_erro'] = 'A foto enviada é inválida. Usa JPG, PNG ou WEBP até 4MB.';
        header('Location: ../admin/novo-depoimento.php');
        exit;
    }
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO depoimentos (nome, empresa, texto, avaliacao, foto, publicado, criado_em)
         VALUES (:nome, :empresa, :texto, :avaliacao, :foto, :publicado, NOW())'
    );
    $stmt->execute([
        ':nome' => $nome, ':empresa' => $empresa ?: null, ':texto' => $texto,
        ':avaliacao' => $avaliacao, ':foto' => $nomeFoto, ':publicado' => $publicado,
    ]);

    $_SESSION['admin_sucesso'] = 'Depoimento adicionado!';
    header('Location: ../admin/depoimentos.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao guardar depoimento: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao guardar o depoimento.';
    header('Location: ../admin/novo-depoimento.php');
    exit;
}
