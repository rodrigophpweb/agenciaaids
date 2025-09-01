<?php
/**
 * Remove o script de Speculation Rules no WordPress 6.3+
 * @package AgenciaAids
 * @author Rodrigo Vieira Eufrasio da Silva
 * @since 1.0.0
 * @link https://developer.wordpress.org/reference/hooks/wp_head/
 * @link https://developer.wordpress.org/reference/functions/remove_action/
 * @link https://developer.wordpress.org/reference/hooks/after_setup_theme/
 * @link https://developer.wordpress.org/reference/functions/add_action/
 * @link https://developer.wordpress.org/reference/hooks/wp_print_speculation_rules/
 * @link https://developer.wordpress.org/reference/functions/wp_print_speculation_rules/
 * @link https://developer.wordpress.org/reference/functions/wp_head/
 * @link https://developer.wordpress.org/reference/hooks/wp_head/
 * @link https://developer.wordpress.org/reference/hooks/after_setup_theme/
 * @link https://developer.wordpress.org/reference/functions/remove_action/
 * @link https://developer.wordpress.org/reference/functions/add_action/
 * @link https://developer.wordpress.org/reference/hooks/wp_print_speculation_rules/            
 */

function agenciaaids_disable_speculationrules() {
    remove_action('wp_head', 'wp_print_speculation_rules', 0);
}
add_action('after_setup_theme', 'agenciaaids_disable_speculationrules');
