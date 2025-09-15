<?php

const META_EXPIRACAO = 'field_data_expiracao'; // <- troque se o seu nome for outro

function despublicar_anuncios_expirados() {
    $hoje = current_time('Y-m-d');

    $q = new WP_Query(array(
        'post_type'      => 'anuncio',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
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
}
add_action('verificar_anuncios_expirados_evento', 'despublicar_anuncios_expirados');

function registrar_cron_verificar_anuncios_expirados() {
    if (!wp_next_scheduled('verificar_anuncios_expirados_evento')) {
        wp_schedule_event(time() + MINUTE_IN_SECONDS, 'daily', 'verificar_anuncios_expirados_evento');
    }
}
add_action('init', 'registrar_cron_verificar_anuncios_expirados');

add_action('switch_theme', function () {
    wp_clear_scheduled_hook('verificar_anuncios_expirados_evento');
});

// 2) Vira rascunho IMEDIATAMENTE ao salvar/atualizar (funciona no admin)
function anuncio_expira_no_salvar($post_id, $post, $update) {
    // evita loop, revisões e lixeira
    if (wp_is_post_revision($post_id) || $post->post_status === 'trash' || $post->post_type !== 'anuncio') {
        return;
    }

    // lê a data
    $raw = get_post_meta($post_id, META_EXPIRACAO, true);
    if (!$raw) return;

    // normaliza formatos comuns do ACF: Y-m-d ou d/m/Y
    $dataObj = false;
    if (preg_match('#^\d{4}-\d{2}-\d{2}$#', $raw)) {
        $dataObj = DateTime::createFromFormat('Y-m-d', $raw);
    } elseif (preg_match('#^\d{2}/\d{2}/\d{4}$#', $raw)) {
        $dataObj = DateTime::createFromFormat('d/m/Y', $raw);
    }
    if (!$dataObj) return;

    $hoje = new DateTime(current_time('Y-m-d'));

    if ($dataObj <= $hoje && $post->post_status === 'publish') {
        // evita reentrância
        remove_action('save_post', 'anuncio_expira_no_salvar', 10);
        wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
        add_action('save_post', 'anuncio_expira_no_salvar', 10, 3);
    }
}
add_action('save_post', 'anuncio_expira_no_salvar', 10, 3);

// (Opcional) Se você usa ACF, pode garantir que rode após o ACF salvar os campos:
// add_action('acf/save_post', function($post_id){ if (get_post_type($post_id)==='anuncio') anuncio_expira_no_salvar($post_id, get_post($post_id), true); }, 20);
