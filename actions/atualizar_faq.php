<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin/faq.php');
    exit;
}
if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_erro'] = 'A sessão do formulário expirou. Tenta novamente.';
    header('Location: ../admin/faq.php');
    exit;
}

$pdo = getConnection();
$id  = (int) ($_POST['id'] ?? 0);

$pergunta  = trim($_POST['pergunta'] ?? '');
$resposta  = trim($_POST['resposta'] ?? '');
$categoria = trim($_POST['categoria'] ?? '');
$ordem     = (int) ($_POST['ordem'] ?? 0);
$publicado = isset($_POST['publicado']) ? 1 : 0;

if ($pergunta === '' || $resposta === '') {
    $_SESSION['admin_erro'] = 'Preenche a pergunta e a resposta.';
    header('Location: ../admin/editar-faq.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        'UPDATE faq SET pergunta = :pergunta, resposta = :resposta, categoria = :categoria,
         ordem = :ordem, publicado = :publicado WHERE id = :id'
    );
    $stmt->execute([
        ':pergunta' => $pergunta, ':resposta' => $resposta, ':categoria' => $categoria ?: null,
        ':ordem' => $ordem, ':publicado' => $publicado, ':id' => $id,
    ]);

    $_SESSION['admin_sucesso'] = 'Pergunta atualizada!';
    header('Location: ../admin/faq.php');
    exit;
} catch (Throwable $e) {
    error_log('Erro ao atualizar FAQ: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao atualizar a pergunta.';
    header('Location: ../admin/editar-faq.php?id=' . $id);
    exit;
}
