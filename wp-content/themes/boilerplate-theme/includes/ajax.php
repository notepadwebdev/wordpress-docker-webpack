<?php

/** 
 *  Posts.
 */
add_action('wp_ajax_ajax_posts', 'ajax_posts');
add_action('wp_ajax_nopriv_ajax_posts', 'ajax_posts');

function ajax_posts(){ 
  $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 0;
  $cpt = (isset($_POST['cpt'])) ? $_POST['cpt'] : 'post';
  $taxonomy = (isset($_POST['taxonomy'])) ? $_POST['taxonomy'] : 'category';
  $category = (isset($_POST['category'])) ? $_POST['category'] : null;
  header("Content-Type: text/html");
  
  $args = array(
    'post_type' => $cpt,
    'orderby' => 'date',
    'order' => 'DESC',
    'paged'    => $page,
    'posts_per_page' => $_POST['ppp'],
    'post_status' => 'publish',
  );
  
  if ($category && $category !== 'all') {
    $args['tax_query'] = array(
      array(
        'taxonomy' => $taxonomy,
        'field'    => 'slug',
        'terms'    => $category,
      ),
    );
  }
  
	$loop = new WP_Query($args);
  
  $out = '<div class="posts-archive__posts theme-grid">';

  $listingTemplatePath = "template-parts/content/{$cpt}-listing";
  if ($loop->have_posts()) :  while ($loop->have_posts()) : $loop->the_post();
    ob_start();
    global $post;
    $post = get_post($postId);
    setup_postdata($post); 
    get_template_part($listingTemplatePath, null, array()); 
    wp_reset_postdata();
    $out .= ob_get_clean();
  endwhile;
  endif;
  wp_reset_postdata();
  $out .= '<div class="ajax-data" data-max-pages="'.$loop->max_num_pages.'"></div>';
  $out .= '</div>';

  // Pagination.
  if ($loop->max_num_pages > 1) :
    $out .= '<div class="posts-archive__pagination">';
    $out .= '<div class="container u--text-center">';
               
    if (!isset($max_page)) {
      $max_page = isset( $loop->max_num_pages ) ? $loop->max_num_pages : 1;
    }
    
    $out .= paginate_links( array(
      'base'         => $_POST['urlBase'],
      'total'        => $max_page,
      'current'      => max( 1, $page),
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
    
    $out .= '</div></div>';
  endif;

  die($out);
}
?>
