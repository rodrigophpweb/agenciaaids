<?php
function coluna_expiracao_anuncio($columns) {
    $columns['data_expiracao'] = 'Expira em';
    return $columns;
}
add_filter('manage_anuncio_posts_columns', 'coluna_expiracao_anuncio');

function mostrar_valor_coluna_expiracao($column, $post_id) {
    if ($column == 'data_expiracao') {
        $data = get_field('data_expiracao', $post_id);
        if ($data) {
            // Usa a data/fuso do WordPress (mesma referência usada no cron de expiração)
            $hoje = current_time('Y-m-d');
            $classe = ($data < $hoje) ? 'style="color:red;font-weight:bold;"' : '';
            echo "<span $classe>" . date_i18n('d/m/Y', strtotime($data)) . "</span>";
        } else {
            echo '—';
        }
    }
}
add_action('manage_anuncio_posts_custom_column', 'mostrar_valor_coluna_expiracao', 10, 2);

// Permite ordenar a listagem clicando no cabeçalho da coluna "Expira em"
function tornar_coluna_expiracao_ordenavel($columns) {
    $columns['data_expiracao'] = 'data_expiracao';
    return $columns;
}
add_filter('manage_edit-anuncio_sortable_columns', 'tornar_coluna_expiracao_ordenavel');

function ordenar_por_data_expiracao($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    if ($query->get('orderby') === 'data_expiracao') {
        $query->set('meta_key', 'data_expiracao');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'ordenar_por_data_expiracao');