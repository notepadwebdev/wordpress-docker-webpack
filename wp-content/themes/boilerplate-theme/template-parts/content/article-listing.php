
<article class="article">            
  <div class="article__image-container">
    <a href="<?php the_permalink(); ?>" aria-label="<?php the_title();?>">
      <?php the_post_thumbnail(); ?>
    </a>
  </div>
  <div class="article__copy-container">
    <h2 class="article__title">
      <a href="<?php the_permalink(); ?>">
        <?php the_title();?>
      </a>
    </h2>
    <?php
      $terms = get_the_terms($post->ID, 'category');
      if ($terms) :
        echo '<div class="article__categories">';
        foreach ($terms as &$term) {
          echo "<span>$term->name</span>";
        }
        echo '</div>';
      endif;
    ?>
  </div>
</article>
