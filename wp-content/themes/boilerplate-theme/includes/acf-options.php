<?php
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
?>
