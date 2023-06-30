<?php
/**
 * Taxonomies.
 */

// Product Categories.
// add_action('init', 'create_product_cats');
// function create_product_cats() {
//   $labels = array(
//     'name'                       => _x( 'Product Categories', 'taxonomy general name', 'textdomain' ),
//     'singular_name'              => _x( 'Product Category', 'taxonomy singular name', 'textdomain' ),
//     'search_items'               => __( 'Search Product Categories', 'textdomain' ),
//     'popular_items'              => __( 'Popular Product Categories', 'textdomain' ),
//     'all_items'                  => __( 'All Product Categories', 'textdomain' ),
//     'parent_item'                => null,
//     'parent_item_colon'          => null,
//     'edit_item'                  => __( 'Edit Product Category', 'textdomain' ),
//     'update_item'                => __( 'Update Product Category', 'textdomain' ),
//     'add_new_item'               => __( 'Add New Product Category', 'textdomain' ),
//     'new_item_name'              => __( 'New Product Category Name', 'textdomain' ),
//     'separate_items_with_commas' => __( 'Separate product categories with commas', 'textdomain' ),
//     'add_or_remove_items'        => __( 'Add or remove product categories', 'textdomain' ),
//     'choose_from_most_used'      => __( 'Choose from the most used product catgeories', 'textdomain' ),
//     'not_found'                  => __( 'No product categories found.', 'textdomain' ),
//     'menu_name'                  => __( 'Product Categories', 'textdomain' ),
//   );

//   $args = array(
//     'public'                => false,
//     'hierarchical'          => false,
//     'labels'                => $labels,
//     'show_ui'               => true,
//     'show_admin_column'     => true,
//     'update_count_callback' => '_update_post_term_count',
//     'query_var'             => true,
//     'rewrite'               => array( 'slug' => 'product-categories' ),
//     'show_in_rest'          => true,
//   );

//   register_taxonomy( 'product-categories', 'products', $args );
// }
?>
