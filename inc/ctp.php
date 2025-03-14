<?php
// ...existing code...

function create_custom_post_type($name, $singular_name, $menu_icon, $supports = array('title', 'editor', 'thumbnail', 'excerpt', 'comments')) {
    $labels = array(
        'name'                  => _x($name, 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x($singular_name, 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __($name, 'textdomain'),
        'name_admin_bar'        => __($singular_name, 'textdomain'),
        'archives'              => __('Item Archives', 'textdomain'),
        'attributes'            => __('Item Attributes', 'textdomain'),
        'parent_item_colon'     => __('Parent Item:', 'textdomain'),
        'all_items'             => __('All Items', 'textdomain'),
        'add_new_item'          => __('Add New Item', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'new_item'              => __('New Item', 'textdomain'),
        'edit_item'             => __('Edit Item', 'textdomain'),
        'update_item'           => __('Update Item', 'textdomain'),
        'view_item'             => __('View Item', 'textdomain'),
        'view_items'            => __('View Items', 'textdomain'),
        'search_items'          => __('Search Item', 'textdomain'),
        'not_found'             => __('Not found', 'textdomain'),
        'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
        'featured_image'        => __('Featured Image', 'textdomain'),
        'set_featured_image'    => __('Set featured image', 'textdomain'),
        'remove_featured_image' => __('Remove featured image', 'textdomain'),
        'use_featured_image'    => __('Use as featured image', 'textdomain'),
        'insert_into_item'      => __('Insert into item', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'textdomain'),
        'items_list'            => __('Items list', 'textdomain'),
        'items_list_navigation' => __('Items list navigation', 'textdomain'),
        'filter_items_list'     => __('Filter items list', 'textdomain'),
    );
    $args = array(
        'label'                 => __($singular_name, 'textdomain'),
        'description'           => __('Post Type Description', 'textdomain'),
        'labels'                => $labels,
        'supports'              => $supports,
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => $menu_icon,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type(strtolower($name), $args);
}

function register_custom_post_types() {
    create_custom_post_type('Artigos', 'Artigo', 'dashicons-admin-post');
    create_custom_post_type('Eventos', 'Evento', 'dashicons-calendar');
    create_custom_post_type('Bibliotecas', 'Biblioteca', 'dashicons-book');
    create_custom_post_type('Servico', 'Serviço', 'dashicons-hammer');
    create_custom_post_type('Dicionarios', 'Dicionario', 'dashicons-book-alt');
    create_custom_post_type('Respostas', 'Resposta', 'dashicons-format-chat');
}
add_action('init', 'register_custom_post_types');

function create_custom_taxonomy($name, $post_type) {
    $labels = array(
        'name'              => _x($name, 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x($name, 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search ' . $name, 'textdomain'),
        'all_items'         => __('All ' . $name, 'textdomain'),
        'parent_item'       => __('Parent ' . $name, 'textdomain'),
        'parent_item_colon' => __('Parent ' . $name . ':', 'textdomain'),
        'edit_item'         => __('Edit ' . $name, 'textdomain'),
        'update_item'       => __('Update ' . $name, 'textdomain'),
        'add_new_item'      => __('Add New ' . $name, 'textdomain'),
        'new_item_name'     => __('New ' . $name . ' Name', 'textdomain'),
        'menu_name'         => __($name, 'textdomain'),
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => strtolower($name)),
    );
    register_taxonomy(strtolower($name), array($post_type), $args);
}

function register_custom_taxonomies() {
    create_custom_taxonomy('Temas', 'respostas');
}
add_action('init', 'register_custom_taxonomies');