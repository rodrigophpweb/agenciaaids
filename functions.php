<?php
// --------------------------------------------------
// Suporte ao tema
// --------------------------------------------------
require_once get_template_directory() . '/inc/setup-theme.php';

// --------------------------------------------------
// Scripts e estilos
// --------------------------------------------------
require_once get_template_directory() . '/inc/style-scripts.php';

// --------------------------------------------------
// Helpers e funções utilitárias
// --------------------------------------------------
require_once get_template_directory() . '/inc/helpers.php';

// --------------------------------------------------
// Breadcrumb
// --------------------------------------------------
require_once get_template_directory() . '/inc/breadcrumb.php';

// --------------------------------------------------
// Menus
// --------------------------------------------------
require_once get_template_directory() . '/inc/register-menus.php';

// --------------------------------------------------
// Tamanhos de imagem
// --------------------------------------------------
require_once get_template_directory() . '/inc/image-sizes.php';

// --------------------------------------------------
// ACF - Campos personalizados e Opções do Tema
// --------------------------------------------------
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => 'Configurações do Tema',
        'menu_title' => 'Configurações do Tema',
        'menu_slug'  => 'configuracoes-do-tema',
        'capability' => 'edit_posts',
        'redirect'   => false,
    ]);
}

// Autoload dos campos ACF
add_action('acf/init', function () {
    $acf_dir = get_template_directory() . '/inc/fields';
    foreach (glob($acf_dir . '/*.php') as $file) {
        require_once $file;
    }
});

// --------------------------------------------------
// Ajustes no WordPress
// --------------------------------------------------
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    return str_replace(" type='text/javascript'", '', $tag);
}, 10, 3);

add_action('init', function () {
    add_post_type_support('post', 'excerpt');
    add_post_type_support('page', 'excerpt');
});

// --------------------------------------------------
// Registro e ajustes de CPT e queries
// --------------------------------------------------
require_once get_template_directory() . '/inc/ctp.php';
require_once get_template_directory() . '/inc/query-mods.php';

// --------------------------------------------------
// Cron para anúncios
// --------------------------------------------------
require_once get_template_directory() . '/inc/ads-cron.php';

// --------------------------------------------------
// Limpezas e performance
// --------------------------------------------------
require_once get_template_directory() . '/inc/performance-cleanup.php';
require_once get_template_directory() . '/inc/disable-emojis.php';
require_once get_template_directory() . '/inc/disable-speculation-rules.php';
require_once get_template_directory() . '/inc/cleanup-html-output.php';

// --------------------------------------------------
// Outras personalizações
// --------------------------------------------------
require_once get_template_directory() . '/inc/custom-excerpt-lenght.php';

// --------------------------------------------------
// Ocultar o tipo de post padrão "Post"
// --------------------------------------------------
add_action('admin_menu', function () {
    remove_menu_page('edit.php');
});

function agenciaaids_remover_primeiro_paragrafo_imagem($content) {
    if (!is_singular('post') && !is_singular('artigos')) return $content;

    // Remove o primeiro <p> se ele contiver apenas uma <img>
    $pattern = '/^<p>\s*(<img[^>]+>)\s*<\/p>/i';

    $content = preg_replace($pattern, '', $content, 1);

    return $content;
}
add_filter('the_content', 'agenciaaids_remover_primeiro_paragrafo_imagem');

