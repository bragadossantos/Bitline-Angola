<?php
/**
 * Router único para o deploy no Vercel.
 *
 * O plano Hobby do Vercel só permite 12 Serverless Functions por
 * deployment. Este site tem 50+ ficheiros .php acedidos diretamente
 * (sem front controller), por isso usamos UM único ficheiro PHP
 * (este) como função, e o vercel.json reencaminha todos os pedidos
 * para aqui. Este router resolve o caminho pedido para o ficheiro
 * .php correspondente na raiz do projeto e executa-o, replicando o
 * comportamento de acesso direto por ficheiro que o Apache tinha.
 *
 * Ficheiros estáticos (css/js/assets/uploads/favicon/robots.txt) não
 * passam por aqui — são servidos diretamente pelo Vercel (ver rotas
 * no vercel.json).
 */

$raiz = dirname(__DIR__); // raiz do projeto (uma pasta acima de /api)

// Caminho pedido, sem query string
$caminho = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$caminho = urldecode($caminho);

// Página inicial
if ($caminho === '' || $caminho === '/') {
    $caminho = '/index.php';
}

// Normaliza separadores e bloqueia tentativas de sair da raiz do projeto (../)
$caminho = str_replace('\\', '/', $caminho);
$partes = [];
foreach (explode('/', $caminho) as $parte) {
    if ($parte === '' || $parte === '.') {
        continue;
    }
    if ($parte === '..') {
        http_response_code(400);
        exit('Pedido inválido.');
    }
    $partes[] = $parte;
}

$relativo = implode('/', $partes);

// Só executamos ficheiros .php existentes dentro da raiz do projeto
if (!preg_match('/\.php$/i', $relativo)) {
    http_response_code(404);
    exit('Não encontrado.');
}

// Bloqueia acesso direto a pastas sensíveis (equivalente ao antigo .htaccess)
// e à própria pasta do router, para evitar recursão infinita.
if (preg_match('#^(config|database|api)(/|$)#i', $relativo)) {
    http_response_code(403);
    exit('Acesso negado.');
}

$ficheiro = $raiz . '/' . $relativo;

// Confirma que o ficheiro resolvido continua dentro da raiz do projeto
$ficheiroReal = realpath($ficheiro);
$raizReal = realpath($raiz);
if ($ficheiroReal === false || $raizReal === false || strpos($ficheiroReal, $raizReal . DIRECTORY_SEPARATOR) !== 0) {
    http_response_code(404);
    exit('Página não encontrada.');
}

if (!is_file($ficheiroReal)) {
    http_response_code(404);
    exit('Página não encontrada.');
}

require $ficheiroReal;
