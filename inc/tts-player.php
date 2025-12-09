<?php
/**
 * TTS Player - Enfileira scripts e estilos do Text-to-Speech Player
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 */

/**
 * Carrega scripts e estilos do TTS Player apenas em páginas single
 */
function agenciaaids_enqueue_tts_player() {
    // Apenas carregar em páginas single (posts individuais)
    if (!is_single()) {
        return;
    }

    $theme_version = wp_get_theme()->get('Version');
    $js_file_path = get_template_directory() . '/assets/js/tts-player.js';
    $css_file_path = get_template_directory() . '/assets/css/components/tts-player.css';

    // Verificar se os arquivos existem antes de enfileirar
    if (file_exists($js_file_path)) {
        $js_file_uri = get_template_directory_uri() . '/assets/js/tts-player.js';
        $js_version = $theme_version . '.' . filemtime($js_file_path);

        wp_enqueue_script(
            'tts-player',
            $js_file_uri,
            [], // Sem dependências
            $js_version,
            true // Carregar no footer
        );
    }

    if (file_exists($css_file_path)) {
        $css_file_uri = get_template_directory_uri() . '/assets/css/components/tts-player.css';
        $css_version = $theme_version . '.' . filemtime($css_file_path);

        wp_enqueue_style(
            'tts-player',
            $css_file_uri,
            [], // Sem dependências
            $css_version
        );
    }
}
add_action('wp_enqueue_scripts', 'agenciaaids_enqueue_tts_player');
