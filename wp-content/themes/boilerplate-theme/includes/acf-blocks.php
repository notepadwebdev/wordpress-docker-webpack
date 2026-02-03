<?php
/**
 *    ACF Blocks.
 */

add_theme_support('align-wide');
remove_theme_support('core-block-patterns');


/**
 * 
 *    Custom Block Categories.
 * 
 */
add_filter( 'block_categories_all', 'theme_block_categories', 10, 2 );
function theme_block_categories( $categories, $post ) {
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'custom-blocks',
        'title' => __( 'Custom Blocks', 'custom-blocks' ),
        'icon'  => 'arrow-right-alt2',
      ),
      array(
        'slug' => 'archive-blocks',
        'title' => __( 'Archives', 'archive-blocks' ),
        'icon'  => 'portfolio',
      ),
    )
  );
}


/**
 * 
 *    Custom Blocks.
 * 
 */
add_action( 'init', 'register_acf_blocks', 5 );
function register_acf_blocks() {

  $parentDir = realpath(__DIR__ . '/..');

  // JS versioning based on last time main.bundle was updated (only on staging site).
  $cssVer = null;
  $jsVer = null;
  $server_name = isset($_SERVER['SERVER_NAME']) ? sanitize_text_field($_SERVER['SERVER_NAME']) : '';
  if (strpos($server_name, 'staging') !== false) {
    // CSS.
    $css_path = $parentDir . '/dist/css/styles.css';
    $cssVer = file_exists($css_path) ? filemtime($css_path) : null;
    // JS.
    $js_path = $parentDir . '/dist/js/main.bundle.js';
    $jsVer = file_exists($js_path) ? filemtime($js_path) : null;
  }

  // Register 3rd Party Libs.
  // wp_register_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=ADD_KEY_HERE&callback=Function.prototype', array(), '', true);
  // wp_register_script('swiper', get_template_directory_uri() . '/dist/js/vendor/swiper.min.js', array(), '', true);

  /**
   *  Register custom modules.
   */
  // wp_register_script('video-youtube', get_template_directory_uri() . '/dist/js/video-youtube.bundle.js', array('jquery'), $cssVer, true);
  // wp_register_script('video-mp4', get_template_directory_uri() . '/dist/js/video-mp4.bundle.js', array('jquery'), $cssVer, true);

  /**
   *  Register block specific scripts.
   */
  wp_register_script('block-posts-archive', get_template_directory_uri() . '/dist/js/posts-archive.bundle.js', array('jquery'), $jsVer, true);
  // wp_register_script('block-content-block', get_template_directory_uri() . '/dist/js/content-block.bundle.js', array('jquery', 'video-youtube', 'video-mp4'), $jsVer, true);
  

  /**
   *  Localise AJAX dependent scripts.
   */
  wp_localize_script('block-posts-archive', 'ajax_params', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'current_page' => get_query_var('page') ? get_query_var('page') : 1
  ));
  
  /**
   *  Register custom blocks (with directory existence checks).
   */
  $content_block_dir = $parentDir . '/template-parts/blocks/content-block';
  $posts_archive_dir = $parentDir . '/template-parts/blocks/posts-archive';

  if ( is_dir( $content_block_dir ) ) {
    register_block_type( $content_block_dir );
  }
  if ( is_dir( $posts_archive_dir ) ) {
    register_block_type( $posts_archive_dir );
  }
}


/**
 * 
 *    Use whitelist for allowed blocks.
 * 
 */
add_filter( 'allowed_block_types_all', 'theme_allowed_block_types' );
function theme_allowed_block_types( $allowed_blocks, $editor_context = null ) {
  
  // Dump a list of all block slugs.
  // $registered_block_slugs = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
  // echo '<pre>' . print_r( $registered_block_slugs, true ) . '</pre>';
  
  return array(
    'core/block', // Required for reusable blocks functionality.
    'acf/content-block',
    'acf/posts-archive',
  );
}

// Ensure block scripts are only loaded on pages where the block is present.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

?>
