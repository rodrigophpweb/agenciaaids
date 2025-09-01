<?php
/**
 * ACF Videos
 * This file registers a custom ACF field group for embedding videos in the 'videos' post type.
 * It includes a single text field for the video embed URL.
 * The field is displayed after the post title in the editor.
 * The field group is set to be used with the 'videos' post type and is compatible with GraphQL.
 * @package agenciaaids
 * @since 1.0.0 
 * @version 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://www.advancedcustomfields.com/resources/register-fields-via-php/
 * @see https://www.advancedcustomfields.com/resources/fields/text/
 * @see https://www.advancedcustomfields.com/resources/conditional-logic/
 * @see https://www.advancedcustomfields.com/resources/show-fields-in-rest-api/
 * @see https://www.advancedcustomfields.com/resources/graphql/
 * @see https://www.advancedcustomfields.com/resources/graphql-field-names/
 * @see https://www.advancedcustomfields.com/resources/graphql-map-types-from-location-rules/
 * @see https://www.advancedcustomfields.com/resources/graphql-map-types-from-location-rules/#map-graphql-types-from-location-rules
 * @see https://www.advancedcustomfields.com/resources/graphql-types/
 * @see https://www.advancedcustomfields.com/resources/graphql-types/#graphql-types
 * @see https://www.advancedcustomfields.com/resources/graphql-types/#graphql-types-from-location-rules
 */

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_5aa7c5d45eebe',
        'title' => 'Embed Vídeos',
        'fields' => array(
            array(
                'key' => 'field_5aa7c5ed84f24',
                'label' => 'URL do Embed',
                'name' => 'embed',
                'type' => 'text',
                'instructions' => 'Inserir a URL gerada quando for colocar o iframe. Ex: https://player.vimeo.com/video/113385648 ou https://www.youtube.com/embed/rteC3aaGMvw',
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
                    'value' => 'videos',
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
        'graphql_field_name' => 'embedVideos',
        'map_graphql_types_from_location_rules' => 1,
        'graphql_types' => array('Post', 'Video'),
    ));
}
