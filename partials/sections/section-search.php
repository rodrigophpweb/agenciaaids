<search class="paddingContent">
    <div class="articles">

        <header>
            <h1>Resultados da Busca "<?php the_search_query(); ?>"</h1>
            <mark class="resultSearch"><?php echo $wp_query->found_posts; ?> resultado(s) encontrado(s)</mark class="resultSearch">
        </header>

        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                    <figure>
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail'); ?>
                        <?php else : ?>
                            <!-- <img src="https://agenciaaids.com.br/wp-content/themes/agenciaaids/assets/images/backdrop-ag-aids-compress-web.webp" alt="<?= esc_attr(get_the_title()); ?>"> -->
                            <?php echo aa_get_safe_thumbnail_html( get_the_ID(), 'medium', ['class' => 'thumb'] );?>
                        <?php endif; ?>
                    </figure>

                    <div class="content">
                        <?php the_title('<h2>', '</h2>')?>
                        <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                echo '<mark class="category">' . esc_html($categories[0]->name) . '</mark>';
                            }
                        ?>
                        <p><?php echo wp_trim_words(get_the_content(), 30, '...'); ?></p>
                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                    </div>
                </a>
            </article>
        <?php endwhile; else : ?>
            <p>Nenhum resultado encontrado.</p>
        <?php endif; ?>
    </div>
    
    <!--<aside>
        <h2>Quer receber notícias e artigos como esses?</h2>
        <p>Assine nossa newsletter e fique por dentro das novidades sobre HIV e outras doenças.</p>
    </aside>-->    
</search>
<nav class="paginator">
    <?php echo paginate_links(array('total' => $wp_query->max_num_pages));?>
</nav>
