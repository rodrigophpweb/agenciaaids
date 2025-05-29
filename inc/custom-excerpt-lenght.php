<?php
/**
 * Template Name: Arquivo
 * Template Post Type: page
 *
 * @package AgenciaAids
 * @since 1.0.0
 */
function agencia_aids_custom_excerpt_length($length) {
    return 15; // ou 25, ajuste conforme o design
}
add_filter('excerpt_length', 'agencia_aids_custom_excerpt_length');