<?php
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
    'has_archive' => false,
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

// Product Categories.
add_action('init', 'create_product_cats');
function create_product_cats() {
  $labels = array(
    'name'                       => _x( 'Product Categories', 'taxonomy general name', 'textdomain' ),
    'singular_name'              => _x( 'Product Category', 'taxonomy singular name', 'textdomain' ),
    'search_items'               => __( 'Search Product Categories', 'textdomain' ),
    'popular_items'              => __( 'Popular Product Categories', 'textdomain' ),
    'all_items'                  => __( 'All Product Categories', 'textdomain' ),
    'parent_item'                => null,
    'parent_item_colon'          => null,
    'edit_item'                  => __( 'Edit Product Category', 'textdomain' ),
    'update_item'                => __( 'Update Product Category', 'textdomain' ),
    'add_new_item'               => __( 'Add New Product Category', 'textdomain' ),
    'new_item_name'              => __( 'New Product Category Name', 'textdomain' ),
    'separate_items_with_commas' => __( 'Separate product categories with commas', 'textdomain' ),
    'add_or_remove_items'        => __( 'Add or remove product categories', 'textdomain' ),
    'choose_from_most_used'      => __( 'Choose from the most used product catgeories', 'textdomain' ),
    'not_found'                  => __( 'No product categories found.', 'textdomain' ),
    'menu_name'                  => __( 'Product Categories', 'textdomain' ),
  );

  $args = array(
    'public'                => false,
    'hierarchical'          => false,
    'labels'                => $labels,
    'show_ui'               => true,
    'show_admin_column'     => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var'             => true,
    'rewrite'               => array( 'slug' => 'product-categories' ),
    'show_in_rest'          => true,
  );

  register_taxonomy( 'product-categories', 'products', $args );
}
?>
