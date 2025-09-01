<?php
/**
 * Registra campos ACF adicionais para os CPTs: noticias, equipes e palestras
 * @package AgenciaAids
 * @since 1.0.0
 */

if (function_exists('acf_add_local_field_group')) {

    // Grupo: Fonte de Informações (para Noticias)
    acf_add_local_field_group(array(
        'key' => 'group_5a9552bf43ba7',
        'title' => 'Fonte de Informações',
        'fields' => array(
            array(
                'key' => 'field_5a9552ca9e515',
                'label' => 'Fonte',
                'name' => 'fonte',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'noticias',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
        'show_in_graphql' => 1,
        'graphql_field_name' => 'fonteDeInformacaoes',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Noticia', 'Post'),
    ));

    // Grupo: Informação (para Equipes e Palestras)
    acf_add_local_field_group(array(
        'key' => 'group_5a85a1e78b754',
        'title' => 'Informação',
        'fields' => array(
            array(
                'key' => 'field_5a85a1f32f9e4',
                'label' => 'Cargo',
                'name' => 'cargo',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_5a954df05079d',
                'label' => 'E-mail',
                'name' => 'email',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'equipes',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'palestras',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => false,
    ));
}
