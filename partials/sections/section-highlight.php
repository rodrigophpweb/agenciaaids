<section id="articles" class="section-articles paddingContent" aria-labelledby="articles-title">
    <h2 id="articles-title" class="section-title">Artigos</h2>
    <p class="section-subtitle">Novos</p>
    
    <!-- WP Query, trazer o último artigo do ctp artigos-->
    <div class="posts-grid">
        <?php
        $args = [
            'post_type'      => 'artigos',
            'posts_per_page' => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            
            'paged'          => get_query_var('paged') ?: 1,
        ];
        $query = new WP_Query($args);

        if ($query->have_posts()):
            while ($query->have_posts()): $query->the_post();
        ?>
            <div class="post-grid-one-columns">
                <article class="featured-post" itemscope="" itemtype="https://schema.org/Article">
                    <a href="<?php esc_url(get_the_permalink())?>" itemprop="url">
                        <figure itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" itemprop="url" loading="lazy">
                        </figure>
                        
                        <div class="post-info">
                            <?php the_title('<h3 itemprop="headline">','<h3>')?>
                            <p itemprop="description"><?php the_excerpt(); ?></p>
                            <time datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date(); ?></time>
                            <meta itemprop="dateModified" content="<?php echo get_the_modified_date('c'); ?>">
                            <meta itemprop="author" content="<?php echo get_the_author(); ?>">
                        </div>
                    </a>
                </article>
                <div class="secondary-posts">
                </div>
            </div>

        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</section>