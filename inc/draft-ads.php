<?php

/*
 * Coplito é o seguinte eu tenho um custom post type chamado com slug chamado anuncio
 * Nele eu tenho alguns fields personalizados que eu criei com o ACF, porém eu preciso criar uma funcionalidade com base no
 * seguinte field chamado de field_data_expiracao.
 * 
 * O que eu preciso que ele pegue o valor de data de expiração e compare com a data atual do sistema, se a data de expiração
 * for menor que a data atual do sistema, ele deve automaticamente mudar o status do post para rascunho (draft).
 * 
 * Essa função precisa ser executada diariamente, para que assim que um anúncio expirar ele já mude o status. e vai rodar no 
 * painel do wordpress normalmente.
 * 
 * Eu preciso que você me ajude a criar essa funcionalidade, você pode fazer isso?
 */

function verificar_expiracao_anuncios() {
    // Define o fuso horário para garantir que a comparação de datas seja correta
    date_default_timezone_set('America/Sao_Paulo');

    // Obtém a data atual no formato Y-m-d
    $data_atual = date('Y-m-d');

    // Argumentos para consultar os anúncios publicados
    $args = array(
        'post_type'      => 'anuncio',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'data_expiracao',
                'value'   => $data_atual,
                'compare' => '<',
                'type'    => 'DATE'
            )
        )
    );

    // Consulta os anúncios que expiraram
    $query = new WP_Query($args);

    // Verifica se há anúncios expirados
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();

            // Atualiza o status do anúncio para rascunho (draft)
            wp_update_post(array(
                'ID'          => $post_id,
                'post_status' => 'draft'
            ));
        }
        wp_reset_postdata();
    }
}

// Agendar a verificação diária se ainda não estiver agendada
if (!wp_next_scheduled('verificar_expiracao_anuncios_evento')) {
    wp_schedule_event(time(), 'daily', 'verificar_expiracao_anuncios_evento');
}

?>

