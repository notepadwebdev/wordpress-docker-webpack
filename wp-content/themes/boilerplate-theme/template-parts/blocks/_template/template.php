<?php
  $className = 'temlpate block';
  
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
  <?php if ($block['anchor']) { echo "id='{$block['anchor']}'"; } ?> 
  class="<?php echo esc_attr($className); ?> body-pad block-pad"
>
  <div class="container">

    <?php if (get_field('title')) : ?>

      <?php if (get_field('title')) : ?>
        <h2><?php the_field('title'); ?></h2>
      <?php endif; ?>

    <?php else: ?>

      <?php /* Initial display after adding the unpopulated block. */ ?>
      <h2 class="block__placeholder">Template Block</h2>

    <?php endif; ?>

  </div>
</section>
