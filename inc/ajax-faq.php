<?php
/**
 * AJAX handler para buscar respostas por assunto
 */

// Handler para usuários logados
add_action('wp_ajax_get_respostas_by_assunto', 'agencia_aids_get_respostas_by_assunto');
// Handler para usuários não logados
add_action('wp_ajax_nopriv_get_respostas_by_assunto', 'agencia_aids_get_respostas_by_assunto');

function agencia_aids_get_respostas_by_assunto() {
    // Verificar o nonce para segurança
    if (!wp_verify_nonce($_POST['nonce'], 'faq_nonce')) {
        wp_die('Erro de segurança');
    }

    $term_id = intval($_POST['term_id']);
    
    if (!$term_id) {
        wp_send_json_error('ID do termo inválido');
        return;
    }

    $args = array(
        'post_type' => 'respostas',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'assuntos',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ),
        ),
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

    $posts = get_posts($args);
    
    if (empty($posts)) {
        wp_send_json_success('<p>Nenhuma resposta encontrada para este assunto.</p>');
        return;
    }

    ob_start();
    
    foreach ($posts as $post) {
        setup_postdata($post);
        ?>
        <details itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            <summary itemprop="name"><?php the_title(); ?></summary>
            <article itemprop="text">
                <?php the_content(); ?>
            </article>
        </details>
        <?php
    }
    
    wp_reset_postdata();
    
    $html = ob_get_clean();
    wp_send_json_success($html);
}
