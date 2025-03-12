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


function custom_breadcrumbs() {
    // Configurações
    $separator = ' » ';
    $home_title = 'Início';

    // Obter o objeto global do WordPress
    global $post;

    // Início do breadcrumb
    echo '<nav aria-label="breadcrumb">';
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
    } elseif (is_page() && $post->post_parent) {
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
        foreach ($breadcrumbs as $breadcrumb) {
            echo $breadcrumb;
        }
        echo '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="' . (count($breadcrumbs) + 2) . '" />';
        echo '</li>';
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

function load_custom_css() {
    $css_files = [
        'acessibilidade'            => 'acessibility.css',
        'artigos'                   => 'articles.css',
        'contato'                   => 'contact.css',
        'dicionario'                => 'dictionary.css',
        'faq'                       => 'faq.css',
        'home'                      => 'home.css',
        'palestras'                 => 'lectures.css',
        'biblioteca'                => 'library.css',
        'noticias'                  => 'news.css',
        'politica-de-privacidade'   => 'policePrivacy.css',
        'quem-somos'                => 'whoWeAre.css',
    ];

    if (is_page()) {
        global $post;
        $slug = $post->post_name;

        if (is_front_page()) {
            wp_enqueue_style('home', get_template_directory_uri() . '/assets/css/pages/home.css');
        } elseif (array_key_exists($slug, $css_files)) {
            wp_enqueue_style($slug, get_template_directory_uri() . '/assets/css/pages/' . $css_files[$slug]);
        }
    }
}
add_action('wp_enqueue_scripts', 'load_custom_css');
