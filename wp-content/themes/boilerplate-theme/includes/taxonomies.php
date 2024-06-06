<?php
/**
 * Taxonomies.
 */

 function getPrimaryCategory($post) {
  
  $primaryTerm = null;
  $categories = get_the_category($post->ID);
  
  // If multiple categories added AND Yoast is installed, get the primary term.
  if (count($categories) > 1 && function_exists('yoast_get_primary_term_id')) :
    $primaryTermId = yoast_get_primary_term_id('category', $post);
    $primaryTerm = ($primaryTermId && $primaryTermId > 1) ? get_term($primaryTermId) : null;
  endif;

  // If Yoast primary term not set then use the first in the array.
  if (!$primaryTerm && count($categories)) {
    $primaryTerm = ($categories[0]->term_id > 1) ? $categories[0] : null;
  }

  return $primaryTerm;
}

// Services.
// add_action('init', 'create_services');
// function create_services() {
//   $labels = array(
//     'name'                       => _x( 'Services', 'taxonomy general name', 'textdomain' ),
//     'singular_name'              => _x( 'Service', 'taxonomy singular name', 'textdomain' ),
//     'search_items'               => __( 'Search Services', 'textdomain' ),
//     'popular_items'              => __( 'Popular Services', 'textdomain' ),
//     'all_items'                  => __( 'All Services', 'textdomain' ),
//     'parent_item'                => null,
//     'parent_item_colon'          => null,
//     'edit_item'                  => __( 'Edit Service', 'textdomain' ),
//     'update_item'                => __( 'Update Service', 'textdomain' ),
//     'add_new_item'               => __( 'Add New Service', 'textdomain' ),
//     'new_item_name'              => __( 'New Service Name', 'textdomain' ),
//     'separate_items_with_commas' => __( 'Separate services with commas', 'textdomain' ),
//     'add_or_remove_items'        => __( 'Add or remove services', 'textdomain' ),
//     'choose_from_most_used'      => __( 'Choose from the most used services', 'textdomain' ),
//     'not_found'                  => __( 'No services found.', 'textdomain' ),
//     'menu_name'                  => __( 'Services', 'textdomain' ),
//   );

//   $args = array(
//     'public'                => false,
//     'hierarchical'          => false,
//     'labels'                => $labels,
//     'show_ui'               => true,
//     'show_admin_column'     => true,
//     'update_count_callback' => '_update_post_term_count',
//     'query_var'             => true,
//     'rewrite'               => array( 'slug' => 'services' ),
//     'show_in_rest'          => true,
//   );

//   register_taxonomy( 'service', 'post-type', $args );
// }
?>
