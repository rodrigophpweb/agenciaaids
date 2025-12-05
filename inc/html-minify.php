<?php
/**
 * HTML Minification
 * 
 * Minifica o HTML final renderizado pelo WordPress para melhorar performance.
 * Remove espaços em branco desnecessários, quebras de linha e comentários HTML
 * preservando a funcionalidade e conteúdo importante.
 * 
 * @package AgenciaAids
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Função que minifica o HTML
 * 
 * Esta função recebe o buffer de HTML completo e retorna minificado.
 * Preserva conteúdo importante como <pre>, <textarea>, <script> e <style>
 * 
 * @param string $html HTML a ser minificado
 * @return string HTML minificado
 */
function agenciaaids_minify_html($html) {
    // Se estiver vazio, retorna
    if (empty($html)) {
        return $html;
    }

    // Array para armazenar blocos que NÃO devem ser minificados
    $preserve = [];
    $preserve_index = 0;

    // Padrões de tags que precisam ser preservadas
    $preserve_patterns = [
        // Preservar <pre> (código formatado)
        '/<pre\b[^>]*>.*?<\/pre>/is',
        // Preservar <textarea> (formulários)
        '/<textarea\b[^>]*>.*?<\/textarea>/is',
        // Preservar <script> (JavaScript)
        '/<script\b[^>]*>.*?<\/script>/is',
        // Preservar <style> (CSS inline)
        '/<style\b[^>]*>.*?<\/style>/is',
        // Preservar comentários condicionais IE (<!--[if ...)
        '/<!--\[if\b[^\]]*\]>.*?<!\[endif\]-->/is',
    ];

    // Substituir blocos preservados por placeholders
    foreach ($preserve_patterns as $pattern) {
        $html = preg_replace_callback($pattern, function($matches) use (&$preserve, &$preserve_index) {
            $placeholder = "___PRESERVE_{$preserve_index}___";
            $preserve[$placeholder] = $matches[0];
            $preserve_index++;
            return $placeholder;
        }, $html);
    }

    // Remover comentários HTML (exceto condicionais IE que já foram preservados)
    $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);

    // Remover espaços em branco entre tags
    $html = preg_replace('/>\s+</', '><', $html);

    // Remover múltiplos espaços em branco dentro do conteúdo
    $html = preg_replace('/\s{2,}/', ' ', $html);

    // Remover quebras de linha e tabs
    $html = str_replace(["\r\n", "\r", "\n", "\t"], '', $html);

    // Remover espaços ao redor de = em atributos HTML
    $html = preg_replace('/\s*=\s*/', '=', $html);

    // Restaurar os blocos preservados
    foreach ($preserve as $placeholder => $content) {
        $html = str_replace($placeholder, $content, $html);
    }

    return trim($html);
}

/**
 * Callback para iniciar o output buffering
 * 
 * Esta função é chamada quando o WordPress começa a renderizar.
 * Inicia o buffer de saída para capturar todo o HTML gerado.
 */
function agenciaaids_start_html_minify() {
    // Só minifica no frontend (não no admin)
    if (!is_admin() && !is_customize_preview()) {
        ob_start('agenciaaids_minify_html');
    }
}

/**
 * Callback para finalizar o output buffering
 * 
 * Garante que o buffer seja processado antes de enviar ao navegador.
 */
function agenciaaids_end_html_minify() {
    if (!is_admin() && !is_customize_preview()) {
        if (ob_get_level() > 0) {
            ob_end_flush();
        }
    }
}

/**
 * Hooks do WordPress
 * 
 * template_redirect: Dispara antes do WordPress carregar o template
 * - Perfeito para iniciar o buffer antes de qualquer saída HTML
 * 
 * shutdown: Dispara no final da execução do WordPress
 * - Garante que todo o HTML foi capturado antes de finalizar
 */
add_action('template_redirect', 'agenciaaids_start_html_minify', 1);
add_action('shutdown', 'agenciaaids_end_html_minify', 999);
