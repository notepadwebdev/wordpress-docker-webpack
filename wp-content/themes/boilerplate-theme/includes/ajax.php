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
  
  switch ($cpt) {
    case 'post':
      $listingTemplatePath = 'template-parts/content/article-listing';
      break;
  }
  
  $out = '';
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
  die($out);
}
?>
