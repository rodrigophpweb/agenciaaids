<section class="library">
    <h1>Todos os livros</h1>
    <p><?php the_exerpt()?></p>
    <article class="books">
        <?php
        $args = array(
            'post_type' => 'livros',
            'posts_per_page' => -1
        );
        $books = new WP_Query($args);
        if ($books->have_posts()) :
            while ($books->have_posts()) :
                $books->the_post();
                ?>

                <figure>
                    <?php the_post_thumbnail('thumbnail-book-cover'); ?>
                    <figcaption>
                        <h2><?php the_title(); ?></h2>
                        <span class="author"><?=get_field('library-author')?></span>
                        <span class="editor"><?=get_field('library-editora')?></span>
                        <span class="excerpt"><?php the_excerpt()?></span>
                        <a href="<?=esc_html(get_field('library_publisher'))?>" target="_blank" rel="">Visite o site da editora</a>
                    </figcaption>
                </figure>

                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </article>
</section>