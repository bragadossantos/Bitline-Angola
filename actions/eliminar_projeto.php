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

$id  = (int) ($_POST['id'] ?? 0);
$pdo = getConnection();

try {
    $stmt = $pdo->prepare('SELECT imagem FROM portfolio WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $projeto = $stmt->fetch();

    if ($projeto) {
        $pdo->prepare('DELETE FROM portfolio WHERE id = :id')->execute([':id' => $id]);
        if (!empty($projeto['imagem']) && file_exists(UPLOAD_DIR_PORTFOLIO . $projeto['imagem'])) {
            @unlink(UPLOAD_DIR_PORTFOLIO . $projeto['imagem']);
        }
        $_SESSION['admin_sucesso'] = 'Projeto eliminado.';
    } else {
        $_SESSION['admin_erro'] = 'Projeto não encontrado.';
    }
} catch (Throwable $e) {
    error_log('Erro ao eliminar projeto: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao eliminar o projeto.';
}

header('Location: ../admin/portfolio.php');
exit;
