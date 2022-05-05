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

    case 'hash':
      $url = '#'.$args['target_id'];
      break;

    // case 'file':
    //   $url = $args['file']['url'];
    //   break;
  }
  
  $class = (isset($args['style'])) ? 'btn--'.$args['style'] : '';
?>

<a href="<?php echo $url; ?>" class="btn <?php echo $class; ?>">
  <span>
    <?php echo $args['label']; ?>
  </span>
  <svg width="15" height="9" viewBox="0 0 15 9" fill="none">
    <path d="M1 1L7.5 7L14 1" stroke="#011627" stroke-width="2"/>
  </svg>
</a>