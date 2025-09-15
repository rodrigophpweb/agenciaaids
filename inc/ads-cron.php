<?php
// Função que despublica os anúncios vencidos
function despublicar_anuncios_expirados() {
    $hoje = date('Y-m-d');

    $query = new WP_Query(array(
        'post_type'      => 'anuncio',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'field_data_expiracao',
                'value'   => $hoje,
                'compare' => '<',
                'type'    => 'DATE',
            )
        )
    ));

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            wp_update_post(array(
                'ID'          => $post->ID,
                'post_status' => 'draft'
            ));
        }
    }
}

// Agendamento diário (se não estiver agendado)
if (!wp_next_scheduled('verificar_anuncios_expirados_evento')) {
    wp_schedule_event(time(), 'daily', 'verificar_anuncios_expirados_evento');
}

// Liga a função ao evento
add_action('verificar_anuncios_expirados_evento', 'despublicar_anuncios_expirados');

// Segurança: limpar agendamento ao trocar o tema
add_action('switch_theme', function () {
    wp_clear_scheduled_hook('verificar_anuncios_expirados_evento');
});
