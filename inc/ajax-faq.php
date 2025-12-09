<?php
/**
 * AJAX handler para buscar respostas por assunto
 */

// Handler para usuários logados
add_action('wp_ajax_get_respostas_by_assunto', 'agencia_aids_get_respostas_by_assunto');
// Handler para usuários não logados
add_action('wp_ajax_nopriv_get_respostas_by_assunto', 'agencia_aids_get_respostas_by_assunto');

function agencia_aids_get_respostas_by_assunto() {    
    // Verificação de nonce mais permissiva para debug
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'faq_nonce')) {
        wp_send_json_error('Erro de segurança');
        wp_die();
    }

    if (!current_user_can('read')) {
        wp_send_json_error('Permissão negada');
        wp_die();
    }

    $term_id = intval($_POST['term_id']);
    
    
    if (!$term_id) {
        wp_send_json_error('ID do termo inválido');
        return;
    }

    // Query simplificada para debug
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
        // Removido meta_query temporariamente para debug
    );

    $posts = get_posts($args);
    
    if (empty($posts)) {
        wp_send_json_success('<p>Nenhuma resposta encontrada para este assunto.</p>');
        return;
    }

    ob_start();
    
    foreach ($posts as $post) {
        ?>
        <details itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
            <summary itemprop="name"><?php echo esc_html($post->post_title); ?></summary>
            <article itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                <div itemprop="text">
                    <?php echo wp_kses_post($post->post_content); ?>
                </div>
            </article>
        </details>
        <?php
    }
    
    $html = ob_get_clean();
    wp_send_json_success($html);
}
