<?php
/**
 * Bitline Angola - Ligação à Base de Dados
 * Configuração central usando PDO (MySQL)
 */

/* ---------------------------------------------------------------
 * MODO DEBUG — controlado pela variável de ambiente MODO_DEBUG.
 * Localmente (XAMPP), sem a variável definida, mantém-se 'true'.
 * Em produção (Vercel), define MODO_DEBUG=false nas variáveis de
 * ambiente do projeto: os erros deixam de aparecer no ecrã (não
 * expõem caminhos internos nem detalhes técnicos a visitantes) e
 * passam a ficar só registados em log, no servidor.
 * ------------------------------------------------------------- */
define('MODO_DEBUG', filter_var(getenv('MODO_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN));

if (MODO_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

/* ---------------------------------------------------------------
 * SEGURANÇA — cabeçalhos HTTP e configuração de sessão
 * Corre antes de qualquer output e antes do session_start(),
 * para que as definições de cookie tenham efeito.
 * ------------------------------------------------------------- */
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');                       // evita clickjacking (o site não pode ser posto num <iframe> de outro domínio)
    header('X-Content-Type-Options: nosniff');                   // impede o browser de "adivinhar" tipos de ficheiro
    header('Referrer-Policy: strict-origin-when-cross-origin');   // limita o que é partilhado com outros sites via referrer
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()'); // desativa APIs que o site não usa

    // CSP propositadamente permissiva em script/style porque o site usa
    // atributos onclick/onsubmit inline e CDNs externos (Google Fonts, Font Awesome).
    // Continua a bloquear o essencial: carregar scripts de domínios desconhecidos.
    header("Content-Security-Policy: default-src 'self'; " .
           "img-src 'self' data: https:; " .
           "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
           "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .
           "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; " .
           "connect-src 'self';");
}

// Cookies de sessão mais seguros (tem de ser antes do session_start(), feito em includes/funcoes.php)
ini_set('session.cookie_httponly', '1');  // impede que JavaScript leia o cookie de sessão
ini_set('session.use_strict_mode', '1');  // rejeita IDs de sessão inventados
ini_set('session.cookie_samesite', 'Lax'); // reduz risco de CSRF vindo de outros sites
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', '1'); // cookie só viaja por HTTPS, quando o site já estiver em HTTPS
}

// --- Credenciais ---
// Lidas de variáveis de ambiente (definidas no painel do Vercel em produção,
// ou no teu ambiente local). Sem elas, caem nos valores por omissão do
// XAMPP/Laragon local (root sem password) — NUNCA colocar aqui credenciais
// reais de produção; define-as sempre como variáveis de ambiente.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'bitline_angola');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// --- Configurações gerais do site ---
define('SITE_NAME', 'Bitline Angola');
define('SITE_URL', getenv('SITE_URL') ?: 'https://bitlineangola.com.ao'); // domínio real (confirma se o site final usa "www." ou não)
define('UPLOAD_DIR', __DIR__ . '/../uploads/blog/');
define('UPLOAD_URL', 'uploads/blog/');
define('UPLOAD_DIR_PORTFOLIO', __DIR__ . '/../uploads/portfolio/');
define('UPLOAD_URL_PORTFOLIO', 'uploads/portfolio/');
define('UPLOAD_DIR_DEPOIMENTOS', __DIR__ . '/../uploads/depoimentos/');
define('UPLOAD_URL_DEPOIMENTOS', 'uploads/depoimentos/');
define('UPLOAD_DIR_CANDIDATURAS', __DIR__ . '/../uploads/candidaturas/');
define('UPLOAD_URL_CANDIDATURAS', 'uploads/candidaturas/');

// Contactos oficiais da Bitline Angola
define('CONTACTO_UNITEL', '929380158');
define('CONTACTO_AFRICELL', '952250142');
define('CONTACTO_EMAIL', 'geral@bitlineangola.com.ao');

function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $opcoes = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
        } catch (PDOException $e) {
            // Em produção não devemos mostrar o erro real ao utilizador
            error_log('Erro de ligação à BD: ' . $e->getMessage());
            http_response_code(500);
            die('Erro interno: não foi possível ligar à base de dados. Tente novamente mais tarde.');
        }
    }

    return $pdo;
}
