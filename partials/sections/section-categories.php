<section class="paddingContent headerCategory">
    <header>
        <!-- Name category -->
        <h1><?php single_cat_title(); ?></h1>
        <span><?php echo category_description(); ?></span>
    </header>
</section>

<?php
    $cat_id = get_queried_object_id();
    $paged  = max(1, get_query_var('paged'));

    $args = [
    'post_type'      => ['noticias'], // ajuste aqui seus CPTs
    'cat'            => $cat_id,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'ignore_sticky_posts' => true,
    ];

    $q = new WP_Query($args);
?>

<section id="cards-grid" class="cards paddingContent">
    <?php if ($q->have_posts()) : ?>
        <?php while ($q->have_posts()) : $q->the_post(); ?>
            <article class="card">
                <a href="<?php the_permalink(); ?>" class="card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                    <figure>
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail'); ?>
                        <?php else : ?>
                            <img src="https://agenciaaids.com.br/wp-content/themes/agenciaaids/assets/images/backdrop-ag-aids-compress-web.webp" alt="<?= esc_attr(get_the_title()); ?>">
                        <?php endif; ?>
                    </figure>

                    <div class="content">
                        <?php the_title('<h2 class="card-title">', '</h2>'); ?>

                        <?php
                            $post_cats = get_the_category();
                            if (!empty($post_cats)) :
                        ?>
                            <mark class="category"><?php echo esc_html($post_cats[0]->name); ?></mark>
                        <?php endif; ?>
                        <p><?php echo wp_trim_words(get_the_content(), 30, '...'); ?></p>
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
            'total'   => $q->max_num_pages,
            'current' => $paged,
        ]);
    ?>
</nav>
