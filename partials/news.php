<section class="paddingContent headerCategory">
    <header>
        <!-- Name category -->
        <?php the_title('<h1>','</h1>');?>
        <span><?php the_content();?></span>

        <div class="filters">
            <select name="year_filter" id="year_filter">
                <option value="">Todos os anos</option>
                <?php
                    $years = range(date('Y'), 2006);
                    foreach ($years as $year) {
                        echo '<option value="' . esc_attr($year) . '">' . esc_html($year) . '</option>';
                    }
                ?>
            </select>

            <select name="month_filter" id="month_filter">
                <option value="">Todos os meses</option>
                <?php
                    $months_name = [
                        1 => 'Janeiro',
                        2 => 'Fevereiro',
                        3 => 'Março',
                        4 => 'Abril',
                        5 => 'Maio',
                        6 => 'Junho',
                        7 => 'Julho',
                        8 => 'Agosto',
                        9 => 'Setembro',
                        10 => 'Outubro',
                        11 => 'Novembro',
                        12 => 'Dezembro'
                    ];
                    foreach ($months_name as $month => $name) {
                        echo '<option value="' . esc_attr($month) . '">' . esc_html($name) . '</option>';
                    }
                ?>
            </select>

            <select name="category_filter" id="category_filter">
                <option value="">Todas as categorias</option>
                <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                    }
                ?>
            </select>
            
            <!-- Campo oculto para armazenar a categoria atual -->
            <input type="hidden" id="current_category" value="<?php echo esc_attr(get_queried_object_id()); ?>">
        </div>
    </header>
</section>

<?php
    $cat_id = get_queried_object_id();
    $paged  = max(1, get_query_var('paged'));

    $args = [
        'post_type'      => ['noticias'], 
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
