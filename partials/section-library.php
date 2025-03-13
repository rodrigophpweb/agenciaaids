<section class="library" itemscope itemtype="http://schema.org/Library">
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

                <figure itemscope itemtype="http://schema.org/Book">
                    <?php the_post_thumbnail('thumbnail-book-cover', ['itemprop' => 'image']); ?>
                    <figcaption>
                        <h2 itemprop="name"><?php the_title(); ?></h2>
                        <span class="author" itemprop="author"><?=get_field('library-author')?></span>
                        <span class="editor" itemprop="publisher"><?=get_field('library-editora')?></span>
                        <span class="excerpt" itemprop="description"><?php the_excerpt()?></span>
                        <a href="<?=esc_html(get_field('library_publisher'))?>" target="_blank" rel="noopener noreferrer" itemprop="url">Visite o site da editora</a>
                    </figcaption>
                </figure>

                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </article>
</section>