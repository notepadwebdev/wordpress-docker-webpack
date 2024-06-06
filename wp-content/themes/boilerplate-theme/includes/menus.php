<?php
/**
 * WP Menus.
 */
add_action( 'init', 'register_menus' );
function register_menus() {
  register_nav_menu('primary-navigation',__( 'Primary Navigation' ));
}

// Nav Walkers.
require get_template_directory().'/includes/primary-nav-walker.php';

/**
 * Remove items from the Admin Menu.
 */
add_action( 'admin_menu', 'admin_clean');
function admin_clean() {
  remove_menu_page('edit-comments.php');
  
  // Webmaster only admin links!
  if (get_current_user_id() !== 1) {
    remove_menu_page('edit.php?post_type=acf-field-group');
    remove_menu_page('plugins.php');
  }
}

/**
 * Reusable Blocks accessible in backend
 * @link https://www.billerickson.net/reusable-blocks-accessible-in-wordpress-admin-area
 *
 */
function be_reusable_blocks_admin_menu() {
  add_menu_page( 'Reusable Blocks', 'Reusable Blocks', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22 );
}
add_action( 'admin_menu', 'be_reusable_blocks_admin_menu' );

/**
 * Control order of Admin Menu items.
 */
add_filter( 'custom_menu_order', 'reorder_admin_menu' );
add_filter( 'menu_order', 'reorder_admin_menu' );
function reorder_admin_menu( $__return_true ) {
  return array(
    'index.php', // Dashboard
    'edit.php?post_type=page', // Pages
    //'edit.php?post_type=products', // Products
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
?>
