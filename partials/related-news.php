<?php
// Busca posts relacionados na mesma categoria
$categories = get_the_category();
$related_posts = null;

if ($categories) {
    $category_id = $categories[0]->term_id;

    $related_posts = new WP_Query([
        'cat'                 => $category_id,
        'post__not_in'        => [get_the_ID()],
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
        'post_status'         => 'publish'
    ]);
}

// Se não encontrou posts na categoria, busca os 3 posts mais recentes
if (!$related_posts || !$related_posts->have_posts()) {
    $related_posts = new WP_Query([
        'post__not_in'        => [get_the_ID()],
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
        'post_status'         => 'publish',
        'orderby'             => 'date',
        'order'               => 'DESC'
    ]);
}

if ($related_posts && $related_posts->have_posts()) : ?>
        <section class="related-posts paddingContent" aria-labelledby="related-posts-title">
            <h2 id="related-posts-title">Artigos Relacionados</h2>
            <div class="related-grid">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                    <article <?php post_class('related-card'); ?> itemscope itemtype="https://schema.org/Article">
                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                            <figure>
                                <?php                         
                                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                    $default_image = 'https://agenciaaids.com.br/wp-content/themes/agenciaaids/assets/images/backdrop-ag-aids-compress-web.webp';
                                    $image_url = $thumbnail_url ?: $default_image;
                                    $image_class = $thumbnail_url ? 'card-image' : 'card-image card-image-default';
                                ?>
                                <img src="<?=esc_url($image_url)?>" alt="<?=esc_attr(get_the_title())?>" class="<?=esc_attr($image_class)?>" loading="lazy" decoding="async">
                            </figure>
                            <?php the_title('<h3 itemprop="headline">', '</h3>')?>
                        </a>
                        <time datetime="<?php echo esc_attr(get_the_date('Y-m-d')); ?>" itemprop="datePublished">
                            <?php echo esc_html(get_the_date('d/m/Y')); ?>
                        </time>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <?php endif;
    wp_reset_postdata();
?>
