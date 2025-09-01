<?php
function agenciaaids_theme_setup() {
    add_theme_support('custom-logo', [
        'height'      => 122,
        'width'       => 114,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('post-thumbnails');
    add_theme_support('custom-fields');
}
add_action('after_setup_theme', 'agenciaaids_theme_setup');