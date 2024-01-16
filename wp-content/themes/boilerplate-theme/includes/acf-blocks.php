<?php
/**
 *    ACF Blocks.
 */

add_theme_support('align-wide');
add_theme_support( 'editor-styles' );
remove_theme_support('core-block-patterns');
add_editor_style( 'dist/css/styles.css' );
add_editor_style( 'admin.css' );


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

  /**
   *  Register block specific scripts.
   */
  //wp_register_script('block-template', get_template_directory_uri() . '/dist/js/template.bundle.js', array('jquery'), '', true);

  // Localise AJAX dependent scripts.
  // wp_localize_script('block-template', 'ajax_params', array(
  //   'ajaxurl' => admin_url('admin-ajax.php'),
  //   'current_page' => get_query_var('paged') ? get_query_var('paged') : 1
  // ));
  
  /**
   *  Register custom blocks.
   */ 
  register_block_type( $parentDir . '/template-parts/blocks/content-block' );
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
  );
}

?>
