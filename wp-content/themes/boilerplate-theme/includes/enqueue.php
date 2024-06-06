<?php
/**
 *    Enqueue styles and scripts.
 */

// Site wide styles/JS.
add_action( 'wp_enqueue_scripts', 'theme_scripts' );
function theme_scripts() {

  // CSS / JS versioning based on last time files was updated (only on staging site).
  $ver = null;
  if (strpos($_SERVER['SERVER_NAME'], 'staging') !== false) {
    $parentDir = realpath(__DIR__ . '/..');
    $cssVer = filemtime( $parentDir . '/dist/css/styles.css' );
    $jsVer = filemtime( $parentDir . '/dist/js/main.bundle.js' );
  }

  wp_enqueue_style('style', get_template_directory_uri().'/dist/css/styles.css', array(), $cssVer);
  wp_enqueue_script('script', get_template_directory_uri().'/dist/js/main.bundle.js', array(), $jsVer);
}

// CMS JS.
add_action('enqueue_block_editor_assets', 'admin_load_block_scripts');
function admin_load_block_scripts() {
	wp_enqueue_script('cms-js', get_template_directory_uri().'/dist/js/cms.bundle.js');
}

/**
 * Gutenberg.
 */
add_theme_support( 'editor-styles' ); 
add_editor_style( 'dist/css/main.css' ); 
add_editor_style( 'admin.css' ); 
?>
