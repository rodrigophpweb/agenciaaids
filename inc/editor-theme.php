<?php

/**
 * Disable Editor Files Theme Support 
 * @package AgenciaAids 
 * @since 1.0.0
 * @author Rodrigo Vieira Eufrasio da Silva
 * 
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Remove suporte a editor de estilos do Gutenberg
add_action('after_setup_theme', function() {
    remove_theme_support('editor-styles');
});

// Remove o Editor de Temas do menu Aparência
add_action('admin_init', function() {
    remove_submenu_page('themes.php', 'theme-editor.php');
});

// Remove também o Editor de Plugins do menu Plugins (segurança adicional)
add_action('admin_init', function() {
    remove_submenu_page('plugins.php', 'plugin-editor.php');
});
