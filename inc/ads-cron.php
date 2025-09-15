<?php
// 1) Função que despublica os anúncios vencidos
function despublicar_anuncios_expirados() {
    // Usa o timezone do WordPress (Configurações > Geral)
    $hoje = current_time('Y-m-d');

    $query = new WP_Query(array(
        'post_type'      => 'anuncio',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids', // performance
        'meta_query'     => array(
            'relation' => 'AND',
            // meta precisa existir
            array(
                'key'     => 'field_data_expiracao',
                'compare' => 'EXISTS',
            ),
            // meta não pode ser vazio
            array(
                'key'     => 'field_data_expiracao',
                'value'   => '',
                'compare' => '!=',
            ),
            // data <= hoje (se quiser incluir o dia atual) ou '<' se quiser só dias anteriores
            array(
                'key'     => 'field_data_expiracao',
                'value'   => $hoje,
                'compare' => '<=',
                'type'    => 'DATE',
            ),
        ),
    ));

    if ( $query->have_posts() ) {
        foreach ( $query->posts as $post_id ) {
            // Muda para rascunho
            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'draft',
            ));
        }
    }
}

// 2) Agendamento diário (use o hook init para registrar o cron)
function registrar_cron_verificar_anuncios_expirados() {
    if ( ! wp_next_scheduled('verificar_anuncios_expirados_evento') ) {
        // agenda para rodar daqui a 1 minuto e depois diariamente
        wp_schedule_event( time() + MINUTE_IN_SECONDS, 'daily', 'verificar_anuncios_expirados_evento' );
    }
}
add_action('init', 'registrar_cron_verificar_anuncios_expirados');

// 3) Liga a função ao evento
add_action('verificar_anuncios_expirados_evento', 'despublicar_anuncios_expirados');

// 4) Segurança: limpar agendamento ao trocar o tema
add_action('switch_theme', function () {
    wp_clear_scheduled_hook('verificar_anuncios_expirados_evento');
});
