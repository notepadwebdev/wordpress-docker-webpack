<?php
/**
 *    ACF Blocks.
 */

add_theme_support('align-wide');
remove_theme_support('core-block-patterns');

/**
 *    Custom Block Categories.
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
 *    Custom Blocks.
 */
add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

  // Check function exists.
  if( function_exists('acf_register_block_type') ) {

    // Hero.
    acf_register_block_type(array(
      'name'              => 'hero',
      'title'             => __('Hero'),
      'description'       => __('Hero'),
      'render_template'   => 'template-parts/blocks/hero/hero.php',
      'category'          => 'custom-blocks',
      'icon'              => 'arrow-right-alt2',
      'keywords'          => array( 'content' ),
      'align'             => 'full',
      'supports'          => array(
        'align_text' => false,
        'align_content' => false,
        'align'		=> array('full'),
        'multiple' => false,
      ),
    ));
  }
}

/**
 *    Use whitelist for allowed blocks.
 */
add_filter( 'allowed_block_types_all', 'theme_allowed_block_types' );
function theme_allowed_block_types( $allowed_blocks ) {
   return array(
    'core/image',
    'acf/hero',
    // Add each custom block here...
  );
}

?>
