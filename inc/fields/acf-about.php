<?php

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group([
        'key' => 'group_colaboradores_quem_somos',
        'title' => 'Colaboradores - Página Quem Somos',
        'fields' => [
            [
                'key' => 'field_collaborators_repeater',
                'label' => 'Colaboradores',
                'name' => 'collaborators_repeater',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Adicionar Colaborador',
                'sub_fields' => [
                    [
                        'key' => 'field_foto_collaborator',
                        'label' => 'Foto do Colaborador',
                        'name' => 'foto_collaborator',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ],
                    [
                        'key' => 'field_nome_collaborator',
                        'label' => 'Nome do Colaborador',
                        'name' => 'nome_collaborator',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_cargo_collaborator',
                        'label' => 'Cargo',
                        'name' => 'cargo_collaborator',
                        'type' => 'text',
                        'instructions' => 'Ex: Coordenador Geral, Jornalista, etc.',
                    ],
                    [
                        'key' => 'field_descricao_collaborator',
                        'label' => 'Descrição',
                        'name' => 'descricao_collaborator',
                        'type' => 'textarea',
                    ],
                    [
                        'key' => 'field_email_collaborator',
                        'label' => 'E-mail',
                        'name' => 'email_collaborator',
                        'type' => 'email',
                    ],
                    [
                        'key' => 'field_facebook_collaborator',
                        'label' => 'Facebook',
                        'name' => 'facebook_collaborator',
                        'type' => 'url',
                    ],
                    [
                        'key' => 'field_instagram_collaborator',
                        'label' => 'Instagram',
                        'name' => 'instagram_collaborator',
                        'type' => 'url',
                    ],
                    [
                        'key' => 'field_linkedin_collaborator',
                        'label' => 'LinkedIn',
                        'name' => 'linkedin_collaborator',
                        'type' => 'url',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 7,
                ],
            ],
        ],
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
        'description' => '',
    ]);
}
