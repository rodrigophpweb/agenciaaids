<section class="content-library paddingContent" itemscope itemtype="http://schema.org/Library">
    <h1>Todos os livros</h1>
    <p><?php the_excerpt(); ?></p>
    <article class="books" id="books-container">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = [
            'post_type'         => 'bibliotecas',
            'posts_per_page'    => 9,
            'paged'             => $paged
        ];
        $books = new WP_Query($args);
        if ($books->have_posts()) :
            while ($books->have_posts()) :
                $books->the_post();
                ?>

                <figure itemscope itemtype="http://schema.org/Book">
                    <?php the_post_thumbnail('thumbnail-book-cover', ['itemprop' => 'image']); ?>
                    <figcaption>
                        <h2 itemprop="name"><?php the_title(); ?></h2>
                        <span class="author" itemprop="author"><?= get_field('autor') ?></span>
                        <span class="editor" itemprop="publisher"><?= get_field('editora') ?></span>
                        <span class="excerpt" itemprop="description"><?php the_excerpt() ?></span>
                        <a href="<?= esc_html(get_field('link')) ?>" target="_blank" rel="noopener noreferrer" itemprop="url">Visite o site da editora</a>
                    </figcaption>
                </figure>

                <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </article>
    <nav class="pagination" itemscope itemtype="http://schema.org/SiteNavigationElement">
        <?php
            echo paginate_links([
                'total' => $books->max_num_pages,
                'current' => $paged,
                'format' => '?paged=%#%',
                'prev_text' => __('&laquo; Anterior'),
                'next_text' => __('Próximo &raquo;'),
                'type' => 'list',
                
            ]);
        ?>
    </nav>    
</section>