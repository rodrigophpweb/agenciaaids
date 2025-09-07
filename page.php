<?php
    get_header();
    get_template_part('partials/breadcrumb');
    echo '<div class="adsMobile paddingContent">';
        get_template_part('partials/sections/section-ads');
    echo '</div>';

    $templates = [
        'quem-somos'    => 'about',
        'todas-noticias' => 'news',
        'todos-artigos'  => 'articles',
        'palestras'     => 'lectures',
        'contato'       => 'contact',
        'biblioteca'    => 'library',
        'faq'           => 'faq',
        'servico'       => 'services'
    ];

if (is_page(array_keys($templates))) {
    $page_slug = get_post_field('post_name', get_post());
    if (array_key_exists($page_slug, $templates)) {
        get_template_part('partials/' . $templates[$page_slug]);
    }
    get_template_part('partials/partners');
} else {
    // Template padrão para outras páginas
    ?>
    <main>
        <h1><?php the_title(); ?></h1>
        <div>
            <?php the_content(); ?>
        </div>
    </main>
    <?php
}

get_footer();
