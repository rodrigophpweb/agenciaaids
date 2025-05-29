<?php
if (isset($args['query'])) {
    $query = $args['query'];
    echo paginate_links([
        'total' => $query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'format' => '?paged=%#%',
        'prev_text' => __('&laquo; Anterior'),
        'next_text' => __('Próximo &raquo;'),
        'before_page_number' => '<span class="screen-reader-text">' . __('Página') . ' </span>'
    ]);
}
?>