<section class="paddingContent headerCategory">
    <header>
        <!-- Name category -->
        <?php the_title('<h1>','</h1>', true);?>
        <span class="theContent"><?php the_content();?></span>
    </header>
</section>

<?php
    $paged = max(1, get_query_var('paged'));

    $args = [
        'post_type'             => 'videos', 
        'paged'                 => $paged,
        'post_status'           => 'publish',
        'posts_per_page'        => 20,
        'ignore_sticky_posts'   => true,
    ];

    $q = new WP_Query($args);
?>

<section id="cards-grid" class="cards paddingContent">
    <?php if ($q->have_posts()) : ?>
        <?php while ($q->have_posts()) : $q->the_post(); ?>
            <article class="card">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                    <figure>
                        <?php
                            $video_url = get_field('embed', get_the_ID());
                            $thumb_url = '';

                            if ($video_url && function_exists('get_youtube_thumbnail')) {
                                $thumb_url = get_youtube_thumbnail($video_url);
                            }

                            if (empty($thumb_url) && has_post_thumbnail()) {
                                $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                            }

                            // Fallback para imagem padrão se não houver thumbnail
                            if (empty($thumb_url)) {
                                $thumb_url = get_template_directory_uri() . '/assets/images/backdrop-ag-aids-compress-web.webp';
                            }
                        ?>
                        <img src="<?= esc_url($thumb_url) ?>" alt="<?= esc_attr(get_the_title()); ?>" loading="lazy">
                    </figure>

                    <div class="content">
                        <?php the_title('<h2 class="card-title">', '</h2>', true); ?>

                        <?php
                            $post_cats = get_the_category();
                            if (!empty($post_cats)) :
                        ?>
                            <mark class="category"><?php echo esc_html($post_cats[0]->name); ?></mark>
                        <?php endif; ?>
                        <p><?php echo esc_html(wp_trim_words(trim(str_replace(['&nbsp;', ' '], ' ', get_the_content())), 30, '...')); ?></p>
                        <time class="card-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </div>
                </a>
            </article>
       <?php endwhile; ?>

    
  <?php else : ?>
    <p>Nenhum conteúdo encontrado nesta categoria.</p>
  <?php endif; wp_reset_postdata(); ?>
</section>

<nav class="paginator">
    <?php
        echo paginate_links([
            'total'     => $q->max_num_pages,
            'current'   => $paged,
            'prev_text' => '« Anterior',
            'next_text' => 'Próxima »',
            'type'      => 'plain',
        ]);
    ?>
</nav>
