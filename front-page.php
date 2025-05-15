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
    get_template_part('partials/highlight','posts');
    get_template_part('partials/highlight','articles');
    get_template_part('partials/highlight','videos');
    get_footer();