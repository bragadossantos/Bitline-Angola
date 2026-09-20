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

$id  = (int) ($_POST['id'] ?? 0);
$pdo = getConnection();

try {
    $stmt = $pdo->prepare('SELECT foto FROM depoimentos WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $dep = $stmt->fetch();

    if ($dep) {
        $pdo->prepare('DELETE FROM depoimentos WHERE id = :id')->execute([':id' => $id]);
        if (!empty($dep['foto']) && file_exists(UPLOAD_DIR_DEPOIMENTOS . $dep['foto'])) {
            @unlink(UPLOAD_DIR_DEPOIMENTOS . $dep['foto']);
        }
        $_SESSION['admin_sucesso'] = 'Depoimento eliminado.';
    } else {
        $_SESSION['admin_erro'] = 'Depoimento não encontrado.';
    }
} catch (Throwable $e) {
    error_log('Erro ao eliminar depoimento: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao eliminar o depoimento.';
}

header('Location: ../admin/depoimentos.php');
exit;
