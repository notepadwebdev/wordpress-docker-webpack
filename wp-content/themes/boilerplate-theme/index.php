<?php 
include(locate_template('header.php'));
while ( have_posts() ) { the_post();
  the_content();
}
include(locate_template('footer.php')); 
?>
