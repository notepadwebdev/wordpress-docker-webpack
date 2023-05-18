<?php
  // echo '<pre>';
  // print_r($args); 
  // echo '</pre>';

  $rows = $args['flexible_content'];
  forEach ($rows as &$row) {

    switch ($row['acf_fc_layout']) {

      case 'wysiwyg_layout':
        get_template_part('template-parts/components/wysiwyg', null, $row);
        break;

      case 'image_layout': 
        get_template_part('template-parts/components/image', null, $row['image']);
        break;

      case 'button_layout':
        get_template_part('template-parts/components/cta-link', null, $row['cta']);
        break;

      case 'video_layout':
        get_template_part('template-parts/components/video', null, $row['video']);
        break;
    }

  }

?>