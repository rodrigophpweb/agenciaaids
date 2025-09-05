<?php
// --------------------------------------------------
// Suporte ao tema
// --------------------------------------------------
require_once get_template_directory() . '/inc/setup-theme.php';

// --------------------------------------------------
// Scripts e estilos
// --------------------------------------------------
require_once get_template_directory() . '/inc/style-scripts.php';

// --------------------------------------------------
// Helpers e funções utilitárias
// --------------------------------------------------
require_once get_template_directory() . '/inc/helpers.php';

// --------------------------------------------------
// Breadcrumb
// --------------------------------------------------
require_once get_template_directory() . '/inc/breadcrumb.php';

// --------------------------------------------------
// Menus
// --------------------------------------------------
require_once get_template_directory() . '/inc/register-menus.php';

// --------------------------------------------------
// Tamanhos de imagem
// --------------------------------------------------
require_once get_template_directory() . '/inc/image-sizes.php';

// --------------------------------------------------
// ACF - Campos personalizados e Opções do Tema
// --------------------------------------------------
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => 'Configurações do Tema',
        'menu_title' => 'Configurações do Tema',
        'menu_slug'  => 'configuracoes-do-tema',
        'capability' => 'edit_posts',
        'redirect'   => false,
    ]);
}

// Autoload dos campos ACF
add_action('acf/init', function () {
    $acf_dir = get_template_directory() . '/inc/fields';
    foreach (glob($acf_dir . '/*.php') as $file) {
        require_once $file;
    }
});

// --------------------------------------------------
// Ajustes no WordPress
// --------------------------------------------------
add_filter('script_loader_tag', function ($tag, $handle, $src) {
    return str_replace(" type='text/javascript'", '', $tag);
}, 10, 3);

add_action('init', function () {
    add_post_type_support('post', 'excerpt');
    add_post_type_support('page', 'excerpt');
});

// --------------------------------------------------
// Registro e ajustes de CPT e queries
// --------------------------------------------------
require_once get_template_directory() . '/inc/ctp.php';
require_once get_template_directory() . '/inc/query-mods.php';

// --------------------------------------------------
// Cron para anúncios
// --------------------------------------------------
require_once get_template_directory() . '/inc/ads-cron.php';

// --------------------------------------------------
// Limpezas e performance
// --------------------------------------------------
require_once get_template_directory() . '/inc/performance-cleanup.php';
require_once get_template_directory() . '/inc/disable-emojis.php';
require_once get_template_directory() . '/inc/disable-speculation-rules.php';
require_once get_template_directory() . '/inc/cleanup-html-output.php';

// --------------------------------------------------
// Outras personalizações
// --------------------------------------------------
require_once get_template_directory() . '/inc/custom-excerpt-lenght.php';

// --------------------------------------------------
// Ocultar o tipo de post padrão "Post"
// --------------------------------------------------
add_action('admin_menu', function () {
    remove_menu_page('edit.php');
});

function agenciaaids_remover_primeiro_paragrafo_imagem($content) {
    if (!is_singular('post') && !is_singular('artigos')) return $content;

    // Remove o primeiro <p> se ele contiver apenas uma <img>
    $pattern = '/^<p>\s*(<img[^>]+>)\s*<\/p>/i';

    $content = preg_replace($pattern, '', $content, 1);

    return $content;
}
add_filter('the_content', 'agenciaaids_remover_primeiro_paragrafo_imagem');

// --------------------------------------------------
// AJAX para filtros de categoria
// --------------------------------------------------
function agenciaaids_filter_posts() {
    // Verificar nonce para segurança
    if (!wp_verify_nonce($_POST['nonce'], 'ajax_nonce')) {
        wp_die('Erro de segurança');
    }

    $year = sanitize_text_field($_POST['year']);
    $month = sanitize_text_field($_POST['month']);
    $category = intval($_POST['category']);
    $current_category = intval($_POST['current_category']);
    $paged = intval($_POST['paged']) ?: 1;

    // Construir argumentos da query
    $args = [
        'post_type'      => ['post', 'noticias'],
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'paged'          => $paged,
        'ignore_sticky_posts' => true,
    ];

    // Filtros por data
    $date_query = [];
    if ($year) {
        $date_query['year'] = $year;
    }
    if ($month) {
        $date_query['month'] = $month;
    }
    if (!empty($date_query)) {
        $args['date_query'] = [$date_query];
    }

    // Filtro por categoria
    if ($category) {
        $args['cat'] = $category;
    } elseif ($current_category) {
        // Se não foi especificada categoria no filtro mas existe categoria atual, usar a categoria atual
        $args['cat'] = $current_category;
    }
    // Se nem category nem current_category existem, busca todas as categorias

    $query = new WP_Query($args);
    
    ob_start();
    
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            ?>
            <article class="card">
                <a href="<?php the_permalink(); ?>" class="card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                    <figure>
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('thumbnail'); ?>
                        <?php else : ?>
                            <!-- <img src="https://agenciaaids.com.br/wp-content/themes/agenciaaids/assets/images/backdrop-ag-aids-compress-web.webp" alt="<?= esc_attr(get_the_title()); ?>"> -->
                            <?php echo aa_get_safe_thumbnail_html( get_the_ID(), 'medium', ['class' => 'thumb'] );?>
                        <?php endif; ?>
                    </figure>

                    <div class="content">
                        <?php the_title('<h2 class="card-title">', '</h2>'); ?>

                        <?php
                            $post_cats = get_the_category();
                            if (!empty($post_cats)) :
                        ?>
                            <mark class="category"><?php echo esc_html($post_cats[0]->name); ?></mark>
                        <?php endif; ?>
                        
                        <p><?php echo wp_trim_words(trim(str_replace(['&nbsp;', ' '], ' ', get_the_content())), 30, '...'); ?></p>
                        <time class="card-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </div>
                </a>
            </article>
            <?php
        endwhile;
    else :
        echo '<p>Nenhum conteúdo encontrado com os filtros selecionados.</p>';
    endif;
    
    $content = ob_get_clean();
    
    // Preparar paginação
    $pagination = '';
    if ($query->max_num_pages > 1) {
        ob_start();
        
        // Construir URL base para paginação
        $current_url = home_url($_SERVER['REQUEST_URI']);
        $base_url = strtok($current_url, '?'); // Remove query string se existir
        
        echo paginate_links([
            'total'   => $query->max_num_pages,
            'current' => $paged,
            'base'    => $base_url . '%_%',
            'format'  => '?paged=%#%',
            'prev_text' => '« Anterior',
            'next_text' => 'Próxima »',
            'type'      => 'plain',
        ]);
        $pagination = ob_get_clean();
    }
    
    wp_reset_postdata();
    
    wp_send_json_success([
        'content' => $content,
        'pagination' => $pagination,
        'total_posts' => $query->found_posts,
        'max_pages' => $query->max_num_pages
    ]);
}

add_action('wp_ajax_filter_posts', 'agenciaaids_filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'agenciaaids_filter_posts');


// --------------------------------------------------
// Função para obter thumbnail com fallback
// --------------------------------------------------
function aa_get_safe_thumbnail_html( $post_id = null, $size = 'medium', $attr = [] ) {
    $post_id = $post_id ?: get_the_ID();
    $fallback = 'https://agenciaaids.com.br/wp-content/themes/agenciaaids/assets/images/backdrop-ag-aids-compress-web.webp';

    $thumb_id = get_post_thumbnail_id( $post_id );
    if ( $thumb_id ) {
        $file = get_attached_file( $thumb_id ); // caminho absoluto do arquivo original
        if ( $file && file_exists( $file ) ) {
            // Ok, arquivo existe
            return get_the_post_thumbnail( $post_id, $size, $attr );
        }
    }

    // Sem thumb ou arquivo sumiu -> imprime <img> com o fallback
    $alt = esc_attr( get_the_title( $post_id ) );
    $classes = isset($attr['class']) ? 'class="'. esc_attr($attr['class']) .'"' : '';
    $loading = isset($attr['loading']) ? 'loading="'. esc_attr($attr['loading']) .'"' : 'loading="lazy"';
    return sprintf('<img src="%s" alt="%s" %s %s />', esc_url($fallback), $alt, $classes, $loading);
}

