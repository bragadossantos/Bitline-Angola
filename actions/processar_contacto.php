<?php
/**
 * Processa os formulários de contacto (rápido do index e completo do contacto.php).
 * Devolve sempre JSON, porque é chamado via fetch() no js/app.js.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respostaFormulario('error', 'Método não permitido.', '../contacto.php');
}

if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
    respostaFormulario('error', 'A sessão do formulário expirou. Atualiza a página e tenta novamente.', '../contacto.php');
}

// --- Honeypot anti-bot: se o campo escondido vier preenchido, finge sucesso e ignora ---
if (!empty($_POST['website_url_check'])) {
    respostaFormulario('success', 'Mensagem enviada com sucesso!', '../contacto.php');
}

$nome     = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$telefone = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
$assunto  = isset($_POST['assunto']) ? trim($_POST['assunto']) : 'Consultoria Geral';
$mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';

// O formulário rápido do index só tem email — preenchemos os restantes campos
if ($nome === '') {
    $nome = 'Contacto Rápido (via site)';
}
if ($mensagem === '') {
    $mensagem = 'Pedido de contacto enviado através do formulário rápido da página inicial.';
}

// --- Validações ---
$erros = [];

if (mb_strlen($nome) < 2) {
    $erros[] = 'Indica o teu nome.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'Indica um email válido.';
}

if (mb_strlen($mensagem) < 3) {
    $erros[] = 'A mensagem é demasiado curta.';
}

if (!empty($erros)) {
    respostaFormulario('error', implode(' ', $erros), '../contacto.php');
}

try {
    $pdo = getConnection();
    $stmt = $pdo->prepare(
        'INSERT INTO contactos (nome, email, telefone, assunto, mensagem, ip, user_agent, criado_em)
         VALUES (:nome, :email, :telefone, :assunto, :mensagem, :ip, :ua, NOW())'
    );
    $stmt->execute([
        ':nome'     => $nome,
        ':email'    => $email,
        ':telefone' => $telefone,
        ':assunto'  => $assunto,
        ':mensagem' => $mensagem,
        ':ip'       => $_SERVER['REMOTE_ADDR'] ?? '',
        ':ua'       => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);

    respostaFormulario('success', 'Obrigado! A tua mensagem foi enviada. A nossa equipa entrará em contacto em breve.', '../contacto.php');
} catch (Throwable $e) {
    error_log('Erro ao guardar contacto: ' . $e->getMessage());
    respostaFormulario('error', 'Ocorreu um erro ao enviar a tua mensagem. Tenta novamente ou liga para nós.', '../contacto.php');
}
