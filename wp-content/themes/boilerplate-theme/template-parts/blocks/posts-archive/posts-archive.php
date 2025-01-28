<?php
  $dataSource = get_field('data_source');
  $postIds = array();
  
  switch($dataSource) {
    
    case 'dynamic':
      $categories = get_field('filter_by_category') ?: 'all'; 
      // $category = get_query_var('category', 'all');
      $ppp = get_field('ppp') ?: get_option('posts_per_page');
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
      $big = 999999999;
      $paginationBase = str_replace( $big, '%#%', esc_url(get_pagenum_link($big)));

      // Get most recent posts.
      $args = array(
        'post_type' => 'post',
        'orderby'=> 'post_date', 
        'order' => 'DESC',
        'post_status' => array( 'publish' ),
        'posts_per_page'   => $ppp,
        'paged' => $paged,
        'tax_query' => array(
          'relation' => 'OR',
        )
      );

      // Category.
      if ($categories && $categories !== 'all') {
        array_push($args['tax_query'], array(
          'taxonomy' => 'category',
          'field'    => 'term_id',
          'terms'    => $categories,
        ));
      }

      // echo '<pre>';
      // print_r($args);
      // echo '</pre>';

      $wp_query = new WP_Query($args);
      
      $postsCount = count($wp_query->posts);
      $postIds = wp_list_pluck( $wp_query->posts, 'ID' );
      // Only use the first set (based upon $ppp number).
      $postIds = array_slice($postIds, 0, $ppp);
      wp_reset_query();
      break;

    case 'manual':
      $items = get_field('items');
      $postIds = wp_list_pluck( $items, 'item' );
      break;
  }

  // Block Pad.
  $blockPadArray = get_field('block_pad') ?: array('block_padding' => array('block-pad'));
  $blockPad = implode(' ', $blockPadArray['block_padding']);

  $className = 'posts-archive block';
  
  if (!empty($block['align'])) {
    $className .= ' block--'.$block['align'].'-width';
  }
  if (!empty($block['align_text'])) {
    $className .= ' align-text-' . $block['align_text'];
  }
  if (!empty($block['align_content'])) {
    $className .= ' align-content-' . $block['align_content'];
  }

  if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
  }
?>

<section 
  <?php if (array_key_exists('anchor', $block)) { echo "id='{$block['anchor']}'"; } ?>
  class="<?php echo esc_attr($className); ?> body-pad <?php echo $blockPad; ?>" 
  <?php if (get_field('theme')) : ?>
    data-theme="<?php the_field('theme'); ?>"
  <?php endif; ?>
>
  <div class="container">

    <?php if ((get_field('include_block_header') && get_field('block_header_wysiwyg')) || count($postIds) >= 1) : ?>

      <?php
        if (get_field('include_block_header')) : 
          get_template_part('template-parts/components/block-header');
        endif;
      ?>

      <div 
        class="posts-archive__ajax-container" 
        data-ppp="<?php echo $ppp; ?>" 
        data-paged="<?php echo $paged; ?>" 
        data-url-base="<?php echo $paginationBase; ?>"
      >
        <div class="posts-archive__posts theme-grid">
          <?php 
            foreach($postIds as &$postId) {
              global $post;
              $post = get_post($postId);
              setup_postdata($post);   
              get_template_part('template-parts/content/post', 'listing', array());
              wp_reset_postdata();  
            }
          ?>
        </div>

        <?php if (get_field('include_pagination')) : ?>
          <div class="posts-archive__pagination pagination">
            <div class="container u--text-center">
              <?php 
                echo paginate_links( array(
                    'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                    'total'        => $wp_query->max_num_pages,
                    'current'      => max( 1, get_query_var( 'paged' ) ),
                    'format'       => '?paged=%#%',
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
              ?>
            </div>
          </div>
        <?php endif; ?>

      </div>

    <?php else: ?>

      <?php /* Initial display after adding the unpopulated block. */ ?>
      <h2 class="block__placeholder">Posts Archive Block</h2>

    <?php endif; ?>

  </div>
</section>
