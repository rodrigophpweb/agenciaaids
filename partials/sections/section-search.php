<search class="paddingContent">
    <div class="articles">

        <header>
            <h1>Resultados da Busca "<?php the_search_query(); ?>"</h1>
            <span><?php echo $wp_query->found_posts; ?> resultado(s) encontrado(s)</span>
        </header>

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article>
                <figure>
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>">
                    <?php else : ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/backdrop-ag-aids-compress-web.webp" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                </figure>

                <div class="content">
                    <?php the_title('<h2>', '</h2>')?>
                    <p><?php the_excerpt(); ?></p>
                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                    <?php
                    $categories = get_the_category();
                        if (!empty($categories)) {
                            echo '<mark class="category">' . esc_html($categories[0]->name) . '</mark>';
                        }
                    ?>
                    <?php if (get_next_post()) : ?>
                        <hr>
                    <?php endif; ?>
                </div>
            </article>
        <?php endwhile; else : ?>
            <p>Nenhum resultado encontrado.</p>
        <?php endif; ?>
    </div>
    <aside>
        <h2>Quer receber notícias e artigos como esses?</h2>
        <p>Assine nossa newsletter e fique por dentro das novidades sobre HIV e outras doenças.</p>
    </aside>    
</search>
<nav class="paginator">
    <?php echo paginate_links(array('total' => $wp_query->max_num_pages));?>
</nav>
