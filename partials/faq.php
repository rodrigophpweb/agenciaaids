<article itemscope itemtype="https://schema.org/FAQPage" class="paddingContent">
    <header class="faq">
        <?php the_title('<span class="subtitle"></span>', '</span>'); ?>
        <h1><?php the_field('faq_title'); ?></h1>

        <nav class="faq_nav">
            <?php
                $assuntos = get_terms([
                    'taxonomy' => 'assuntos',
                    'hide_empty' => false,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ]);
                
                if ($assuntos && !is_wp_error($assuntos)) :
                    foreach ($assuntos as $assunto) : ?>
                        <button class="category-link" data-category-id="<?= $assunto->term_id ?>" data-category-slug="<?= $assunto->slug ?>">
                            <?= $assunto->name ?>
                        </button>
                    <?php endforeach;
                endif;
            ?>
        </nav>
    </header>

    <section itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" class="question paddingContent" id="faq-content">
        <?php
            // Por padrão, mostra todas as respostas ou do primeiro assunto
            $assuntos = get_terms([
                'taxonomy' => 'assuntos',
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC'
            ]);
            
            $default_term_id = !empty($assuntos) ? $assuntos[0]->term_id : null;
            
            $args = array(
                'post_type' => 'respostas',
                'posts_per_page' => -1,
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'status',
                        'compare' => 'NOT EXISTS'
                    ),
                    array(
                        'key' => 'status',
                        'value' => 'published',
                        'compare' => '='
                    )
                )
            );
            
            if ($default_term_id) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'assuntos',
                        'field'    => 'term_id',
                        'terms'    => $default_term_id,
                    ),
                );
            }
            
            $posts = get_posts($args);
            if ($posts) {
                foreach ($posts as $post) {
                    setup_postdata($post);
                    ?>
                    <details itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <?php the_title('<summary itemprop="name">', '</summary>')?>
                        <article itemprop="text">
                            <?php the_content(); ?>
                        </article>
                    </details>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo '<p>Nenhuma resposta encontrada para este assunto.</p>';
            }
        ?>
    </section>
</article>