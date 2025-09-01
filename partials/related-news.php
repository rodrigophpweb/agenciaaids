<?php
$categories = get_the_category();
if ($categories) {
    $category_id = $categories[0]->term_id;

    $related_posts = new WP_Query([
        'cat'                 => $category_id,
        'post__not_in'        => [get_the_ID()],
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => true,
    ]);

    if ($related_posts->have_posts()) : ?>
        <section class="related-posts paddingContent" aria-labelledby="related-posts-title">
            <h2 id="related-posts-title">Artigos Relacionados</h2>
            <div class="related-grid">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                    <article <?php post_class('related-card'); ?> itemscope itemtype="https://schema.org/Article">
                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                            <?php if (has_post_thumbnail()) : ?>
                                <figure>
                                    <?php the_post_thumbnail('medium', ['itemprop' => 'image']); ?>
                                </figure>
                            <?php endif; ?>
                            <h3 itemprop="headline"><?php the_title(); ?></h3>
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
}
?>
