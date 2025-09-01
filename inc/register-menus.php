<?php
add_action('init', function () {
    register_nav_menus([
        'header-menu' => __('Menu do Cabeçalho'),
        'footer-menu' => __('Menu do Rodapé'),
    ]);
});