<?php
/**
 * Expiração automática de anúncios (CPT "anuncio")
 *
 * Regra: o anúncio deve virar RASCUNHO exatamente às 23:59:00 do dia
 * definido no campo ACF "Data de Expiração" (data_expiracao).
 *
 * Estratégia:
 * 1. Ao salvar o post no admin, calculamos o timestamp exato de expiração
 *    (data escolhida + 23:59:00, no fuso horário do site) e:
 *      - se esse instante já passou, despublica na hora;
 *      - se ainda não chegou, agenda um evento único (wp_schedule_single_event)
 *        para disparar exatamente nesse instante.
 *    Se a data for alterada/removida, o agendamento antigo é limpo e recriado.
 * 2. Uma varredura de segurança roda a cada hora, cobrindo o caso do
 *    WP-Cron não disparar no horário exato (ex.: baixo tráfego no site).
 */

// Nome do meta do ACF
const META_EXPIRACAO = 'field_data_expiracao';
const META_EXPIRACAO_NOME = 'data_expiracao'; // 'name' do campo (usado no meta_query)
const HOOK_EXPIRAR_ANUNCIO = 'agenciaaids_expirar_anuncio_evento';
const HOOK_VARREDURA_ANUNCIOS = 'agenciaaids_varredura_anuncios_evento';

/**
 * Calcula o timestamp (GMT/unix) correspondente a "data 23:59:00" no fuso do site.
 * Retorna false se a data for inválida.
 */
function agenciaaids_calcular_timestamp_expiracao($data_y_m_d)
{
    try {
        $dt = new DateTime($data_y_m_d . ' 23:59:00', wp_timezone());
        return $dt->getTimestamp();
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Move o anúncio para rascunho, se ainda estiver publicado.
 */
function agenciaaids_despublicar_anuncio($post_id)
{
    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'anuncio' || $post->post_status !== 'publish') {
        return;
    }
    wp_update_post(array(
        'ID'          => $post_id,
        'post_status' => 'draft',
    ));
}

// Callback do evento único agendado para o instante exato de expiração
add_action(HOOK_EXPIRAR_ANUNCIO, function ($post_id) {
    // Revalida a data no momento do disparo, caso o usuário tenha alterado o campo depois de agendar
    $raw = get_field(META_EXPIRACAO, $post_id);
    if (!$raw) {
        return;
    }
    $ts_expira = agenciaaids_calcular_timestamp_expiracao($raw);
    if ($ts_expira !== false && time() >= $ts_expira) {
        agenciaaids_despublicar_anuncio($post_id);
    }
}, 10, 1);

// Ao salvar/atualizar o anúncio no admin (depois do ACF gravar os campos)
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

    // Sempre limpa um agendamento anterior para este post (a data pode ter mudado)
    wp_clear_scheduled_hook(HOOK_EXPIRAR_ANUNCIO, array($post_id));

    // Lê a data do ACF já no formato Y-m-d (por causa do return_format)
    $raw = get_field(META_EXPIRACAO, $post_id);
    if (!$raw) {
        return;
    }

    $ts_expira = agenciaaids_calcular_timestamp_expiracao($raw);
    if ($ts_expira === false) {
        return;
    }

    if (time() >= $ts_expira) {
        // A data + 23:59:00 já passou: despublica imediatamente
        agenciaaids_despublicar_anuncio($post_id);
    } elseif ($post->post_status === 'publish') {
        // Ainda não chegou a hora: agenda o disparo exato às 23:59:00 da data escolhida
        wp_schedule_single_event($ts_expira, HOOK_EXPIRAR_ANUNCIO, array($post_id));
    }
}, 20); // prioridade 20 para rodar depois que o ACF gravar os campos

// Varredura de segurança (a cada hora), para o caso do WP-Cron não disparar no horário exato
add_action(HOOK_VARREDURA_ANUNCIOS, function () {
    $agora = time();

    $q = new WP_Query(array(
        'post_type'      => 'anuncio',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'AND',
            array('key' => META_EXPIRACAO_NOME, 'compare' => 'EXISTS'),
            array('key' => META_EXPIRACAO_NOME, 'value' => '', 'compare' => '!='),
            array(
                // Filtro grosso por dia (o corte fino de 23:59:00 é feito em PHP abaixo)
                'key'     => META_EXPIRACAO_NOME,
                'value'   => current_time('Y-m-d'),
                'compare' => '<=',
                'type'    => 'DATE',
            ),
        ),
    ));

    if ($q->have_posts()) {
        foreach ($q->posts as $post_id) {
            $raw = get_field(META_EXPIRACAO, $post_id);
            if (!$raw) {
                continue;
            }
            $ts_expira = agenciaaids_calcular_timestamp_expiracao($raw);
            if ($ts_expira !== false && $agora >= $ts_expira) {
                agenciaaids_despublicar_anuncio($post_id);
            }
        }
    }
});
add_action('init', function () {
    if (!wp_next_scheduled(HOOK_VARREDURA_ANUNCIOS)) {
        wp_schedule_event(time() + MINUTE_IN_SECONDS, 'hourly', HOOK_VARREDURA_ANUNCIOS);
    }
});
add_action('switch_theme', function () {
    wp_clear_scheduled_hook(HOOK_VARREDURA_ANUNCIOS);
    // Observação: os eventos únicos (HOOK_EXPIRAR_ANUNCIO) ficam por post e são
    // recriados/limpos a cada 'acf/save_post', então não precisam de limpeza em massa aqui.
});