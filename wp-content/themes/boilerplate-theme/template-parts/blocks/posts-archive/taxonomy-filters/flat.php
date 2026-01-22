<?php 
  /* 
  *
  * Flat menu.
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
    <li>
      <button class="posts-archive__taxonomy-filters__reset"><?php echo esc_html($resetLabel); ?></button>
    </li>
  <?php endif; ?>

  <?php 
    // All terms.
    while (have_rows('filterable_terms')) : the_row();
      $term = get_sub_field('term');
      
      // Skip this term if it's not in the allowed list (when restrictions exist)
      if (!empty($allowedTermIds) && !in_array($term->term_id, $allowedTermIds)) {
        continue;
      }
      
      $label = get_sub_field('label') ?: $term->name;
      ?>
      <li 
        data-term-id="<?php echo esc_attr($term->term_id); ?>" 
        data-taxonomy="<?php echo esc_attr($term->taxonomy); ?>"
      >
        <button><?php echo esc_html($label); ?></button>
      </li>
  <?php endwhile; ?>
</ul>