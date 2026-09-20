<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/portfolio.php');
    exit;
}

if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/portfolio.php');
    exit;
}

$pdo = getConnection();
$id  = (int) ($_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM portfolio WHERE id = :id');
$stmt->execute([':id' => $id]);
$atual = $stmt->fetch();

if (!$atual) {
    $_SESSION['admin_erro'] = 'Projeto não encontrado.';
    header('Location: ../admin/portfolio.php');
    exit;
}

$titulo    = trim($_POST['titulo'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$cliente   = trim($_POST['cliente'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$ordem     = (int) ($_POST['ordem'] ?? 0);
$destaque  = isset($_POST['destaque']) ? 1 : 0;

if ($titulo === '' || $descricao === '') {
    $_SESSION['admin_erro'] = 'Preenche o título e a descrição do projeto.';
    header('Location: ../admin/editar-projeto.php?id=' . $id);
    exit;
}

$nomeImagem = $atual['imagem'];
if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $nova = processarUploadGenerico($_FILES['imagem'], UPLOAD_DIR_PORTFOLIO, 'projeto_');
    if ($nova === null) {
        $_SESSION['admin_erro'] = 'A imagem enviada é inválida. Usa JPG, PNG ou WEBP até 4MB.';
        header('Location: ../admin/editar-projeto.php?id=' . $id);
        exit;
    }
    if (!empty($atual['imagem']) && file_exists(UPLOAD_DIR_PORTFOLIO . $atual['imagem'])) {
        @unlink(UPLOAD_DIR_PORTFOLIO . $atual['imagem']);
    }
    $nomeImagem = $nova;
}
if (isset($_POST['remover_imagem']) && empty($_FILES['imagem']['name'])) {
    if (!empty($atual['imagem']) && file_exists(UPLOAD_DIR_PORTFOLIO . $atual['imagem'])) {
        @unlink(UPLOAD_DIR_PORTFOLIO . $atual['imagem']);
    }
    $nomeImagem = null;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE portfolio SET titulo = :titulo, categoria = :categoria, descricao = :descricao,
         imagem = :imagem, cliente = :cliente, destaque = :destaque, ordem = :ordem WHERE id = :id'
    );
    $stmt->execute([
        ':titulo' => $titulo, ':categoria' => $categoria ?: null, ':descricao' => $descricao,
        ':imagem' => $nomeImagem, ':cliente' => $cliente ?: null, ':destaque' => $destaque,
        ':ordem' => $ordem, ':id' => $id,
    ]);

    $_SESSION['admin_sucesso'] = 'Projeto atualizado com sucesso!';
    header('Location: ../admin/portfolio.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao atualizar projeto: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao atualizar o projeto.';
    header('Location: ../admin/editar-projeto.php?id=' . $id);
    exit;
}
