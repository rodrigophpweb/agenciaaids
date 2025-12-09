<?php
/**
 * ocultar_anuncios_expirados
 * This function modifies the main query to hide expired ads.
 * It checks if the current query is for the 'anuncio' post type archive, taxonomy, or search results.
 * If the 'data_expiracao' meta field exists and is less than today's date, those posts are excluded.
 * @param WP_Query $query The current query object.
 * @return void
 * @since 1.0.0
 * @package AgenciaAids
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://www.agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/reference/hooks/pre_get_posts/
 */
function ocultar_anuncios_expirados($query) {
    if (!is_admin() && $query->is_main_query() && (is_post_type_archive('anuncio') || is_tax() || is_search())) {
        $hoje = current_time('Y-m-d');
        $meta_query = array(
            'relation' => 'OR',
            array(
                'key' => 'data_expiracao',
                'compare' => 'NOT EXISTS',
            ),
            array(
                'key' => 'data_expiracao',
                'value' => $hoje,
                'compare' => '>=',
                'type' => 'DATE'
            ),
        );
        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'ocultar_anuncios_expirados');
