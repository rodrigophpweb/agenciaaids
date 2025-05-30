<?php
/**
 * ACF Custom Fields for Ads Expiration
 * This file adds a custom field group for managing ad expiration dates.
 * It uses the Advanced Custom Fields (ACF) plugin to create a date picker field
 * for the 'anuncio' post type.
 * @package AgenciaAids
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * @link https://www.agenciaaids.com.br
 * @license GPL-2.0+
 * @see https://www.advancedcustomfields.com/
 */
if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group(array(
        'key' => 'group_anuncio_campos',
        'title' => 'Detalhes do Anúncio',
        'fields' => array(
            array(
                'key' => 'field_link_anuncio',
                'label' => 'Link do Anúncio',
                'name' => 'link_anuncio',
                'type' => 'url',
                'instructions' => 'Informe o link externo do anúncio, caso exista.',
                'required' => 0,
                'placeholder' => 'https://anunciante.com.br/ads',
            ),
            array(
                'key' => 'field_data_expiracao',
                'label' => 'Data de Expiração',
                'name' => 'data_expiracao',
                'type' => 'date_picker',
                'display_format' => 'd/m/Y',
                'return_format' => 'Y-m-d',
                'required' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'anuncio',
                ),
            ),
        ),
    ));

}