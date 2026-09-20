<?php
/**
 * Funções auxiliares reutilizadas em todo o site.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Limpa e escapa texto para output seguro em HTML */
function limpar(string $texto): string
{
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

/** Gera um slug amigável a partir de um título (com suporte a acentos) */
function gerarSlug(string $texto): string
{
    $texto = mb_strtolower($texto, 'UTF-8');
    $trocas = [
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c', 'ñ' => 'n',
    ];
    $texto = strtr($texto, $trocas);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    $texto = trim($texto, '-');
    return $texto !== '' ? $texto : 'artigo-' . time();
}

/** Garante que o slug é único na tabela artigos */
function slugUnico(PDO $pdo, string $slug, ?int $ignorarId = null): string
{
    $slugBase = $slug;
    $contador = 1;

    do {
        $sql = 'SELECT COUNT(*) FROM artigos WHERE slug = :slug';
        if ($ignorarId) {
            $sql .= ' AND id != :id';
        }
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug);
        if ($ignorarId) {
            $stmt->bindValue(':id', $ignorarId, PDO::PARAM_INT);
        }
        $stmt->execute();
        $existe = (int) $stmt->fetchColumn() > 0;

        if ($existe) {
            $contador++;
            $slug = $slugBase . '-' . $contador;
        }
    } while ($existe);

    return $slug;
}

/** Formata data para o formato português (ex: 22 de Julho de 2026) */
function formatarData(string $dataMysql): string
{
    $meses = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];
    $timestamp = strtotime($dataMysql);
    return date('d', $timestamp) . ' de ' . $meses[(int) date('n', $timestamp)] . ' de ' . date('Y', $timestamp);
}

/** Calcula tempo estimado de leitura em minutos */
function tempoLeitura(string $conteudo): int
{
    $palavras = str_word_count(strip_tags($conteudo));
    return max(1, (int) ceil($palavras / 200));
}

/** Gera / valida token CSRF simples para formulários */
function gerarTokenCSRF(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validarTokenCSRF(?string $token): bool
{
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/** Verifica se o admin está autenticado; caso contrário redireciona para o login */
function exigirLogin(): void
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

/** Devolve resposta JSON e termina a execução */
function respostaJSON(string $status, string $mensagem, array $extra = []): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['status' => $status, 'message' => $mensagem], $extra));
    exit;
}

/**
 * Como respostaJSON(), mas com rede de segurança: se o pedido não vier
 * identificado como AJAX (ex: JavaScript falhou ou está desativado),
 * em vez de mostrar JSON cru ao utilizador, guarda a mensagem na sessão
 * e devolve-o à página de origem, onde a mensagem aparece normalmente.
 */
function respostaFormulario(string $status, string $mensagem, string $paginaFallback): void
{
    $ehPedidoAjax = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'fetch';

    if ($ehPedidoAjax) {
        respostaJSON($status, $mensagem);
    }

    $_SESSION['form_status']   = $status;
    $_SESSION['form_mensagem'] = $mensagem;

    // Usa a página de onde veio o pedido, se for do próprio site; caso contrário usa o fallback indicado
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $destino = ($referer !== '' && strpos($referer, $_SERVER['HTTP_HOST'] ?? '___') !== false) ? $referer : $paginaFallback;

    header('Location: ' . $destino);
    exit;
}

/** Mostra (e limpa) a mensagem guardada por respostaFormulario(), para quando não há JS */
function mostrarMensagemFormularioSemJS(): void
{
    if (empty($_SESSION['form_mensagem'])) {
        return;
    }
    $status   = $_SESSION['form_status'] ?? 'success';
    $mensagem = $_SESSION['form_mensagem'];
    unset($_SESSION['form_status'], $_SESSION['form_mensagem']);

    $classe = $status === 'success' ? 'admin-alert-sucesso' : 'admin-alert-erro';
    echo '<div class="admin-alert ' . $classe . '" style="max-width:900px;margin:0 auto 24px;">' . limpar($mensagem) . '</div>';
}

/**
 * Valida e move o upload de imagem de um artigo para uploads/blog/.
 * Devolve o nome do ficheiro gerado, ou null em caso de erro/validação falhada.
 */
function processarUploadImagem(array $ficheiro): ?string
{
    return processarUploadGenerico($ficheiro, UPLOAD_DIR, 'artigo_');
}

/**
 * Versão genérica: valida e move o upload de uma imagem para qualquer pasta de destino.
 * Devolve o nome do ficheiro gerado, ou null em caso de erro/validação falhada.
 */
function processarUploadGenerico(array $ficheiro, string $dirDestino, string $prefixo = 'ficheiro_'): ?string
{
    $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $tamanhoMaximo       = 4 * 1024 * 1024; // 4MB

    $extensao = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, $extensoesPermitidas, true)) {
        return null;
    }
    if ($ficheiro['size'] > $tamanhoMaximo) {
        return null;
    }

    if (!is_dir($dirDestino)) {
        mkdir($dirDestino, 0755, true);
    }

    $nomeFinal = uniqid($prefixo, true) . '.' . $extensao;
    $destino   = $dirDestino . $nomeFinal;

    if (!move_uploaded_file($ficheiro['tmp_name'], $destino)) {
        return null;
    }

    return $nomeFinal;
}

/** Máscara simples de telefone/whatsapp para exibição: 929 380 158 */
function formatarTelefone(string $numero): string
{
    $numero = preg_replace('/\D/', '', $numero);
    if (strlen($numero) === 9) {
        return substr($numero, 0, 3) . ' ' . substr($numero, 3, 3) . ' ' . substr($numero, 6, 3);
    }
    return $numero;
}
