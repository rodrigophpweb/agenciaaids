<?php
// Arquivo: acf-speakers.php
// Incluir no functions.php ou via require_once no seu tema

if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group([
        'key' => 'group_speakers',
        'title' => 'Informações do Palestrante',
        'fields' => [
            [
                'key' => 'field_email_palestrante',
                'label' => 'E-mail',
                'name' => 'email_palestrante',
                'type' => 'email',
                'instructions' => 'Insira o e-mail profissional do palestrante.',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
            [
                'key' => 'field_cidade_palestrante',
                'label' => 'Cidade',
                'name' => 'cidade_palestrante',
                'type' => 'text',
                'instructions' => 'Ex: São Paulo - SP',
                'required' => 0,
                'wrapper' => [
                    'width' => '50',
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'palestrantes',
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'acf_after_title',
        'style' => 'seamless',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => ['the_content'],
        'active' => true,
        'description' => '',
    ]);
}
