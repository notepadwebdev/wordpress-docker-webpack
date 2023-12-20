<?php
  // echo '<pre>';
  // print_r($args);
  // echo '</pre>';

  $image = $args['image'];
  $crop = (isset($args['crop'])) ? $args['crop'] : 'full';
  $borderRadius = (isset($args['border_radius']) && $args['border_radius']) ? true : false;

  $classname = 'image';

  if ($borderRadius) {
    $classname .= ' image--border-radius';
  }
?>

<?php if (is_array($image)) : ?>
  <div class="<?php echo $classname; ?>">
      <?php echo wp_get_attachment_image($image['id'], $crop, false, ["class" => ""]); ?>
      <?php
        if (isset($args['include_colour_fill']) && $args['include_colour_fill']) :
          echo '<div class="image__fill" style="--color-fill: '.$args['colour_fill'].';"></div>';
        endif;
      ?>
  </div>
<?php endif; ?>