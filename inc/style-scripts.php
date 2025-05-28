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
    // HTMX – local ou CDN
    wp_enqueue_script('htmx', get_template_directory_uri() . '/assets/js/htmx.min.js', [], null, true);
    $css_files = [
        'acessibilidade'            => 'acessibility.css',
        'artigos'                   => 'articles.css',
        'contato'                   => 'contact.css',
        'dicionario'                => 'dictionary.css',
        'faq'                       => 'faq.css',
        'home'                      => 'home.css',
        'palestras'                 => 'lectures.css',
        'biblioteca'                => 'library.css',
        'noticias'                  => 'news.css',
        'politica-de-privacidade'   => 'policePrivacy.css',
        'quem-somos'                => 'whoWeAre.css',
    ];

    if (is_page()) {
        global $post;
        $slug = $post->post_name;

        if (is_front_page()) {
            wp_enqueue_style('home', get_template_directory_uri() . '/assets/css/pages/home.css');
        } elseif (array_key_exists($slug, $css_files)) {
            wp_enqueue_style($slug, get_template_directory_uri() . '/assets/css/pages/' . $css_files[$slug]);
        }
    }
}
add_action('wp_enqueue_scripts', 'load_custom_css');