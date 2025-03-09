<section class="highlightArticles" itemscope itemtype="https://schema.org/Blog">
    <?php
        $latest_article = new WP_Query(array(
            'post_type'         => 'artigos',
            'posts_per_page'    => 1,
            'orderby'           => 'date',
            'order'             => 'DESC'
        ));
        if ($latest_article->have_posts()) :
            $latest_article->the_post();
            $author_article = get_field('authorArticle');
            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    ?>
            <article itemscope itemtype="https://schema.org/BlogPosting">
                <figure itemscope itemtype="https://schema.org/ImageObject">
                    <img src="<?= esc_url($featured_image); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image">
                    <figcaption>Por <strong itemprop="author"><?= esc_html($author_article); ?></strong></figcaption>
                </figure>
                <header>
                    <h3 itemprop="headline">Artigos</h3>
                    <?php the_title('<h4 itemprop="name">', '<h4>')?>
                    <p itemprop="description"><?php the_excerpt()?></p>
                    <a href="<?php the_permalink()?>" itemprop="url" title="Saiba mais sobre - <?php the_title_attribute()?>">Saiba mais</a>
                </header>
            </article>
    <?php
            wp_reset_postdata();
        endif;
    ?>
</section>