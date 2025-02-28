<?php


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
