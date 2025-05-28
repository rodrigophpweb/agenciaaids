<?php
/**
 * Registra os campos personalizados ACF para o CPT 'artigos'
 * @package AgenciaAids
 * @since 1.0.0
 */

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_5a95b5580579d',
        'title' => 'Informação Autor',
        'fields' => array(
            array(
                'key' => 'field_5a95b5769768e',
                'label' => 'Autor',
                'name' => 'autor',
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
                    'value' => 'artigos',
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
        'graphql_field_name' => 'informacaoAutor',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Artigo', 'Post'),
    ));
}
