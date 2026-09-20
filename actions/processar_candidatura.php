<?php
/**
 * Processa o formulário de candidatura da página "Trabalha Connosco".
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respostaFormulario('error', 'Método não permitido.', '../trabalha-connosco.php');
}

if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    respostaFormulario('error', 'A sessão do formulário expirou. Atualiza a página e tenta novamente.', '../trabalha-connosco.php');
}

// Honeypot anti-bot
if (!empty($_POST['website_url_check'])) {
    respostaFormulario('success', 'Candidatura enviada com sucesso!', '../trabalha-connosco.php');
}

$nome           = trim($_POST['nome'] ?? '');
$email          = trim($_POST['email'] ?? '');
$telefone       = trim($_POST['telefone'] ?? '');
$areaInteresse  = trim($_POST['area_interesse'] ?? '');
$mensagem       = trim($_POST['mensagem'] ?? '');

if (mb_strlen($nome) < 2) {
    respostaFormulario('error', 'Indica o teu nome.', '../trabalha-connosco.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respostaFormulario('error', 'Indica um email válido.', '../trabalha-connosco.php');
}

// --- Upload de CV (opcional, só PDF) ---
$nomeFicheiroCv = null;
if (!empty($_FILES['cv']['name']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $extensao = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    $tamanhoMaximo = 4 * 1024 * 1024; // 4MB

    if ($extensao === 'pdf' && $_FILES['cv']['size'] <= $tamanhoMaximo) {
        if (!is_dir(UPLOAD_DIR_CANDIDATURAS)) {
            mkdir(UPLOAD_DIR_CANDIDATURAS, 0755, true);
        }
        $nomeFicheiroCv = uniqid('cv_', true) . '.pdf';
        move_uploaded_file($_FILES['cv']['tmp_name'], UPLOAD_DIR_CANDIDATURAS . $nomeFicheiroCv);
    }
}

try {
    $pdo = getConnection();
    $stmt = $pdo->prepare(
        'INSERT INTO candidaturas (nome, email, telefone, area_interesse, mensagem, cv_ficheiro, status, criado_em)
         VALUES (:nome, :email, :telefone, :area, :mensagem, :cv, "nova", NOW())'
    );
    $stmt->execute([
        ':nome'     => $nome,
        ':email'    => $email,
        ':telefone' => $telefone,
        ':area'     => $areaInteresse,
        ':mensagem' => $mensagem,
        ':cv'       => $nomeFicheiroCv,
    ]);

    respostaFormulario('success', 'Candidatura enviada com sucesso! A nossa equipa vai analisar o teu perfil.', '../trabalha-connosco.php');
} catch (Throwable $e) {
    error_log('Erro ao guardar candidatura: ' . $e->getMessage());
    respostaFormulario('error', 'Ocorreu um erro ao enviar a candidatura. Tenta novamente.', '../trabalha-connosco.php');
}
