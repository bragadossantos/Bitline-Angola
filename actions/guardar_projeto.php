<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/novo-projeto.php');
    exit;
}

if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/novo-projeto.php');
    exit;
}

$pdo = getConnection();

$titulo    = trim($_POST['titulo'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$cliente   = trim($_POST['cliente'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$ordem     = (int) ($_POST['ordem'] ?? 0);
$destaque  = isset($_POST['destaque']) ? 1 : 0;

if ($titulo === '' || $descricao === '') {
    $_SESSION['admin_erro'] = 'Preenche o título e a descrição do projeto.';
    header('Location: ../admin/novo-projeto.php');
    exit;
}

$nomeImagem = null;
if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $nomeImagem = processarUploadGenerico($_FILES['imagem'], UPLOAD_DIR_PORTFOLIO, 'projeto_');
    if ($nomeImagem === null) {
        $_SESSION['admin_erro'] = 'A imagem enviada é inválida. Usa JPG, PNG ou WEBP até 4MB.';
        header('Location: ../admin/novo-projeto.php');
        exit;
    }
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO portfolio (titulo, categoria, descricao, imagem, cliente, destaque, ordem, criado_em)
         VALUES (:titulo, :categoria, :descricao, :imagem, :cliente, :destaque, :ordem, NOW())'
    );
    $stmt->execute([
        ':titulo'    => $titulo,
        ':categoria' => $categoria ?: null,
        ':descricao' => $descricao,
        ':imagem'    => $nomeImagem,
        ':cliente'   => $cliente ?: null,
        ':destaque'  => $destaque,
        ':ordem'     => $ordem,
    ]);

    $_SESSION['admin_sucesso'] = 'Projeto adicionado ao portefólio!';
    header('Location: ../admin/portfolio.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao guardar projeto: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao guardar o projeto.';
    header('Location: ../admin/novo-projeto.php');
    exit;
}
