<?php
/**
 * Limpeza de performance e otimizações do WordPress
 * @package AgenciaAids
 */

/**
 * Remove emojis
 */
add_action('init', function () {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    add_filter('tiny_mce_plugins', function ($plugins) {
        return is_array($plugins) ? array_diff($plugins, ['wpemoji']) : [];
    });
    add_filter('emoji_svg_url', '__return_false');
});

/**
 * Remove o script Speculation Rules do HTML final
 */
add_action('template_redirect', function () {
    ob_start(function ($html) {
        return preg_replace(
            '#<script type="speculationrules">.*?</script>#is',
            '',
            $html
        );
    });
});

/**
 * Remove embeds (wp-embed.js) e links da API REST
 */
add_action('init', function () {
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);
    remove_action('wp_head', 'wp_oembed_add_host_js');
});

/**
 * Remove links desnecessários do head
 */
add_action('init', function () {
    remove_action('wp_head', 'rsd_link');                      // RSD
    remove_action('wp_head', 'wlwmanifest_link');              // Windows Live Writer
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);      // Shortlink
    remove_action('wp_head', 'wp_generator');                  // Versão do WP
    remove_action('wp_head', 'feed_links', 2);                 // RSS Feed
    remove_action('wp_head', 'feed_links_extra', 3);           // RSS Feed extra
});

/**
 * Remove a versão do WordPress dos scripts e estilos
 */
add_filter('style_loader_src', 'agenciaaids_remove_ver_param', 9999);
add_filter('script_loader_src', 'agenciaaids_remove_ver_param', 9999);

function agenciaaids_remove_ver_param($src) {
    if (strpos($src, 'ver=') !== false) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Remove estilos do editor de blocos no front-end
 */
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}, 100);


/**
 * Remove o estilo inline do Classic Theme (Gutenberg)
 */
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('classic-theme-styles');
    wp_deregister_style('classic-theme-styles');
}, 100);


/**
 * Carrega CF7 e Akismet apenas na página de contato
 * @package AgenciaAids
 */

add_action('wp_enqueue_scripts', function () {
    // Verifica se não estamos na página de contato (slug)
    if (!is_page('contato')) {
        // CONTACT FORM 7
        wp_dequeue_script('contact-form-7');
        wp_dequeue_style('contact-form-7');
        wp_deregister_script('contact-form-7');
        wp_deregister_style('contact-form-7');

        // TRANSLATION SCRIPTS do CF7
        wp_deregister_script('contact-form-7-js-before');
        wp_deregister_script('contact-form-7-js-translations');

        // AKISMET (usado em alguns temas com CF7)
        wp_dequeue_style('akismet-widget-styles');
        wp_deregister_style('akismet-widget-styles');
    }
}, 100);
