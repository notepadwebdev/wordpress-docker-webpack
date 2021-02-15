<?php
/**
 * Remove Wordpress things that we don't want.
 */
add_filter( 'init', 'theme_clean');
function theme_clean() {
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'print_emoji_detection_script', 7 );
  remove_action('wp_print_styles', 'print_emoji_styles' );
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'index_rel_link');
  add_filter( 'emoji_svg_url', '__return_false' );
  //add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
}
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css(){
  wp_dequeue_style( 'wp-block-library' );
  wp_dequeue_style( 'wp-block-library-theme' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );
function my_deregister_scripts(){
  wp_deregister_script( 'wp-embed' );
}

/**
 * Gutenberg.
 */
// Disable colors.
add_theme_support( 'editor-color-palette' );
add_theme_support( 'disable-custom-colors' );
// Disable font sizes.
add_theme_support( 'editor-font-sizes', [] );
add_theme_support( 'disable-custom-font-sizes' );
?>
