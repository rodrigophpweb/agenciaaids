<section class="highlightVideos" itemscope itemtype="http://schema.org/VideoObject">
    <h2 itemprop="name">TV Agência Aids</h2>
    <?php
        function display_videos($args, $article_class, $thumbnail_size) {
            $query = new WP_Query($args);
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
    ?>
                    <article class="<?php echo esc_attr($article_class); ?>" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
                        <figure itemprop="thumbnailUrl">
                            <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail($thumbnail_size, ['alt' => esc_attr(get_the_title())]); ?>
                                <?php endif; ?>
                            </a>
                        </figure>
                        <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                            <h3 itemprop="name"><?php echo esc_html(get_the_title()); ?></h3>
                        </a>
                    </article>
    <?php
                endwhile; wp_reset_postdata();
            endif;
        }

        // Exibir o último post do custom post type 'videos'
        display_videos(
            [
                'post_type'         => 'videos',
                'posts_per_page'    => 1
            ],
            'latestVideoPost',
            'latestVideo'
        );
    ?>

    <aside class="aside_videos">
        <?php
            // Exibir os dois posts mais recentes, excluindo o último post
            display_videos(
                [
                    'post_type'         => 'videos',
                    'posts_per_page'    => 2,
                    'offset'            => 1
                ],
                'aside_videos_article',
                'thumbnail_aside_videos_home'
            );
        ?>
    </aside>
</section>