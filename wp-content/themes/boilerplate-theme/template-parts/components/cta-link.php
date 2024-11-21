<?php 
  // echo '<pre>';
  // print_r($args); 
  // echo '</pre>';
  
  switch ($args['type']) {
    case 'internal':
      $url = $args['link'];
      break;

    case 'external':
      $url = $args['url'];
      break;

    case 'file':
      $url = $args['file']['url'];
      break;

    case 'hash':
      $url = '#'.$args['target_id'];
      break;

    case 'mailto':
      $url = 'mailto:'.$args['email'];
      break;
  }

  // Append a hash to the link?
  if (isset($args['target_id']) && $args['target_id']) {
    $url .= '#'.$args['target_id'];
  }
  
  $class = 'btn';
  $class .= (isset($args['colour'])) ? ' btn--'.$args['colour'] : '';
  $class .= (isset($args['style'])) ? ' btn--'.$args['style'] : '';
?>

<a 
  href="<?php echo $url; ?>" 
  class="<?php echo $class; ?>" 
  <?php if (isset($args['new_tab']) && $args['new_tab']) : ?>
    target="_blank" 
  <?php endif; ?>
>
  <span>
    <?php echo $args['label']; ?>
  </span>
</a>