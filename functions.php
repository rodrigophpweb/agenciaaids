<?php

// Habilitar suporte ao logotipo personalizado
function theme_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 122,
        'width'       => 114,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'theme_setup');
add_theme_support('post-thumbnails');

function enqueue_custom_scripts() {
    wp_enqueue_script('custom-app-js', get_template_directory_uri() . '/assets/js/app.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Arquivos auxiliares
require_once get_template_directory() . '/inc/helpers.php';



function custom_breadcrumbs() {
    // Configurações
    $separator = ' » ';
    $home_title = 'Início';

    // Obter o objeto global do WordPress
    global $post;

    // Início do breadcrumb
    echo '<nav aria-label="breadcrumb" class="paddingContent">';
    echo '<ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';

    // Home (primeiro item do breadcrumb)
    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a href="' . get_home_url() . '" itemprop="item"><span itemprop="name">' . $home_title . '</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';

    // Páginas internas
    if (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a href="' . get_category_link($category->term_id) . '" itemprop="item">';
            echo '<span itemprop="name">' . $category->name . '</span>';
            echo '</a>';
            echo '<meta itemprop="position" content="2" />';
            echo '</li>';
        }
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="3" />';
        echo '</li>';
    } elseif (is_page()) {
        if ($post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = [];
            while ($parent_id) {
                $page = get_post($parent_id);
                $breadcrumbs[] = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                    <a href="' . get_permalink($page->ID) . '" itemprop="item">
                                        <span itemprop="name">' . get_the_title($page->ID) . '</span>
                                    </a>
                                    <meta itemprop="position" content="' . (count($breadcrumbs) + 2) . '" />
                                  </li>';
                $parent_id = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $index => $breadcrumb) {
                echo $breadcrumb;
                // Adicionar o separador após cada breadcrumb, exceto o último
                if ($index < count($breadcrumbs) - 1) {
                    echo '<span class="separator">' . $separator . '</span>';
                }
            }
            // Adicionar o separador antes da página atual
            echo '<span class="separator">' . $separator . '</span>';
        }
        // Adicionar a página atual ao breadcrumb
        echo '<span class="separator">' . $separator . '</span>';
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . (isset($breadcrumbs) ? count($breadcrumbs) + 2 : 2) . '" />';
        echo '</li>';    
        // Adicionar o separador entre "Início" e a página atual, caso não tenha pai
    } elseif (is_category()) {
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . single_cat_title('', false) . '</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_archive()) {
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . post_type_archive_title('', false) . '</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_search()) {
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">Resultados da pesquisa para "' . get_search_query() . '"</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_404()) {
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">Erro 404</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';

    // Estrutura JSON-LD para SEO
    $breadcrumb_json = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => []
    ];

    $position = 1;
    $breadcrumb_json["itemListElement"][] = [
        "@type" => "ListItem",
        "position" => $position,
        "name" => $home_title,
        "item" => get_home_url()
    ];
    $position++;

    if (is_single()) {
        if (!empty($categories)) {
            $breadcrumb_json["itemListElement"][] = [
                "@type" => "ListItem",
                "position" => $position,
                "name" => $category->name,
                "item" => get_category_link($category->term_id)
            ];
            $position++;
        }
        $breadcrumb_json["itemListElement"][] = [
            "@type" => "ListItem",
            "position" => $position,
            "name" => get_the_title()
        ];
    } elseif (is_page() && $post->post_parent) {
        foreach ($breadcrumbs as $breadcrumb) {
            $position++;
            $breadcrumb_json["itemListElement"][] = [
                "@type" => "ListItem",
                "position" => $position,
                "name" => get_the_title($post->post_parent),
                "item" => get_permalink($post->post_parent)
            ];
        }
        $position++;
        $breadcrumb_json["itemListElement"][] = [
            "@type" => "ListItem",
            "position" => $position,
            "name" => get_the_title()
        ];
    }

    echo '<script type="application/ld+json">' . json_encode($breadcrumb_json, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

// Exibir o breadcrumb no template
function display_custom_breadcrumbs() {
    if (!is_home() && !is_front_page()) {
        custom_breadcrumbs();
    }
}

function register_my_menus() {
    register_nav_menus(
        [
            'header-menu' => __( 'Menu do Cabeçalho' ),
            'footer-menu' => __( 'Menu do Rodapé' ),

        ]
    );
}
add_action( 'init', 'register_my_menus' );

function custom_image_sizes() {
    add_image_size('latestVideo', 904, 450, true); // Ajuste conforme necessário
    add_image_size('thumbnail_aside_videos_home', 200, 200, true); // Ajuste conforme necessário
}
add_action('after_setup_theme', 'custom_image_sizes');

// Incluir o arquivo ctp.php
require_once get_template_directory() . '/inc/ctp.php';


if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Configurações do Tema',
        'menu_title'    => 'Configurações do Tema',
        'menu_slug'     => 'configuracoes-do-tema',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}


// Remover o atributo type das tags script
function remove_type_attr($tag, $handle, $src) {
    return str_replace(" type='text/javascript'", '', $tag);
}
add_filter('script_loader_tag', 'remove_type_attr', 10, 3);

//Enable Field Excerpt in posts
add_action('init', function() {
    add_post_type_support('post', 'excerpt');
});
add_action('init', function() {
    add_post_type_support('page', 'excerpt');
});


/**
 * agencia_aids_hide_default_post_type
 * Remove o tipo de post padrão "post" do menu do WordPress
 * @since 1.0.0
 * @return void
 * @link https://developer.wordpress.org/reference/functions/remove_menu_page/
 * @link https://developer.wordpress.org/reference/hooks/admin_menu/
 * @link https://developer.wordpress.org/reference/functions/add_action/
 * @link https://developer.wordpress.org/reference/hooks/remove_menu_page/
 * @link https://developer.wordpress.org/reference/functions/remove_menu_page/
 */

function agencia_aids_hide_default_post_type() {
    remove_menu_page('edit.php'); // Oculta o menu de "Posts"
}
add_action('admin_menu', 'agencia_aids_hide_default_post_type');

// Ativa suporte ao ACF via PHP
add_action('acf/init', function () {

// Inclua os arquivos de campos personalizados aqui
require_once get_template_directory() . '/inc/acf-libray.php';
require_once get_template_directory() . '/inc/acf-services.php';
require_once get_template_directory() . '/inc/acf-ads.php';
require_once get_template_directory() . '/inc/acf-videos.php';
require_once get_template_directory() . '/inc/acf-article.php';
require_once get_template_directory() . '/inc/acf-notices-others.php';

/**
 * Query modifications
 * This file contains modifications to the main query for custom post types and taxonomies.
 * It hides expired ads and modifies the query for specific post types.
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://www.agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/reference/hooks/pre_get_posts/
 */
require_once get_template_directory() . '/inc/query-mods.php';

/**
 * Ads Cron
 * This file contains the cron job for managing ads expiration.
 * It schedules a daily event to check for expired ads and updates their status.
 * @package AgenciaAids     
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://www.agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/plugins/cron/
 */
require get_template_directory() . '/inc/ads-cron.php';


/**
 * Style and Scripts
 * This file contains the styles and scripts for the theme.
 * It enqueues the necessary CSS and JavaScript files for the theme.
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://www.agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/themes/basics/including-css-javascript/
 * @see https://developer.wordpress.org/themes/basics/conditional-tags/ 
 */
require get_template_directory() . '/inc/style-scripts.php';

});

/**
 * Performance Cleanup
 * This file contains performance optimizations for the WordPress theme.
 * It removes unnecessary scripts, styles, and features to improve site performance.
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva 
 * @link https://www.agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/reference/hooks/init/
 * @see https://developer.wordpress.org/reference/hooks/template_redirect/
 * @see https://developer.wordpress.org/reference/hooks/wp_head/
 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 */
require_once get_template_directory() . '/inc/performance-cleanup.php';

/**
 * Custom Excerpt Length
 * This file contains the custom excerpt length for the theme.
 * It modifies the default excerpt length to a custom value.
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://www.agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/reference/hooks/excerpt_length/
 * @see https://developer.wordpress.org/reference/functions/add_filter/
 */
require_once get_template_directory() . '/inc/custom-excerpt-lenght.php';