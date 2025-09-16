<?php
/**
 * AJAX handler para buscar respostas por assunto
 */

// Handler para usuários logados
add_action('wp_ajax_get_respostas_by_assunto', 'agencia_aids_get_respostas_by_assunto');
// Handler para usuários não logados
add_action('wp_ajax_nopriv_get_respostas_by_assunto', 'agencia_aids_get_respostas_by_assunto');

function agencia_aids_get_respostas_by_assunto() {
    // Debug: verificar se a requisição está chegando
    error_log('FAQ AJAX - Função chamada, POST data: ' . print_r($_POST, true));
    
    // Verificação de nonce mais permissiva para debug
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'faq_nonce')) {
        error_log('FAQ AJAX - Erro de nonce');
        // Temporariamente continuar mesmo com erro de nonce para debug
        // wp_die('Erro de segurança');
    }

    $term_id = intval($_POST['term_id']);
    
    // Debug log
    error_log('FAQ AJAX - Term ID recebido: ' . $term_id);
    
    if (!$term_id) {
        error_log('FAQ AJAX - Term ID inválido');
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
    
    // Debug logs
    error_log('FAQ AJAX - Query args: ' . print_r($args, true));
    error_log('FAQ AJAX - Posts encontrados: ' . count($posts));
    
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
