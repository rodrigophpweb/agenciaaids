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
            $hoje = date('Y-m-d');
            $classe = ($data < $hoje) ? 'style="color:red;font-weight:bold;"' : '';
            echo "<span $classe>" . date_i18n('d/m/Y', strtotime($data)) . "</span>";
        } else {
            echo '—';
        }
    }
}
add_action('manage_anuncio_posts_custom_column', 'mostrar_valor_coluna_expiracao', 10, 2);
