<?php 
  // echo '<pre>';
  // print_r($args); 
  // echo '</pre>';

  $validCtas = getValidCtas($args);
  if (!$validCtas) {
    return;
  }

  $ctas = isset($args['ctas']) && is_array($args['ctas']) ? $args['ctas'] : [];
  $align = isset($args['align']) ? $args['align'] : '';
?>

<?php if (count($ctas) > 0) : ?>
  <div class="ctas-list" data-align="<?php echo esc_attr($align); ?>">
    <?php foreach ($ctas as $cta) : ?>
      <div class="ctas-list__item">
        <?php 
          get_template_part('template-parts/components/cta-link', null, $cta['cta']); 
        ?>  
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>