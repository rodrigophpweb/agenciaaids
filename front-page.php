<?php

    get_header();

    // Monta primeiro os dados
    $highlight_args = [
        'post_type'         => 'post',
        'posts_per_page'    => 1,
        'post__in'          => get_option('sticky_posts') ?: [], // ← protege se get_option('sticky_posts') for vazio
        'ignore_sticky_posts' => 1
    ];

    // Depois passa
    get_template_part('partials/highlight', null, $highlight_args);
    get_template_part('partials/doubt');
    get_template_part('partials/partners');

    // Seções principais com schema.org correto
    render_section('section-content-grid', [
        'section_id'        => 'recent-news',
        'title'             => 'Notícias',
        'subtitle'          => 'Recentes',
        'post_type'         => 'noticia',
        'highlight'         => 1,
        'columns'           => 3,
        'itemtype'          => 'https://schema.org/NewsArticle',
        'itemprop_title'    => 'headline',
        'itemprop_image'    => 'image',
        'itemprop_excerpt'  => 'description',
        'itemprop_date'     => 'datePublished',
    ]);

    render_section('section-content-grid', [
        'section_id'        => 'articles',
        'title'             => 'Artigos',
        'subtitle'          => 'Novos',
        'post_type'         => 'artigo',
        'highlight'         => 1,
        'columns'           => 3,
        'itemtype'          => 'https://schema.org/Article',
        'itemprop_title'    => 'headline',
        'itemprop_image'    => 'image',
        'itemprop_excerpt'  => 'description',
        'itemprop_date'     => 'datePublished',
    ]);

    render_section('section-content-grid', [
        'section_id'        => 'tv-agencia-aids',
        'title'             => 'TV Agência Aids',
        'post_type'         => 'videos',
        'highlight'         => 1,
        'columns'           => 3,
        'itemtype'          => 'https://schema.org/VideoObject',
        'itemprop_title'    => 'name',
        'itemprop_image'    => 'thumbnailUrl',
        'itemprop_excerpt'  => 'description',
        'itemprop_date'     => 'uploadDate',
    ]);


    get_footer();