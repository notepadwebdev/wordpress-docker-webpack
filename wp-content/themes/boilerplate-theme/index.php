<?php include(locate_template('header.php')); ?>

<?php while ( have_posts() ) { the_post(); ?>
      
    <h1>
      <?php the_title(); ?>
    </h1>
    
    <div>
      <?php the_content(); ?>
    </div>
  
<?php } ?>

<?php include(locate_template('footer.php')); ?>
