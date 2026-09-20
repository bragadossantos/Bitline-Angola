<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/novo-faq.php');
    exit;
}
if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/novo-faq.php');
    exit;
}

$pdo = getConnection();

$pergunta  = trim($_POST['pergunta'] ?? '');
$resposta  = trim($_POST['resposta'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$ordem     = (int) ($_POST['ordem'] ?? 0);
$publicado = isset($_POST['publicado']) ? 1 : 0;

if ($pergunta === '' || $resposta === '') {
    $_SESSION['admin_erro'] = 'Preenche a pergunta e a resposta.';
    header('Location: ../admin/novo-faq.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        'INSERT INTO faq (pergunta, resposta, categoria, ordem, publicado, criado_em)
         VALUES (:pergunta, :resposta, :categoria, :ordem, :publicado, NOW())'
    );
    $stmt->execute([
        ':pergunta' => $pergunta, ':resposta' => $resposta, ':categoria' => $categoria ?: null,
        ':ordem' => $ordem, ':publicado' => $publicado,
    ]);

    $_SESSION['admin_sucesso'] = 'Pergunta adicionada ao FAQ!';
    header('Location: ../admin/faq.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao guardar FAQ: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao guardar a pergunta.';
    header('Location: ../admin/novo-faq.php');
    exit;
}
