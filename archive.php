<?php
get_header();

$archive_title = get_the_archive_title();
$archive_description = get_the_archive_description();

$args = [
    'section_id'    => 'arquivo-' . get_post_type(),
    'class'         => 'paddingContent',
    'title'         => $archive_title,
    'subtitle'      => $archive_description,
    'post_type'     => get_post_type(),
    'highlight'     => 1,
    'columns'       => 3,
    'query'         => $wp_query, // usa a query padrão da página de arquivo
];

get_template_part('partials/section/section-content-grid', null, $args);

get_footer();
