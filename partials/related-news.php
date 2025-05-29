<section class="relatedNews" itemscope itemtype="https://schema.org/CollectionPage">
    <h2 itemprop="name">Notícias Relacionadas</h2>
    <?php
        $categories = get_the_category();
    
        if ($categories) {
            $category_id = $categories[0]->term_id;

            $related_args = [
                'category__in'   => [$category_id], 
                'post__not_in'   => [get_the_ID()], 
                'posts_per_page' => 3, 
                'orderby'        => 'date', 
                'order'          => 'DESC'
            ];

            $related_query = new WP_Query($related_args);

            if ($related_query->have_posts()): 
                while ($related_query->have_posts()): $related_query->the_post(); 
                    $post_category = get_the_category()[0] ?? null;
                    $post_image = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: 'caminho/para/imagem_padrao.jpg';
                    $post_date = get_the_date('Y-m-d\TH:i:sP');
                    $post_date_human = get_the_date('d/m/Y - H\hi');
    ?>

                    <article itemscope itemtype="https://schema.org/NewsArticle">
                        <figure>
                            <img src="<?= esc_url($post_image); ?>" alt="<?= esc_attr(get_the_title()); ?>" itemprop="image">
                        </figure>                        
                        <mark itemprop="articleSection"><?= esc_html($post_category->name); ?></mark>
                        <h3 itemprop="headline"><?= esc_html(get_the_title()); ?></h3>
                        <p itemprop="description"><?= esc_html(get_the_excerpt()); ?></p>
                        <time datetime="<?= esc_attr($post_date); ?>" itemprop="datePublished"><?= esc_html($post_date_human); ?></time>
                        <a href="<?= esc_url(get_permalink()); ?>" title="Leia mais sobre <?= esc_attr(get_the_title()); ?>" itemprop="url">Saiba mais</a>
                    </article>

                <?php endwhile; wp_reset_postdata();
            endif;
        } 
    ?>
    <a href="<?= esc_url(site_url('noticias')); ?>" title="Leia mais notícias" itemprop="mainEntityOfPage">Leia mais</a>
</section>
