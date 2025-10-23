<?php 
function agenciaaids_filter_dictionary() {
    // Verificar nonce para segurança
    if (!wp_verify_nonce($_POST['nonce'], 'ajax_nonce')) {
        wp_send_json_error('Erro de segurança');
    }

    $letter = isset($_POST['letter']) ? strtoupper(sanitize_text_field($_POST['letter'])) : '';
    
    // Validar letra
    $letters = range('A', 'Z');
    if (!in_array($letter, $letters, true)) {
        wp_send_json_error('Letra inválida');
    }

    // Query
    $args = [
        'post_type'      => 'dicionarios',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'meta_query'     => [],
    ];

    // Filtro por letra usando posts_where
    add_filter('posts_where', function ($where, \WP_Query $query) use ($letter) {
        global $wpdb;
        if ($query->get('filter_by_letter')) {
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", $letter . '%');
        }
        return $where;
    }, 10, 2);

    $args['filter_by_letter'] = true;
    
    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) :
        $set_itemid = esc_url(get_post_type_archive_link('dicionario'));
        
        while ($query->have_posts()) : $query->the_post();
            $title = get_the_title();
            $term_anchor = sanitize_title($title);
            $first_letter = strtoupper(substr(remove_accents($title), 0, 1));
            ?>
            <details class="dictionary-item" id="<?php echo esc_attr($term_anchor); ?>" data-letter="<?php echo esc_attr($first_letter); ?>" itemscope itemprop="hasDefinedTerm" itemtype="https://schema.org/DefinedTerm">
                <summary class="question" role="button" aria-expanded="false" itemprop="name">
                    <?php echo esc_html($title); ?>
                </summary>
                <article class="answer" role="region" aria-label="<?php echo esc_attr($title); ?>" itemprop="description">
                    <?php the_content(); ?>
                </article>
                <link itemprop="inDefinedTermSet" href="<?php echo $set_itemid; ?>" />
                <meta itemprop="url" content="<?php echo esc_url(get_permalink()); ?>" />
            </details>
            <?php
        endwhile;
    else :
        echo '<p role="status">Nenhum termo encontrado para a letra ' . esc_html($letter) . '.</p>';
    endif;
    
    wp_reset_postdata();
    
    $content = ob_get_clean();
    
    wp_send_json_success([
        'content' => $content,
        'letter' => $letter,
        'total_posts' => $query->found_posts
    ]);
}

add_action('wp_ajax_filter_dictionary', 'agenciaaids_filter_dictionary');
add_action('wp_ajax_nopriv_filter_dictionary', 'agenciaaids_filter_dictionary');