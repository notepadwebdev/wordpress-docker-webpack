<?php
  $align = get_field('block_header_align') ? implode(' ', get_field('block_header_align')) : 'left';
?>

<header class="block-header <?php echo $align; ?>">
  <?php if (get_field('block_header_wysiwyg')) : ?>
    <div class="wysiwyg">
      <?php the_field('block_header_wysiwyg'); ?>
    </div>
  <?php endif; ?>
</header>