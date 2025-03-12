<section class="highlightVideos" itemscope itemtype="http://schema.org/VideoObject">
    <h2 itemprop="name">TV Agência Aids</h2>
    <?php
    // Query para buscar o último post do custom post type 'videos'
    $args = array(
        'post_type' => 'videos',
        'posts_per_page' => 1
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
    ?>
        <article class="latestVideoPost" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
            <figure itemprop="thumbnailUrl">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('latestVideo', ['alt' => get_the_title()]); ?>
                    <?php endif; ?>
                </a>
            </figure>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                <h3 itemprop="name"><?php the_title(); ?></h3>
            </a>
        </article>
    <?php
        endwhile;
        wp_reset_postdata();
    endif;
    ?>

    <aside class="aside_videos">
        <?php
        // Query para buscar os dois posts mais recentes, excluindo o último post
        $args = array(
            'post_type' => 'videos',
            'posts_per_page' => 2,
            'offset' => 1
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
        ?>
            <article class="aside_videos_article" itemprop="video" itemscope itemtype="http://schema.org/VideoObject">
                <figure itemprop="thumbnailUrl">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail_aside_videos_home', ['alt' => get_the_title()]); ?>
                        <?php endif; ?>
                    </a>
                </figure>
                <header>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                        <h3 itemprop="name"><?php the_title(); ?></h3>
                    </a>
                </header>
            </article>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </aside>
</section>