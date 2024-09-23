
<article class="post">            
  <div class="post__image-container">
    <a href="<?php the_permalink(); ?>" aria-label="<?php the_title();?>">
      <?php the_post_thumbnail(); ?>
    </a>
  </div>
  <div class="post__copy-container">
    <h2 class="post__title">
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
      </a>
    </h2>
    <div class="post__excerpt">
      <?php the_excerpt(); ?>
    </div>
    <?php
      $terms = get_the_terms($post->ID, 'category');
      if ($terms) :
        echo '<div class="post__categories">';
        foreach ($terms as &$term) {
          if ($term->name !== 'Uncategorised') :
            echo "<span>$term->name</span>";
          endif;
        }
        echo '</div>';
      endif;
    ?>
  </div>
</article>
