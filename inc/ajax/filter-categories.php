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
    
    // Processar post_type (pode ser string única ou lista separada por vírgula)
    $post_type_input = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post,noticias';
    $post_types = array_map('trim', explode(',', $post_type_input));
    $post_types = array_filter($post_types); // Remove valores vazios
    
    // Se tiver apenas um tipo, usar string; se tiver múltiplos, usar array
    $post_type = count($post_types) === 1 ? $post_types[0] : $post_types;

    // Construir argumentos da query
    $args = [
        'posts_per_page'        => 20,
        'post_status'           => 'publish',
        'post_type'             => $post_type,
        'paged'                 => $paged,
        'ignore_sticky_posts'   => true,
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
                <a href="<?php echo esc_url(get_the_permalink()); ?>" class="card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                    <figure>
                        <?php                         
                            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            $default_image = get_template_directory_uri() . '/assets/images/backdrop-ag-aids-compress-web.webp';
                            $image_url = $thumbnail_url ?: $default_image;
                            $image_class = $thumbnail_url ? 'card-image' : 'card-image card-image-default';
                        ?>
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                             class="<?php echo esc_attr($image_class); ?>" 
                             loading="lazy" 
                             decoding="async">
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
        
        $pagination_args = [
            'total'     => $query->max_num_pages,
            'current'   => $paged,
            'prev_text' => esc_html__('« Anterior', 'agenciaaids'),
            'next_text' => esc_html__('Próxima »', 'agenciaaids'),
            'type'      => 'plain',
            'add_args'  => false, // Prevent XSS in URL parameters
        ];
        
        echo wp_kses_post(paginate_links($pagination_args));
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