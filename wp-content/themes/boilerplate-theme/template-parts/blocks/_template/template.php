<?php
  $className = 'named-block block body-pad';
  if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
  }
  if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
  }
  if( !empty($block['align_text']) ) {
    $className .= ' text-' . $block['align_text'];
  }
?>

<section class="<?php echo esc_attr($className); ?>">
  <div class="container">

    <?php if (get_field('something')) : ?>

      <?php if (get_field('block_title')) : ?>
        <h2><?php the_field('block_title'); ?></h2>
      <?php endif; ?>

    <?php else: ?>

      <?php /* Initial display after adding the unpopulated block. */ ?>
      <h2 class="block__placeholder">Named Block</h2>

    <?php endif; ?>

  </div>
</section>
