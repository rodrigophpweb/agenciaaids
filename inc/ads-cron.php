<?php
// Nome do meta do ACF
const META_EXPIRACAO = 'field_data_expiracao';

// Despublica imediatamente ao salvar/atualizar no admin (depois do ACF salvar os campos)
add_action('acf/save_post', function ($post_id) {
    // Ignora se não for um post normal (ACF às vezes salva options etc.)
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'anuncio') {
        return;
    }

    // Ignora revisões/auto-saves/lixeira
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id) || $post->post_status === 'trash') {
        return;
    }

    // Lê a data do ACF já no formato Y-m-d (por causa do return_format)
    $raw = get_field(META_EXPIRACAO, $post_id); // <- ACF helper
    if (!$raw) {
        return;
    }

    // Compara com a data "hoje" do WordPress (respeita fuso do site)
    $hoje = current_time('Y-m-d');

    // Se venceu (<= hoje) e ainda está publicado, vira rascunho
    if ($raw <= $hoje && $post->post_status === 'publish') {
        // Evita loop
        remove_action('acf/save_post', __FUNCTION__);
        wp_update_post(array(
            'ID'          => $post_id,
            'post_status' => 'draft',
        ));
        add_action('acf/save_post', __FUNCTION__);
    }
}, 20); // prioridade 20 para rodar depois que o ACF gravar os campos

// (Opcional) Varredura diária como rede de segurança
add_action('verificar_anuncios_expirados_evento', function () {
    $hoje = current_time('Y-m-d');
    $q = new WP_Query(array(
        'post_type'      => 'anuncio',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'AND',
            array('key' => META_EXPIRACAO, 'compare' => 'EXISTS'),
            array('key' => META_EXPIRACAO, 'value' => '', 'compare' => '!='),
            array(
                'key'     => META_EXPIRACAO,
                'value'   => $hoje,
                'compare' => '<=',
                'type'    => 'DATE',
            ),
        ),
    ));
    if ($q->have_posts()) {
        foreach ($q->posts as $post_id) {
            wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
        }
    }
});
add_action('init', function () {
    if (!wp_next_scheduled('verificar_anuncios_expirados_evento')) {
        wp_schedule_event(time() + MINUTE_IN_SECONDS, 'daily', 'verificar_anuncios_expirados_evento');
    }
});
add_action('switch_theme', function () {
    wp_clear_scheduled_hook('verificar_anuncios_expirados_evento');
});
