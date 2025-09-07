<?php
/**
 * Carrega os estilos e scripts personalizados do tema
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/themes/basics/including-css-javascript/
 * @see https://developer.wordpress.org/themes/basics/conditional-tags/
 * @see https://developer.wordpress.org/themes/basics/child-themes/
 * @see https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @see https://developer.wordpress.org/themes/basics/template-tags/
 * @see https://developer.wordpress.org/themes/basics/template-files/
 * @see https://developer.wordpress.org/themes/basics/template-functions/
 * @see https://developer.wordpress.org/themes/basics/template-redirects/
 * @see https://developer.wordpress.org/themes/basics/template-parts/
 * @see https://developer.wordpress.org/themes/basics/template-tags/#conditional-tags
 * @see https://developer.wordpress.org/themes/basics/template-hierarchy/#conditional-tags
 * @see https://developer.wordpress.org/themes/basics/template-hierarchy/#template-files    
 */
function load_custom_css() {
    $css_files = [
        'acessibilidade'            => 'acessibility.css',
        'todos-artigos'                   => 'articles.css',
        'contato'                   => 'contact.css',
        'dicionario'                => 'dicionario.css',
        'faq'                       => 'faq.css',
        'home'                      => 'home.css',
        'palestras'                 => 'lectures.css',
        'servico'                   => 'services.css',
        'biblioteca'                => 'library.css',
        'todas-noticias'            => 'news.css',
        'politica-de-privacidade'   => 'policePrivacy.css',
        'quem-somos'                => 'whoWeAre.css',
        'single-artigos'            => 'single-artigos.css',
        'single-noticias'           => 'single-noticias.css',
        'single-videos'            => 'single-videos.css',
        'category'                  => 'category.css',
    ];

    // Página inicial
    if (is_front_page()) {
        wp_enqueue_style('home', get_template_directory_uri() . '/assets/css/pages/home.css');
        return;
    }

    // Páginas comuns
    if (is_page()) {
        global $post;
        $slug = $post->post_name;
        if (array_key_exists($slug, $css_files)) {
            wp_enqueue_style($slug, get_template_directory_uri() . '/assets/css/pages/' . $css_files[$slug]);
        }
    }

    // Arquivo de post type
    if (is_archive()) {
        $post_type = get_post_type(); // Ex: 'artigos', 'noticias'
        if ($post_type && array_key_exists($post_type, $css_files)) {
            wp_enqueue_style($post_type, get_template_directory_uri() . '/assets/css/pages/' . $css_files[$post_type]);
        }
    }

    // Post individual (ex: single-artigos.php)
    if (is_single()) {
        $post_type = get_post_type();
        $key = 'single-' . $post_type;
        if (array_key_exists($key, $css_files)) {
            wp_enqueue_style($key, get_template_directory_uri() . '/assets/css/pages/' . $css_files[$key]);
        }
    }

    // Categorias
    if (is_category()) {
        wp_enqueue_style('category', get_template_directory_uri() . '/assets/css/pages/' . $css_files['category']);
    }

    // Search
    if (is_search()) {
        wp_enqueue_style('search', get_template_directory_uri() . '/assets/css/pages/search.css');
    }
}
add_action('wp_enqueue_scripts', 'load_custom_css');


/**
 * Carrega o script app.js em todas as páginas
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://developer.wordpress.org/themes/basics/including-css-javascript/
 * 
 */
function load_custom_scripts() {
    $js_file_path = get_template_directory() . '/assets/js/app.js';
    $js_file_uri = get_template_directory_uri() . '/assets/js/app.js';
    
    // Verifica se o arquivo existe antes de carregar
    if (file_exists($js_file_path)) {
        $version = filemtime($js_file_path);
        wp_enqueue_script('app-js', $js_file_uri, array(), $version, true);
        
        // Adiciona o objeto de localização para AJAX se necessário
        wp_localize_script('app-js', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'load_custom_scripts');