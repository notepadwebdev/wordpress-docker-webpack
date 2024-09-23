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
  $jsVer = null;
  if (strpos($_SERVER['SERVER_NAME'], 'staging') !== false) {
    $parentDir = realpath(__DIR__ . '/..');
    $jsVer = filemtime( $parentDir . '/dist/js/main.bundle.js' );
  }

  /**
   *  Register block specific scripts.
   */
  wp_register_script('block-posts-archive', get_template_directory_uri() . '/dist/js/posts-archive.bundle.js', array('jquery'), $jsVer, true);

  /**
   *  Localise AJAX dependent scripts.
   */
  wp_localize_script('block-posts-archive', 'ajax_params', array(
    'ajaxurl' => admin_url('admin-ajax.php'),
    'current_page' => get_query_var('page') ? get_query_var('page') : 1
  ));
  
  /**
   *  Register custom blocks.
   */ 
  register_block_type( $parentDir . '/template-parts/blocks/content-block' );
  register_block_type( $parentDir . '/template-parts/blocks/posts-archive' );
}


/**
 * 
 *    Use whitelist for allowed blocks.
 * 
 */
add_filter( 'allowed_block_types_all', 'theme_allowed_block_types' );
function theme_allowed_block_types( $allowed_blocks ) {
  
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
