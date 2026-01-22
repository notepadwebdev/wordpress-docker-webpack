<?php

/** 
 *  Posts.
 */
add_action('wp_ajax_ajax_posts', 'ajax_posts');
add_action('wp_ajax_nopriv_ajax_posts', 'ajax_posts');

/**
 * Get available terms for a taxonomy based on current filters
 * 
 * @param string $target_taxonomy The taxonomy to get terms for
 * @param array $all_taxonomies All current taxonomy filters
 * @param string $cpt Post type
 * @param array $filterByTerms Filter restrictions from filter_results_by_terms
 * @return array Array of term objects with ID, name, and count
 */
function get_available_terms_for_taxonomy($target_taxonomy, $all_taxonomies, $cpt = 'post', $filterByTerms = array()) {
  // Build query args excluding the target taxonomy
  $args = array(
    'post_type' => $cpt,
    'posts_per_page' => -1,
    'fields' => 'ids',
    'post_status' => 'publish',
  );
  
  // Build tax_query excluding the target taxonomy
  $other_taxonomies = array_filter($all_taxonomies, function($tax) use ($target_taxonomy) {
    return $tax !== $target_taxonomy;
  }, ARRAY_FILTER_USE_KEY);
  
  if (!empty($other_taxonomies)) {
    $tax_query = array('relation' => 'AND');
    
    foreach ($other_taxonomies as $taxonomy => $term_ids) {
      if (!is_array($term_ids)) {
        $term_ids = array($term_ids);
      }
      
      $term_ids = array_filter($term_ids, function($id) {
        return $id && $id !== 'all';
      });
      
      if (!empty($term_ids)) {
        $tax_query[] = array(
          'taxonomy' => $taxonomy,
          'field'    => 'term_id',
          'terms'    => $term_ids,
          'operator' => 'IN',
        );
      }
    }
    
    if (count($tax_query) > 1) {
      $args['tax_query'] = $tax_query;
    }
  }
  
  // Apply filter_by_terms restrictions
  if (!empty($filterByTerms)) {
    if (!isset($args['tax_query'])) {
      $args['tax_query'] = array('relation' => 'OR');
    } else {
      $existing_tax_query = $args['tax_query'];
      $args['tax_query'] = array(
        'relation' => 'AND',
        $existing_tax_query
      );
    }
    
    $filter_tax_query = array('relation' => 'OR');
    foreach ($filterByTerms as $term_restriction) {
      $filter_tax_query[] = array(
        'taxonomy' => $term_restriction['taxonomy'],
        'field'    => 'term_id',
        'terms'    => $term_restriction['term_id'],
        'operator' => 'IN',
      );
    }
    
    if (count($filter_tax_query) > 1) {
      if (!isset($args['tax_query'])) {
        $args['tax_query'] = $filter_tax_query;
      } else {
        $args['tax_query'][] = $filter_tax_query;
      }
    }
  }
  
  // Get posts matching the other filters
  $query = new WP_Query($args);
  $post_ids = $query->posts;
  wp_reset_postdata();
  
  if (empty($post_ids)) {
    return array();
  }
  
  // Get all terms for target taxonomy that are used by these posts
  global $wpdb;
  $post_ids_str = implode(',', array_map('intval', $post_ids));
  
  $results = $wpdb->get_results($wpdb->prepare(
    "SELECT t.term_id, t.name, t.slug, COUNT(DISTINCT p.ID) as count
    FROM {$wpdb->terms} t
    INNER JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
    INNER JOIN {$wpdb->term_relationships} tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
    INNER JOIN {$wpdb->posts} p ON tr.object_id = p.ID
    WHERE tt.taxonomy = %s
    AND p.ID IN ({$post_ids_str})
    AND p.post_status = 'publish'
    GROUP BY t.term_id
    ORDER BY t.name ASC",
    $target_taxonomy
  ));
  
  // Filter results to only include terms that are in filter_by_terms (if restrictions exist)
  if (!empty($filterByTerms)) {
    $allowed_term_ids = array();
    foreach ($filterByTerms as $term_restriction) {
      if ($term_restriction['taxonomy'] === $target_taxonomy) {
        $allowed_term_ids[] = $term_restriction['term_id'];
      }
    }
    
    // Only apply filter if there are allowed terms for this taxonomy
    if (!empty($allowed_term_ids)) {
      $results = array_filter($results, function($term) use ($allowed_term_ids) {
        return in_array($term->term_id, $allowed_term_ids);
      });
    }
  }
  
  return $results;
}

function ajax_posts() { 
  $cpt = (isset($_POST['cpt'])) ? json_decode(stripslashes($_POST['cpt']), true) : array('post');
  $urlBase = (isset($_POST['urlBase'])) ? $_POST['urlBase'] : '';
  $pageNumber = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
  $includePagination = (isset($_POST['includePagination'])) ? $_POST['includePagination'] : 0;
  
  // Parse taxonomies JSON
  $taxonomies = array();
  if (isset($_POST['taxonomies']) && !empty($_POST['taxonomies'])) {
    $taxonomies = json_decode(stripslashes($_POST['taxonomies']), true);
    if (!is_array($taxonomies)) {
      $taxonomies = array();
    }
  }
  
  // Parse filter_by_terms restrictions
  $filterByTerms = array();
  if (isset($_POST['filterByTerms']) && !empty($_POST['filterByTerms'])) {
    $filterByTerms = json_decode(stripslashes($_POST['filterByTerms']), true);
    if (!is_array($filterByTerms)) {
      $filterByTerms = array();
    }
  }
  
  // Change to JSON response
  header("Content-Type: application/json");
  
  $args = array(
    'post_type' => $cpt,
    'orderby' => 'date',
    'order' => 'DESC',
    'paged'    => $pageNumber,
    'posts_per_page' => $_POST['ppp'],
    'post_status' => 'publish',
  );
  
  // Build tax_query if we have taxonomies
  if (!empty($taxonomies)) {
    $tax_query = array('relation' => 'AND');
    
    foreach ($taxonomies as $taxonomy => $term_ids) {
      // Ensure term_ids is an array
      if (!is_array($term_ids)) {
        $term_ids = array($term_ids);
      }
      
      // Filter out any 'all' values
      $term_ids = array_filter($term_ids, function($id) {
        return $id && $id !== 'all';
      });
      
      // Only add to tax_query if we have valid term IDs
      if (!empty($term_ids)) {
        $tax_query[] = array(
          'taxonomy' => $taxonomy,
          'field'    => 'term_id',
          'terms'    => $term_ids,
          'operator' => 'IN', // Use 'IN' to match any of the provided term IDs
        );
      }
    }
    
    // Only add tax_query if we have actual conditions
    if (count($tax_query) > 1) { // More than just the 'relation' key
      $args['tax_query'] = $tax_query;
    }
  }
  
  // Apply filter_by_terms restrictions (these are always applied, even if no user filters are selected)
  if (!empty($filterByTerms)) {
    // If we don't have a tax_query yet, create one
    if (!isset($args['tax_query'])) {
      $args['tax_query'] = array('relation' => 'OR');
    } else {
      // If we already have a tax_query from user filters, we need to combine them
      // Wrap existing tax_query in an AND relation with the filter_by_terms
      $existing_tax_query = $args['tax_query'];
      $args['tax_query'] = array(
        'relation' => 'AND',
        $existing_tax_query
      );
    }
    
    // Build the filter_by_terms restriction query
    $filter_tax_query = array('relation' => 'OR');
    foreach ($filterByTerms as $term_restriction) {
      $filter_tax_query[] = array(
        'taxonomy' => $term_restriction['taxonomy'],
        'field'    => 'term_id',
        'terms'    => $term_restriction['term_id'],
        'operator' => 'IN',
      );
    }
    
    // Add the filter restrictions to the main query
    if (count($filter_tax_query) > 1) {
      if (!isset($args['tax_query'])) {
        $args['tax_query'] = $filter_tax_query;
      } else {
        $args['tax_query'][] = $filter_tax_query;
      }
    }
  }
  
	$loop = new WP_Query($args);
  
  // Build posts HTML
  $postsHtml = '<div class="posts-archive__posts theme-grid">';

  $listingTemplatePath = "template-parts/content/post-listing";
  if ($loop->have_posts()) :  while ($loop->have_posts()) : $loop->the_post();
    ob_start();
    get_template_part($listingTemplatePath, null, array()); 
    $postsHtml .= ob_get_clean();
  endwhile;
  endif;
  wp_reset_postdata();
  $postsHtml .= '</div>';

  // Build pagination HTML
  $paginationHtml = '';
  if ($includePagination && $loop->max_num_pages > 1) :
    $paginationHtml .= '<div class="posts-archive__pagination pagination">';
               
    $max_page = isset( $loop->max_num_pages ) ? $loop->max_num_pages : 1;
    
    $paginationHtml .= paginate_links( array(
      'base'         => $urlBase,
      'total'        => $max_page,
      'current'      => max( 1, $pageNumber),
      'format'       => '?page=%#%',
      'show_all'     => false,
      'type'         => 'list',
      'end_size'     => 3,
      'mid_size'     => 3,
      'prev_next'    => true,
      'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer posts', 'text-domain' ) ),
      'next_text'    => sprintf( '%1$s <i></i>', __( 'Older posts', 'text-domain' ) ),
      'add_args'     => false,
      'add_fragment' => '',
    ));
    
    $paginationHtml .= '</div>';
  endif;

  // Get available terms for each taxonomy that exists in the filter
  $availableTerms = array();
  
  // Get all unique taxonomies from the posts
  if ($loop->have_posts()) {
    // Get taxonomies used in the current result set
    $used_taxonomies = get_object_taxonomies($cpt, 'names');
    
    foreach ($used_taxonomies as $taxonomy) {
      // Skip post_format as it's usually not user-facing
      if ($taxonomy === 'post_format') {
        continue;
      }
      
      $availableTerms[$taxonomy] = get_available_terms_for_taxonomy($taxonomy, $taxonomies, $cpt, $filterByTerms);
    }
  }

  // Build response
  $response = array(
    'success' => true,
    'postsHtml' => $postsHtml,
    'paginationHtml' => $paginationHtml,
    'maxPages' => $loop->max_num_pages,
    'availableTerms' => $availableTerms,
    'totalPosts' => $loop->found_posts,
  );

  wp_die(json_encode($response));
}
?>
