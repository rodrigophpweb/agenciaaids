<?php
/**
 * Registra os custom post types do projeto Agência Aids
 */
function agencia_aids_register_cpt() {
    $post_types = [
        // slug       => [Nome Plural, Nome Singular, Ícone]
        'noticias'       => ['Notícias', 'Notícia', 'dashicons-media-document'],
        'artigos'        => ['Artigos', 'Artigo', 'dashicons-media-text'],
        'eventos'        => ['Eventos', 'Evento', 'dashicons-calendar-alt'],
        'bibliotecas'    => ['Biblioteca', 'Livro', 'dashicons-book-alt'],
        'servicos'       => ['Serviços', 'Serviço', 'dashicons-admin-tools'],
        'dicionarios'     => ['Dicionário', 'Termo', 'dashicons-book'],
        'respostas'      => ['Respostas', 'Resposta', 'dashicons-format-chat'],
        'videos'         => ['Vídeos', 'Vídeo', 'dashicons-video-alt3'],
        'Equipe'         => ['Equipes', 'Equipe', 'dashicons-video-alt3'],
        'anuncio'        => ['Anúncios', 'Anúncio', 'dashicons-megaphone'],
    ];

    foreach ($post_types as $slug => [$plural, $singular, $icon]) {
        // Define os campos padrão
        $supports = ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'];

        // Ajusta os campos do CPT 'anuncio'
        if ($slug === 'anuncio') {
            $supports = ['title', 'thumbnail'];
        }elseif ($slug === 'respostas' || $slug === 'dicionario'){
            $supports = ['title', 'editor'];
        }elseif ($slug === 'videos'){
            $supports = ['title'];
        }

        register_post_type($slug, [
            'labels' => [
                'name'               => $plural,
                'singular_name'      => $singular,
                'add_new'            => "Adicionar Novo",
                'add_new_item'       => "Adicionar Novo $singular",
                'edit_item'          => "Editar $singular",
                'new_item'           => "Novo $singular",
                'view_item'          => "Ver $singular",
                'view_items'         => "Ver $plural",
                'search_items'       => "Buscar $plural",
                'not_found'          => "Nenhum $singular encontrado",
                'not_found_in_trash' => "Nenhum $singular encontrado na lixeira",
                'all_items'          => "Todos os $plural",
                'archives'           => "Arquivos de $plural",
                'attributes'         => "Atributos de $singular",
                'menu_name'          => $plural,
            ],
            'public'             => true,
            'has_archive'        => true,
            'show_in_rest'       => true,
            'rewrite'            => ['slug' => $slug],
            'menu_icon'          => $icon,
            'supports'           => $supports,
        ]);
    }
}

// Hook principal
add_action('init', 'agencia_aids_register_cpt');



/**
 * Anexa a taxonomia 'category' aos custom post types especificados
 */

function agencia_aids_attach_categories_to_cpts() {
    $post_types_to_attach = ['noticia', 'artigo', 'video'];

    foreach ($post_types_to_attach as $post_type) {
        register_taxonomy_for_object_type('category', $post_type);
    }
}
add_action('init', 'agencia_aids_attach_categories_to_cpts');