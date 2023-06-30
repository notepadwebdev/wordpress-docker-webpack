<?php
/**
 * WP Menus.
 */
add_action( 'init', 'register_menus' );
function register_menus() {
  register_nav_menu('primary-navigation',__( 'Primary Navigation' ));
}

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
