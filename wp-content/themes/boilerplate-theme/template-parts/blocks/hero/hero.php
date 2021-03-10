
<section class="hero block block--full-width">
  <div class="container">
      
    <?php if (get_field('title') || get_field('description')) : ?>
    
      <?php if (get_field('title')) : ?>
        <h1 class="hero__title">
          <?php the_field('block_title'); ?>
        </h1>
      <?php endif; ?>
      
      <?php if (get_field('description')) : ?>
        <div class="hero__description">
          <?php the_field('description'); ?>
        </div>
      <?php endif; ?>

    <?php else: ?>
          
      <?php /* Initial display after adding the unpopulated block */ ?>
      <h2 class="block__placeholder">Hero Block</h2>  
      
    <?php endif; ?> 
      
  </div>
</section>
