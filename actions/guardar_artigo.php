<?php
/**
 * Guarda um novo artigo criado no painel administrativo.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';

exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/novo-artigo.php');
    exit;
}

if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/novo-artigo.php');
    exit;
}

$pdo = getConnection();

$titulo   = trim($_POST['titulo'] ?? '');
$resumo   = trim($_POST['resumo'] ?? '');
$conteudo = $_POST['conteudo'] ?? '';
$status   = in_array($_POST['status'] ?? '', ['rascunho', 'publicado'], true) ? $_POST['status'] : 'rascunho';
$destaque = isset($_POST['destaque']) ? 1 : 0;

if ($titulo === '' || $resumo === '' || trim(strip_tags($conteudo)) === '') {
    $_SESSION['admin_erro'] = 'Preenche todos os campos obrigatórios (título, resumo e conteúdo).';
    header('Location: ../admin/novo-artigo.php');
    exit;
}

// --- Slug ---
$slugManual = trim($_POST['slug'] ?? '');
$slugBase   = $slugManual !== '' ? gerarSlug($slugManual) : gerarSlug($titulo);
$slug       = slugUnico($pdo, $slugBase);

// --- Upload de imagem (opcional) ---
$nomeImagem = null;
if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $nomeImagem = processarUploadImagem($_FILES['imagem']);
    if ($nomeImagem === null) {
        $_SESSION['admin_erro'] = 'A imagem enviada é inválida. Usa JPG, PNG ou WEBP até 4MB.';
        header('Location: ../admin/novo-artigo.php');
        exit;
    }
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO artigos (titulo, slug, resumo, conteudo, imagem, status, destaque, autor_id, visualizacoes, criado_em, atualizado_em)
         VALUES (:titulo, :slug, :resumo, :conteudo, :imagem, :status, :destaque, :autor_id, 0, NOW(), NOW())'
    );
    $stmt->execute([
        ':titulo'   => $titulo,
        ':slug'     => $slug,
        ':resumo'   => $resumo,
        ':conteudo' => $conteudo,
        ':imagem'   => $nomeImagem,
        ':status'   => $status,
        ':destaque' => $destaque,
        ':autor_id' => $_SESSION['admin_id'],
    ]);

    $_SESSION['admin_sucesso'] = 'Artigo criado com sucesso!';
    header('Location: ../admin/artigos.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao guardar artigo: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao guardar o artigo. Tenta novamente.';
    header('Location: ../admin/novo-artigo.php');
    exit;
}
