<?php
  // Block Pad.
  $blockPadArray = get_field('block_pad') ?: array('block_padding' => array('block-pad'));
  $blockPad = implode(' ', $blockPadArray['block_padding']);

  $className = 'template block';
  
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

    <?php if ((get_field('include_block_header') && get_field('block_header_wysiwyg')) ) : ?>

      <?php
        if (get_field('include_block_header')) : 
          get_template_part('template-parts/components/block-header');
        endif;
      ?>

    <?php else: ?>

      <?php /* Initial display after adding the unpopulated block. */ ?>
      <h2 class="block__placeholder">Template Block</h2>

    <?php endif; ?>

  </div>
</section>
