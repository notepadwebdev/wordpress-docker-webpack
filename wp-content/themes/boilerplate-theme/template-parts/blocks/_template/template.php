<?php
  $className = 'named-block block';
  
  if (!empty($block['align'])) {
    $className .= ' block--'.$block['align'].'-width';
  }
  if (!empty($block['align_text'])) {
    $className .= ' text-' . $block['align_text'];
  }

  if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
  }
?>

<section class="<?php echo esc_attr($className); ?> body-pad block-pad">
  <div class="container">

    <?php if (get_field('title')) : ?>

      <?php if (get_field('title')) : ?>
        <h2><?php the_field('title'); ?></h2>
      <?php endif; ?>

    <?php else: ?>

      <?php /* Initial display after adding the unpopulated block. */ ?>
      <h2 class="block__placeholder">Named Block</h2>

    <?php endif; ?>

  </div>
</section>
