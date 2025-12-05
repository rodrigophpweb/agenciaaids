<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<section class="paddingContent headerCategory">
    <header>
        <!-- Name category -->
        <?php the_title('<h1>', '</h1>', true); ?>
        <span class="theContent"><?php the_content(); ?></span>

        <div class="filters">
            <select name="year_filter" id="year_filter">
                <option value="">Todos os anos</option>
                <?php
                    $current_year = (int) date('Y');
                    $years = range($current_year, 2025);
                    foreach ($years as $year) {
                        $year = (int) $year; // Ensure integer
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
                    $categories = get_categories([
                        'hide_empty' => true,
                        'orderby' => 'name',
                        'order' => 'ASC'
                    ]);
                    if (!empty($categories) && !is_wp_error($categories)) {
                        foreach ($categories as $category) {
                            if (isset($category->term_id, $category->name)) {
                                echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                            }
                        }
                    }
                ?>
            </select>
            
            <!-- Campo oculto para página de todas as notícias (sem categoria específica) -->
            <input type="hidden" id="current_category" value="">
        </div>
    </header>
</section>

<?php
    // Sanitize pagination value
    $paged = absint(get_query_var('paged'));
    $paged = max(1, $paged);

    $args = [
        'post_type'              => 'artigos', 
        'paged'                  => $paged,
        'post_status'            => 'publish',
        'posts_per_page'         => 20,
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => false, // Need for pagination
        'update_post_meta_cache' => false, // Improve performance
        'update_post_term_cache' => true, // Need for categories
    ];

    $q = new WP_Query($args);
?>

<section id="cards-grid" class="cards paddingContent">
    <?php if ($q->have_posts()) : ?>
        <?php while ($q->have_posts()) : $q->the_post(); ?>
            <article class="card">
                <a href="<?php echo esc_url(get_the_permalink()); ?>" class="card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                    <figure>
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail', [
                                'loading' => 'lazy',
                                'alt' => esc_attr(get_the_title())
                            ]); ?>
                        <?php else : ?>
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/backdrop-ag-aids-compress-web.webp'); ?>" 
                                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                                 loading="lazy">
                        <?php endif; ?>
                    </figure>

                    <div class="content">
                        <?php the_title('<h2 class="card-title">', '</h2>', true); ?>

                        <?php
                            $post_cats = get_the_category();
                            if (!empty($post_cats) && isset($post_cats[0]->name)) :
                        ?>
                            <mark class="category"><?php echo esc_html($post_cats[0]->name); ?></mark>
                        <?php endif; ?>
                        <p><?php 
                            $content = get_the_content();
                            $content = wp_strip_all_tags($content);
                            $content = trim(str_replace(['&nbsp;', ' '], ' ', $content));
                            echo esc_html(wp_trim_words($content, 30, '...'));
                        ?></p>
                        <time class="card-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </div>
                </a>
            </article>
       <?php endwhile; ?>

    
  <?php else : ?>
    <p>Nenhum conteúdo encontrado nesta categoria.</p>
  <?php endif; wp_reset_postdata(); ?>
</section>

<nav class="paginator" aria-label="Navegação de páginas">
    <?php
        $pagination = paginate_links([
            'total'     => $q->max_num_pages,
            'current'   => $paged,
            'prev_text' => esc_html__('« Anterior', 'agenciaaids'),
            'next_text' => esc_html__('Próxima »', 'agenciaaids'),
            'type'      => 'plain',
            'add_args'  => false, // Prevent XSS in URL parameters
        ]);
        echo wp_kses_post($pagination);
    ?>
</nav>
