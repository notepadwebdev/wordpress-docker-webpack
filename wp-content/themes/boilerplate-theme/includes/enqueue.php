<?php
/**
 *    Enqueue styles and scripts.
 */
add_action( 'wp_enqueue_scripts', 'theme_styles' );
function theme_styles() {
  wp_enqueue_style('style', get_template_directory_uri().'/dist/css/main.css'); 
  wp_enqueue_script('script', get_template_directory_uri().'/dist/js/main.bundle.js');
}

/**
 * Gutenberg.
 */
add_theme_support( 'editor-styles' ); 
add_editor_style( 'dist/css/main.css' ); 
add_editor_style( 'admin.css' ); 
?>
