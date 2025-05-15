<section class="highlightPosts paddingContent">
    <header>
        <span class="subtitle">Recentes</span>
        <h2>Notícias</h2>
    </header>
    <?php

    // Protege o arquivo de acesso direto
    if ( ! defined('ABSPATH') ) {
        exit;
    }
    
    // Query para pegar o post sticky ou o último post
    $sticky = get_option('sticky_posts');
    $args = array(
        'posts_per_page' => 1,
        'post__in' => $sticky,
        'ignore_sticky_posts' => 1
    );
    if (empty($sticky)) {
        $args['post__in'] = array();
        $args['posts_per_page'] = 1;
    }
    $query = new WP_Query($args);
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
        <article class="postSticky" itemscope itemtype="http://schema.org/Article">
            <figure>
                <a href="<?php the_permalink(); ?>" title="Saiba mais sobre: <?php the_title(); ?>">
                    <?php the_post_thumbnail('posts_highlight', array('alt' => get_the_title(), 'itemprop' => 'image')); ?>
                </a>
            </figure>
            <h3 itemprop="headline"><a href="<?php the_permalink(); ?>" title="Saiba mais sobre: <?php the_title(); ?>"><?php the_title(); ?></a></h3>
            <p itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            <time datetime="<?php echo get_the_modified_date('c'); ?>" itemprop="dateModified"><?php echo get_the_modified_date(); ?></time>
        </article>
    <?php endwhile; endif; wp_reset_postdata(); ?>

    <aside class="aside_notices">
        <?php
        // Query para pegar os últimos posts exceto o último post sticky ou o último post
        $args = array(
            'posts_per_page' => 2,
            'post__not_in' => $sticky,
            'ignore_sticky_posts' => 1,
            'offset' => 1
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
            <article class="aide_notices_article" itemscope itemtype="http://schema.org/Article">
                <figure>
                    <a href="<?php the_permalink(); ?>" title="Saiba mais sobre: <?php the_title(); ?>"><?php the_post_thumbnail('thumbnail', array('alt' => get_the_title(), 'itemprop' => 'image')); ?></a>
                </figure>
                <header>
                    <h3 itemprop="headline"><a href="<?php the_permalink(); ?>" title="Saiba mais sobre: <?php the_title(); ?>"><?php the_title(); ?></a></h3>
                    <p itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                    <time datetime="<?php echo get_the_modified_date('c'); ?>" itemprop="dateModified"><?php echo get_the_modified_date(); ?></time>
                </header>
            </article>
        <?php endwhile; endif; wp_reset_postdata(); ?>
    </aside>
</section>