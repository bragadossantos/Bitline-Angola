<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/funcoes.php';

// Se já está autenticado, vai direto para o dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$pdo = getConnection();
$ip  = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';

const MAX_TENTATIVAS   = 5;
const BLOQUEIO_MINUTOS = 10;

$erro = '';
$bloqueadoAte = null;

// --- Verifica se este IP está atualmente bloqueado ---
$stmt = $pdo->prepare('SELECT * FROM login_tentativas WHERE ip = :ip');
$stmt->execute([':ip' => $ip]);
$registoIp = $stmt->fetch();

if ($registoIp && $registoIp['bloqueado_ate'] && strtotime($registoIp['bloqueado_ate']) > time()) {
    $bloqueadoAte = $registoIp['bloqueado_ate'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$bloqueadoAte) {

    if (!validarTokenCSRF($_POST['csrf_token'] ?? null)) {
        $erro = 'A sessão do formulário expirou. Atualiza a página e tenta novamente.';
    } else {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $erro = 'Preenche o email e a password.';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
            $stmt->execute([':email' => $email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                // login bem-sucedido: limpa o registo de tentativas deste IP
                $pdo->prepare('DELETE FROM login_tentativas WHERE ip = :ip')->execute([':ip' => $ip]);

                session_regenerate_id(true);
                $_SESSION['admin_id']   = $admin['id'];
                $_SESSION['admin_nome'] = $admin['nome'];
                header('Location: dashboard.php');
                exit;
            }

            // login falhado: regista/incrementa a tentativa para este IP
            $tentativasAtuais = $registoIp ? (int) $registoIp['tentativas'] + 1 : 1;

            if ($tentativasAtuais >= MAX_TENTATIVAS) {
                $stmt = $pdo->prepare(
                    'INSERT INTO login_tentativas (ip, tentativas, ultima_tentativa, bloqueado_ate)
                     VALUES (:ip, :tentativas1, NOW(), DATE_ADD(NOW(), INTERVAL :minutos1 MINUTE))
                     ON DUPLICATE KEY UPDATE tentativas = :tentativas2, ultima_tentativa = NOW(),
                        bloqueado_ate = DATE_ADD(NOW(), INTERVAL :minutos2 MINUTE)'
                );
                $stmt->execute([
                    ':ip' => $ip,
                    ':tentativas1' => $tentativasAtuais, ':tentativas2' => $tentativasAtuais,
                    ':minutos1' => BLOQUEIO_MINUTOS, ':minutos2' => BLOQUEIO_MINUTOS,
                ]);
                $bloqueadoAte = date('Y-m-d H:i:s', time() + BLOQUEIO_MINUTOS * 60);
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO login_tentativas (ip, tentativas, ultima_tentativa)
                     VALUES (:ip, :tentativas1, NOW())
                     ON DUPLICATE KEY UPDATE tentativas = :tentativas2, ultima_tentativa = NOW()'
                );
                $stmt->execute([':ip' => $ip, ':tentativas1' => $tentativasAtuais, ':tentativas2' => $tentativasAtuais]);
            }

            // Mensagem propositadamente genérica: não revela se foi o email ou a password que falhou
            $erro = 'Credenciais inválidas. Verifica o email e a password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login · Admin Bitline Angola</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../css/estilo.css?v=<?= @filemtime(__DIR__ . '/../css/estilo.css') ?>">
</head>
<body class="admin-login-body">
  <div class="admin-login-box">
    <div class="admin-logo">Bit<span>line</span> <small>Admin</small></div>
    <h1>Entrar no Painel</h1>
    <p>Acesso restrito à equipa Bitline Angola.</p>

    <?php if ($bloqueadoAte): ?>
      <div class="admin-alert admin-alert-erro">
        Demasiadas tentativas falhadas. Tenta novamente às <?= date('H:i', strtotime($bloqueadoAte)) ?>.
      </div>
    <?php elseif ($erro): ?>
      <div class="admin-alert admin-alert-erro"><?= limpar($erro) ?></div>
    <?php endif; ?>

    <?php if (!$bloqueadoAte): ?>
      <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="admin@bitlineangola.co.ao" required autofocus>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit">Entrar</button>
      </form>
    <?php endif; ?>

    <a href="../index.php" class="admin-back-link"><i class="fa-solid fa-arrow-left"></i> Voltar ao site</a>
  </div>
</body>
</html>
