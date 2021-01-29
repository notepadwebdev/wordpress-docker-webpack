<?php
  $title = get_field('title');
  $description = get_field('description');
?>

<section class="hero full-bleed">

  <div class="flexible-container">
    <div class="hero__grid">
      
      <h1 class="hero__title">
        <?php if (get_field('title')) : ?>
          <?php echo get_field('title'); ?>        
        <?php elseif (is_admin()): ?>
          Hero Title Here
        <?php endif; ?> 
      </h1>
      
      <div class="hero__description">
        <?php if (get_field('description')) : ?>
          <?php echo get_field('description'); ?>        
        <?php elseif (is_admin()): ?>
          Lorem ipsum...
        <?php endif; ?> 
      </div>
      
      
    </div>
  </div>
  
</section>
