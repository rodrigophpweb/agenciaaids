<?php
    /**
     *  Template Name: Arquivo de Notícias
     *  Template Post Type: post
     *  @package AgenciaAids
     *  @since 1.0.0
     *  @author Rodrigo Vieira Eufrasio da Silva
     *  @link https://agenciaaids.com.br
     *  @license GPL-2.0+
     *  @see https://developer.wordpress.org/themes/basics/template-hierarchy/
     */
    get_header();

    /**
     * Cria uma nova instância de WP_Query para buscar os posts do tipo atual
     * com paginação e número de posts por página definido nas opções do WordPress.
     * * @see https://developer.wordpress.org/reference/classes/wp_query/
     * * @see https://developer.wordpress.org/themes/basics/template-hierarchy/#archive-templates
     */
    $query = new WP_Query([
        'post_type'      => get_post_type(),
        'paged'          => get_query_var('paged') ?: 1,
        'posts_per_page' => get_option('posts_per_page'),
    ]);

    /**
     * Configura os argumentos para o template de grid de conteúdo.
     * * 'title' é o título do arquivo, obtido através de get_the_archive_title().
     * * 'query' é a instância de WP_Query criada acima.
     * * 'template' define o template específico para o tipo de post, como 'card-noticia.php'.
     * * 'cta' é o texto do botão de chamada para ação, como 'Ver mais'.
     * * 'cta_link' é o link para o arquivo do tipo de post.
     */
    $args = [
        'title'     => get_the_archive_title(),
        'query'     => $query,
        'template'  => 'card-' . get_post_type(), // Ex: card-noticia.php
        'cta'       => 'Ver mais',
        'cta_link'  => get_post_type_archive_link(get_post_type()),
        'grid'      => 'grid-4', // ou grid-3, etc.
        'limit'     => get_option('posts_per_page'),
    ];

    get_template_part('partials/section', 'content-grid', ['args' => $args]);

    get_footer();
?>
