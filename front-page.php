<?php

    get_header();
    get_template_part('partials/highlight', null, [
        'post_type' => 'post',
        'posts_per_page' => 1,
        'post__in' => get_option('sticky_posts'),
        'ignore_sticky_posts' => 1
    ]);
    get_template_part('partials/doubt');
    get_template_part('partials/partners');
    get_template_part('partials/highlight','posts');
    get_template_part('partials/highlight','articles');
    get_template_part('partials/highlight','videos');
    get_footer();