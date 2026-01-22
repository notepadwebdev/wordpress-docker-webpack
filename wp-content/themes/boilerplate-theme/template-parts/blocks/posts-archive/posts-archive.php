<?php
  $dataSource = get_field('data_source');
  $postIds = array();
  
  switch($dataSource) {
    
    case 'dynamic':
      $postTypes = get_field('post_types');
      $cpt = $postTypes ? array_map(function($post_type) { return is_object($post_type) ? $post_type->name : $post_type; }, $postTypes) : array();
      $ppp = get_field('ppp') ?: get_option('posts_per_page');
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
      $big = 999999999;
      $paginationBase = str_replace( $big, '%#%', esc_url(get_pagenum_link($big)));
      $filterByTerms = get_field('filter_results_by_terms') ?: 'all'; 

      // Get most recent posts.
      $args = array(
        'post_type' => $cpt,
        'orderby'=> 'post_date', 
        'order' => 'DESC',
        'post_status' => array( 'publish' ),
        'posts_per_page'   => $ppp,
        'paged' => $paged,
        'tax_query' => array()
      );

      // Restrict results by terms.
      if ($filterByTerms && $filterByTerms !== 'all') {
        $tax_query = array('relation' => 'OR');
        
        foreach ($filterByTerms as $term) {
          $tax_query[] = array(
            'taxonomy' => $term->taxonomy,
            'field'    => 'term_id',
            'terms'    => $term->term_id,
            'operator' => 'IN',
          );
        }
        
        if (count($tax_query) > 1) {
          $args['tax_query'] = optimize_tax_query($tax_query);
        }
      }

      $wp_query = new WP_Query($args);
      
      $postsCount = count($wp_query->posts);
      $postIds = wp_list_pluck( $wp_query->posts, 'ID' );
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

      <?php 
        if (have_rows('taxonomy_filters')) : 
          while (have_rows('taxonomy_filters')) : the_row(); 
            if (get_sub_field('include_filters')) : 
              $menuStructure = get_sub_field('menu_structure');
              ?>
            
              <div 
                class="posts-archive__taxonomy-filters" 
                data-structure="<?php echo esc_attr($menuStructure); ?>"
              >                
                <?php 
                  get_template_part("template-parts/blocks/posts-archive/taxonomy-filters/{$menuStructure}");
                ?>
              </div>

              <?php 
            endif;
          endwhile;
        endif; 
      ?>

      <div 
        class="posts-archive__ajax-container" 
        <?php if ($dataSource === 'dynamic') : ?>
          data-cpt="<?php echo implode(',', $cpt); ?>" 
          data-ppp="<?php echo $ppp; ?>" 
          data-paged="<?php echo $paged; ?>" 
          data-url-base="<?php echo $paginationBase; ?>" 
          data-include-pagination="<?php echo get_field('include_pagination') ? 1 : 0; ?>" 
          <?php if ($filterByTerms && $filterByTerms !== 'all') : ?>
            data-filter-by-terms="<?php echo esc_attr(json_encode(array_map(function($term) { 
              return array('term_id' => $term->term_id, 'taxonomy' => $term->taxonomy); 
            }, $filterByTerms))); ?>" 
          <?php endif; ?>
          <?php if (get_field('update_url_on_change')) : ?>
            data-update-url="1" 
          <?php endif; ?>
        <?php endif; ?>
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
            <?php 
              get_template_part('template-parts/components/pagination', null, array(
                'total' => $wp_query->max_num_pages,
                'current' => max(1, get_query_var('paged'))
              ));
            ?>
          </div>
        <?php endif; ?>

        <?php if ($dataSource == 'manual') : ?>
          <?php 
            $ctas = get_field('footer_cta');
            if ($ctas && is_array($ctas)) :
              get_template_part('template-parts/components/ctas-list', null, $ctas); 
            endif;
          ?>  
        <?php endif; ?>

      </div>

    <?php else: ?>

      <?php /* Initial display after adding the unpopulated block. */ ?>
      <h2 class="block__placeholder">Posts Archive Block</h2>

    <?php endif; ?>

  </div>
</section>
