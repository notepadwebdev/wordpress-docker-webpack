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
}
add_action( 'wp_footer', 'my_deregister_scripts' );
function my_deregister_scripts(){
  wp_deregister_script( 'wp-embed' );
}

/**
 * WP Menus.
 */
add_action( 'init', 'register_menus' );
function register_menus() {
  register_nav_menu('primary-navigation',__( 'Primary Navigation' ));
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

// Case Studies.
add_action('init', 'case_studies_register');
function case_studies_register() {
  $labels = array(
    'name' => _x('Case Studies', 'post type general name'),
    'singular_name' => _x('Case Study', 'post type singular name'),
    'add_new' => _x('Add New', 'Case Study'),
    'add_new_item' => __('Add New Case Study'),
    'edit_item' => __('Edit Case Study'),
    'new_item' => __('New Case Study'),
    'view_item' => __('View Case Study'),
    'search_items' => __('search Case Studies'),
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
      'slug' => 'case-studies',
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
    'graphql_single_name' => 'caseStudy',
    'graphql_plural_name' => 'caseStudies',
  );
  register_post_type('case-studies' , $args);
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
    'edit.php?post_type=case-studies', // Case Studies
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
  remove_menu_page('edit.php?post_type=acf-field-group');
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
		'page_title' 	=> 'Social Settings',
		'menu_title'	=> 'Social Links',
		'parent_slug'	=> 'theme-general-settings',
    'show_in_graphql' => true
	));
}

?>
