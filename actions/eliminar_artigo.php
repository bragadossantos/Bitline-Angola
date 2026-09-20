<?php
/**
 * Elimina um artigo (e a respetiva imagem, se existir).
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

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: ../admin/artigos.php');
    exit;
}

$pdo = getConnection();

try {
    $stmt = $pdo->prepare('SELECT imagem FROM artigos WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $artigo = $stmt->fetch();

    if ($artigo) {
        $pdo->prepare('DELETE FROM artigos WHERE id = :id')->execute([':id' => $id]);

        if (!empty($artigo['imagem']) && file_exists(UPLOAD_DIR . $artigo['imagem'])) {
            @unlink(UPLOAD_DIR . $artigo['imagem']);
        }

        $_SESSION['admin_sucesso'] = 'Artigo eliminado com sucesso.';
    } else {
        $_SESSION['admin_erro'] = 'Artigo não encontrado.';
    }
} catch (Throwable $e) {
    error_log('Erro ao eliminar artigo: ' . $e->getMessage());
    $_SESSION['admin_erro'] = 'Ocorreu um erro ao eliminar o artigo.';
}

header('Location: ../admin/artigos.php');
exit;
