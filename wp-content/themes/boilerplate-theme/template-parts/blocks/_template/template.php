
<section class="named-block block">
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
