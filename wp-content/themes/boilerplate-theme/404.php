<?php include(locate_template('header.php')); ?>
<div class="_404" data-theme="light-1">
  <div class="_404__container">

    <h1 class="_404__title">
      <?php the_field('opt_404_title', 'option'); ?>
    </h1>
    
    <p class="_404__subtitle">
      <strong><?php the_field('opt_404_subtitle', 'option'); ?></strong>
    </p>
    
    <div class="_404__content wysiwyg">
      <?php the_field('opt_404_content', 'option'); ?>
    </div>
    
    <div class="_404__cta">
      <?php
        $cta = get_field('opt_404_cta', 'option');
        if ($cta) {
          get_template_part('template-parts/components/cta-link', null, $cta);
        }
      ?>
    </div>

  </div>
</div>
<?php include(locate_template('footer.php')); ?>
