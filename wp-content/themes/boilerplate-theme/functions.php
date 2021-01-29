<?php
/**
 * Disable Gutenberg.
 */
// add_filter('use_block_editor_for_post', '__return_false', 10);

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
add_action( 'wp_print_styles', 'my_deregister_styles', 100 );
function my_deregister_styles()    { 
   wp_deregister_style( 'dashicons' ); 
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
 *    Enqueue styles
 */
add_action( 'wp_enqueue_scripts', 'theme_styles' );
function theme_styles() {
  wp_enqueue_style('style', get_template_directory_uri().'/dist/css/main.css'); 
  wp_enqueue_script('script', get_template_directory_uri().'/dist/js/main.bundle.js');
}

/**
 * WP Menus.
 */
add_action( 'init', 'register_menus' );
function register_menus() {
  register_nav_menu('primary-navigation',__( 'Primary Navigation' ));
}

/**
 *  Images.
 */
if ( function_exists( 'add_image_size' ) ) {
  // add_image_size( "content-image",  1000, 0, false);
  // add_image_size( "square",         1000, 1000, true);
}

// Remove inline width and height attributes on
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );
function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
   return $html;
}

/**
 * Custom Post Types.
 */

// Rename Posts to News.
// add_action( 'init', 'cp_change_post_object' );
// function cp_change_post_object() {
//   $get_post_type = get_post_type_object('post');
//   $labels = $get_post_type->labels;
//   $labels->name = 'News';
//   $labels->singular_name = 'News';
//   $labels->add_new = 'Add News';
//   $labels->add_new_item = 'Add News';
//   $labels->edit_item = 'Edit News';
//   $labels->new_item = 'News';
//   $labels->view_item = 'View News';
//   $labels->search_items = 'Search News';
//   $labels->not_found = 'No News found';
//   $labels->not_found_in_trash = 'No News found in Trash';
//   $labels->all_items = 'All News';
//   $labels->menu_name = 'News';
//   $labels->name_admin_bar = 'News';
// }

// Products.
add_action('init', 'products_register');
function products_register() {
  $labels = array(
    'name' => _x('Products', 'post type general name'),
    'singular_name' => _x('Product', 'post type singular name'),
    'add_new' => _x('Add New', 'Product'),
    'add_new_item' => __('Add New Product'),
    'edit_item' => __('Edit Product'),
    'new_item' => __('New Product'),
    'view_item' => __('View Product'),
    'search_items' => __('search Products'),
    'not_found' =>  __('nothing found'),
    'not_found_in_trash' => __('nothing found in Trash'),
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array(
      'slug' => 'products',
      'with_front' => false,
    ),
    'capability_type' => 'page',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => 0,
    'supports' => array('title', 'thumbnail', 'editor', 'author'),
    'taxonomies' => array(),
    'show_in_rest' => true,
    'show_in_graphql' => true,
    'graphql_single_name' => 'product',
    'graphql_plural_name' => 'products',
  );
  register_post_type('products' , $args);
  flush_rewrite_rules();
}


/**
 * Control order of Admin Menu items.
 */
add_filter( 'custom_menu_order', 'reorder_admin_menu' );
add_filter( 'menu_order', 'reorder_admin_menu' );
function reorder_admin_menu( $__return_true ) {
  return array(
    'index.php', // Dashboard
    'edit.php?post_type=page', // Pages
    'edit.php?post_type=products', // Products
    'edit.php', // Posts
    'upload.php', // Media
    'themes.php', // Appearance
    'separator1', // --Space--
    'users.php', // Users
    'separator2', // --Space--
    'plugins.php', // Plugins
    'tools.php', // Tools
    'options-general.php', // Settings
    'edit-comments.php', // Comments
  );
}

/**
 * Remove items from the Admin Menu.
 */
add_action( 'admin_menu', 'admin_clean');
function admin_clean() {
  remove_menu_page('edit-comments.php');
  //remove_menu_page('edit.php?post_type=acf-field-group');
}

/**
 * ACF Options.
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
    'show_in_graphql' => true
	));
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Footer Settings',
		'menu_title'	=> 'Footer Settings',
		'parent_slug'	=> 'theme-general-settings',
    'show_in_graphql' => true
	));
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Social Settings',
		'menu_title'	=> 'Social Links',
		'parent_slug'	=> 'theme-general-settings',
    'show_in_graphql' => true
	));
}

/**
 *    ACF Blocks.
 */
add_theme_support('editor-styles');
add_theme_support('align-wide');
// remove_theme_support('core-block-patterns');

// add_action( 'enqueue_block_editor_assets', 'theme_admin_enqueue_assets' );
// function theme_admin_enqueue_assets() {
  
//   wp_enqueue_style('style', get_template_directory_uri().'/dist/css/main.css');
  
//   $blockPath = get_template_directory_uri().'/dist/js/main.bundle.js';
//   wp_enqueue_script(
//     'theme-blocks-js',
//     plugins_url( $blockPath, __FILE__ ),
//     [ 'hero' ],
//     filemtime( plugin_dir_path( __FILE__ ) . $blockPath )
//   );
// }

// add_filter( 'block_categories', 'theme_block_categories', 10, 2 );
// function theme_block_categories( $categories, $post ) {
//   return array_merge(
//     $categories,
//     array(
//       array(
//         'slug' => 'boilerplate-theme',
//         'title' => __( 'Boilerplate Theme', 'boilerplate-theme' ),
//         'icon'  => 'arrow-right-alt2',
//       ),
//     )
//   );
// }

// add_action('acf/init', 'my_acf_init_block_types');
// function my_acf_init_block_types() {

//   // Check function exists.
//   if( function_exists('acf_register_block_type') ) {

//     // Dedfine ACF Gutenberg blocks here...
    
//     // Hero example...
//     acf_register_block_type(array(
//       'name'              => 'hero',
//       'title'             => __('Hero'),
//       'description'       => __('Hero'),
//       'render_template'   => 'template-parts/blocks/hero/hero.php',
//       'category'          => 'made-greater',
//       'icon'              => 'arrow-right-alt2',
//       'keywords'          => array( 'content' ),
//       'align'             => 'full',
//       'supports'          => array(
//         'align_text' => false,
//         'align_content' => false,
//         'align'		=> array('full'),
//         'multiple' => false,
//       ),
//     ));
  
//   }
// }

// Hide all and use whitelist to show custom theme blocks.
// add_filter( 'allowed_block_types', 'theme_allowed_block_types' );
// function theme_allowed_block_types( $allowed_blocks ) {
 
// 	return array(
//     'acf/hero',
//     // Add each custom block here...
// 	);
// }

?>
