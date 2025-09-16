<?php
/**
 * ACF Fields - FAQ Page
 * 
 * Configuração dos campos customizados para a página FAQ
 */

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
        'key' => 'group_faq_page',
        'title' => 'FAQ - Campos da Página',
        'fields' => array(
            array(
                'key' => 'field_faq_subtitle',
                'label' => 'Subtítulo',
                'name' => 'subtitle',
                'type' => 'text',
                'instructions' => 'Digite o subtítulo que será exibido abaixo do título principal da página FAQ.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => 'Ex: Encontre respostas para suas dúvidas mais frequentes',
                'prepend' => '',
                'append' => '',
                'maxlength' => 200,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => 'faq',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Campos customizados para a página de FAQ',
    ));
}
