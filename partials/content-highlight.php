<article class="<?php echo esc_attr($args['article_class']); ?>" itemscope itemtype="http://schema.org/Article">
    <figure>
        <a href="<?php the_permalink(); ?>" title="Saiba mais sobre: <?php the_title_attribute(); ?>">
            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail($args['thumb_size'], [
                    'alt' => get_the_title(),
                    'itemprop' => 'image',
                    'loading' => 'lazy'
                ]);
            }
            ?>
        </a>
    </figure>
    <header>
        <h3 itemprop="headline">
            <a href="<?php the_permalink(); ?>" title="Saiba mais sobre: <?php the_title_attribute(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
        <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                foreach ( $categories as $category ) {
                    echo '<mark>' . esc_html( $category->name ) . '</mark> ';
                }
            }
        ?>
        <p itemprop="description"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
        <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" itemprop="dateModified">
            <?php echo get_the_modified_date(); ?>
        </time>
    </header>
</article>
