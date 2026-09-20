<?php
/**
 * Atualiza um artigo existente a partir do formulário de edição.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';

exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/artigos.php');
    exit;
}

if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/artigos.php');
    exit;
}

$pdo = getConnection();

$id       = (int) ($_POST['id'] ?? 0);
$titulo   = trim($_POST['titulo'] ?? '');
$resumo   = trim($_POST['resumo'] ?? '');
$conteudo = $_POST['conteudo'] ?? '';
$status   = in_array($_POST['status'] ?? '', ['rascunho', 'publicado'], true) ? $_POST['status'] : 'rascunho';
$destaque = isset($_POST['destaque']) ? 1 : 0;

if ($id <= 0) {
    header('Location: ../admin/artigos.php');
    exit;
}

// Confirma que o artigo existe
$stmt = $pdo->prepare('SELECT * FROM artigos WHERE id = :id');
$stmt->execute([':id' => $id]);
$artigoAtual = $stmt->fetch();

if (!$artigoAtual) {
    $_SESSION['admin_erro'] = 'Artigo não encontrado.';
    header('Location: ../admin/artigos.php');
    exit;
}

if ($titulo === '' || $resumo === '' || trim(strip_tags($conteudo)) === '') {
    $_SESSION['admin_erro'] = 'Preenche todos os campos obrigatórios.';
    header('Location: ../admin/editar-artigo.php?id=' . $id);
    exit;
}

// --- Slug ---
$slugManual = trim($_POST['slug'] ?? '');
$slugBase   = $slugManual !== '' ? gerarSlug($slugManual) : gerarSlug($titulo);
$slug       = slugUnico($pdo, $slugBase, $id);

// --- Nova imagem (opcional). Se enviada, substitui a antiga. ---
$nomeImagem = $artigoAtual['imagem'];
if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $novaImagem = processarUploadImagem($_FILES['imagem']);
    if ($novaImagem === null) {
        $_SESSION['admin_erro'] = 'A imagem enviada é inválida. Usa JPG, PNG ou WEBP até 4MB.';
        header('Location: ../admin/editar-artigo.php?id=' . $id);
        exit;
    }
    // Remove a imagem antiga do disco, se existir
    if (!empty($artigoAtual['imagem']) && file_exists(UPLOAD_DIR . $artigoAtual['imagem'])) {
        @unlink(UPLOAD_DIR . $artigoAtual['imagem']);
    }
    $nomeImagem = $novaImagem;
}

// Remover imagem existente (checkbox "remover imagem")
if (isset($_POST['remover_imagem']) && empty($_FILES['imagem']['name'])) {
    if (!empty($artigoAtual['imagem']) && file_exists(UPLOAD_DIR . $artigoAtual['imagem'])) {
        @unlink(UPLOAD_DIR . $artigoAtual['imagem']);
    }
    $nomeImagem = null;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE artigos
         SET titulo = :titulo, slug = :slug, resumo = :resumo, conteudo = :conteudo,
             imagem = :imagem, status = :status, destaque = :destaque, atualizado_em = NOW()
         WHERE id = :id'
    );
    $stmt->execute([
        ':titulo'   => $titulo,
        ':slug'     => $slug,
        ':resumo'   => $resumo,
        ':conteudo' => $conteudo,
        ':imagem'   => $nomeImagem,
        ':status'   => $status,
        ':destaque' => $destaque,
        ':id'       => $id,
    ]);

    $_SESSION['admin_sucesso'] = 'Artigo atualizado com sucesso!';
    header('Location: ../admin/artigos.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao atualizar artigo: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao atualizar o artigo. Tenta novamente.';
    header('Location: ../admin/editar-artigo.php?id=' . $id);
    exit;
}
