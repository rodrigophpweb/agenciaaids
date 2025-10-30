<?php 
function agenciaaids_filter_posts() {
    // Verificar nonce para segurança
    if (!wp_verify_nonce($_POST['nonce'], 'ajax_nonce')) {
        wp_die('Erro de segurança');
    }

    $year = sanitize_text_field($_POST['year']);
    $month = sanitize_text_field($_POST['month']);
    $category = intval($_POST['category']);
    $current_category = intval($_POST['current_category']);
    $paged = intval($_POST['paged']) ?: 1;

    // Construir argumentos da query
    $args = [
        'post_type'      => ['post', 'noticias'],
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'paged'          => $paged,
        'ignore_sticky_posts' => true,
    ];

    // Filtros por data
    $date_query = [];
    if ($year) {
        $date_query['year'] = $year;
    }
    if ($month) {
        $date_query['month'] = $month;
    }
    if (!empty($date_query)) {
        $args['date_query'] = [$date_query];
    }

    // Filtro por categoria
    if ($category) {
        $args['cat'] = $category;
    } elseif ($current_category) {
        // Se não foi especificada categoria no filtro mas existe categoria atual, usar a categoria atual
        $args['cat'] = $current_category;
    }
    // Se nem category nem current_category existem, busca todas as categorias

    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            ?>
            <article class="card">
                <a href="<?php the_permalink(); ?>" class="card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                    <figure>
                        <?php                         
                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            $default_image = 'https://agenciaaids.com.br/wp-content/themes/agenciaaids/assets/images/backdrop-ag-aids-compress-web.webp';
                            $image_url = $thumbnail_url ?: $default_image;
                            $image_class = $thumbnail_url ? 'card-image' : 'card-image card-image-default';
                        ?>
                        <img 
                            src="<?php echo esc_url($image_url); ?>" 
                            alt="<?php echo esc_attr(get_the_title()); ?>"
                            class="<?php echo esc_attr($image_class); ?>"
                            loading="lazy"
                            decoding="async"
                        >
                    </figure>

                    <div class="content">
                        <?php the_title('<h2 class="card-title">', '</h2>'); ?>

                        <?php
                            $post_cats = get_the_category();
                            if (!empty($post_cats)) :
                        ?>
                            <mark class="category"><?php echo esc_html($post_cats[0]->name); ?></mark>
                        <?php endif; ?>
                        <p><?php echo wp_trim_words(trim(str_replace(['&nbsp;', ' '], ' ', get_the_content())), 30, '...'); ?></p>
                        <time class="card-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </div>
                </a>
            </article>
            <?php
        endwhile;
    else :
        echo '<p>Nenhum conteúdo encontrado com os filtros selecionados.</p>';
    endif;
    
    $content = ob_get_clean();
    
    // Preparar paginação
    $pagination = '';
    if ($query->max_num_pages > 1) {
        ob_start();
        
        // Construir URL base para paginação
        $current_url = home_url($_SERVER['REQUEST_URI']);
        $base_url = strtok($current_url, '?'); // Remove query string se existir
        
        echo paginate_links([
            'total'   => $query->max_num_pages,
            'current' => $paged,
            'base'    => $base_url . '%_%',
            'format'  => '?paged=%#%',
            'prev_text' => '« Anterior',
            'next_text' => 'Próxima »',
            'type'      => 'plain',
        ]);
        $pagination = ob_get_clean();
    }
    
    wp_reset_postdata();
    
    wp_send_json_success([
        'content' => $content,
        'pagination' => $pagination,
        'total_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages
    ]);
}

add_action('wp_ajax_filter_posts', 'agenciaaids_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'agenciaaids_filter_posts');