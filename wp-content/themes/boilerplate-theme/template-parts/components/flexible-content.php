<?php
  // echo '<pre>';
  // print_r($args); 
  // echo '</pre>';

  $rows = $args['flexible_content'];
  forEach ($rows as &$row) {

    switch ($row['acf_fc_layout']) {

      case 'wysiwyg_layout':
        echo '<div class="flexible-content__wysiwyg animate-on-enter">';
        get_template_part('template-parts/components/wysiwyg', null, $row);
        echo '</div>';
        break;

      case 'image_layout': 
        echo '<div class="flexible-content__image animate-on-enter">';
        get_template_part('template-parts/components/image', null, $row['image']);
        echo '</div>';
        break;

      case 'button_layout':
        echo '<div class="flexible-content__cta animate-on-enter">';
        get_template_part('template-parts/components/cta-link', null, $row['cta']);
        echo '</div>';
        break;

      case 'video_layout':
        echo '<div class="flexible-content__video animate-on-enter">';
        get_template_part('template-parts/components/video', null, $row['video']);
        echo '</div>';
        break;
    }

  }

?>