<?php
/*
 * Renderiza uma seção de conteúdo a partir de um template específico.
 */
function render_section($template, $args = []) {
    $path = get_template_directory() . "/partials/sections/{$template}.php";
    if (file_exists($path)) {
        get_template_part("partials/sections/{$template}", null, $args);
    }
}

/**
 * Obtém a miniatura do YouTube a partir de uma URL de vídeo, considerando múltiplos formatos.
 *
 * @param string $url URL do vídeo do YouTube.
 * @return string|null URL da miniatura ou null se não for um vídeo válido.
 */
function get_youtube_thumbnail($url) {
    if (!$url || !is_string($url)) return null;

    // Limpa a URL de parâmetros (ex: ?si=abc)
    $clean_url = strtok($url, '?');

    // Tenta extrair o ID do vídeo com regex confiável
    if (preg_match('~
        (?:youtube\.com/(?:embed/|v/|watch\?v=)|youtu\.be/)
        ([\w\-]{11})
    ~x', $clean_url, $matches)) {
        $video_id = $matches[1];

        if (strlen($video_id) === 11) {
            return "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";
        }
    }

    return null;
}

