<?php
/**
 * Remove emojis do WordPress para melhorar performance
 * Desabilita a impressão de scripts e estilos relacionados a emojis
 * e remove o plugin do TinyMCE.
 * @package AgenciaAids
 * @author Rodrigo Vieira Eufrasio da Silva
 * @since 1.0.0
 * @link https://developer.wordpress.org/reference/hooks/init/
 * @link https://developer.wordpress.org/reference/functions/remove_action/
 * @link https://developer.wordpress.org/reference/functions/remove_filter/
 * @link https://developer.wordpress.org/reference/functions/add_filter/
 * @link https://developer.wordpress.org/reference/functions/add_action/
 * @link https://developer.wordpress.org/reference/hooks/tiny_mce_plugins/
 * @link https://developer.wordpress.org/reference/functions/emoji_svg_url/
 * @link https://developer.wordpress.org/reference/hooks/emoji_svg_url/
 * @link https://developer.wordpress.org/reference/hooks/init/
 * @link https://developer.wordpress.org/reference/hooks/remove_action/
 */

function agenciaaids_disable_emojis() {
    // Remove ações relacionadas aos emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove emojis do TinyMCE
    add_filter('tiny_mce_plugins', 'agenciaaids_disable_emojis_tinymce');

    // Remove URL do SVG
    add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'agenciaaids_disable_emojis');

function agenciaaids_disable_emojis_tinymce($plugins) {
    return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
}
