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

$id  = (int) ($_POST['id'] ?? 0);
$pdo = getConnection();

try {
    $stmt = $pdo->prepare('DELETE FROM faq WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $_SESSION['admin_sucesso'] = $stmt->rowCount() > 0 ? 'Pergunta eliminada.' : 'Pergunta não encontrada.';
} catch (Throwable $e) {
    error_log('Erro ao eliminar FAQ: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao eliminar a pergunta.';
}

header('Location: ../admin/faq.php');
exit;
