<?php 
  /* 
  *
  * Multi menu.
  * 
  */ 

  // Get filter_by_terms restrictions from parent scope
  $filterByTerms = get_field('filter_results_by_terms');
  $allowedTermIds = array();
  
  // Build list of allowed term IDs if restrictions exist
  if ($filterByTerms && $filterByTerms !== 'all') {
    foreach ($filterByTerms as $term) {
      $allowedTermIds[] = $term->term_id;
    }
  }
?>
<ul>
  <?php 
    // Reset link.
    $resetLabel = get_sub_field('reset_label');
    if ($resetLabel) : 
  ?>
    <li >
      <button class="posts-archive__taxonomy-filters__reset"><?php echo esc_html($resetLabel); ?></button>
    </li>
  <?php endif; ?>

  <?php 
    // All taxonomies.
    while (have_rows('filterable_taxonomies')) : the_row();
      $taxonomy = get_sub_field('taxonomy');
      $label = get_sub_field('label') ?: $taxonomy->label;
      ?>
      <li>
        <?php // print_r($taxonomy); ?>
        <?php
          $terms = get_terms(array(
            'taxonomy' => $taxonomy->name,
            'hide_empty' => false,
          ));
          
          // Filter terms if restrictions exist
          if (!empty($allowedTermIds)) {
            $terms = array_filter($terms, function($term) use ($allowedTermIds) {
              return in_array($term->term_id, $allowedTermIds);
            });
          }
        ?>
        <select
          name="filter-taxonomy-<?php echo $taxonomy->name; ?>[]" 
          id="filter-taxonomy-<?php echo $taxonomy->name; ?>" 
          data-taxonomy="<?php echo $taxonomy->name; ?>"
        >
          <option value="all"><?php echo $label; ?></option>
          <?php foreach ($terms as $term) : ?>
            <option 
              value="<?php echo esc_attr($term->term_id); ?>" 
            >
              <?php echo esc_html($term->name); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </li>
  <?php endwhile; ?>
</ul>