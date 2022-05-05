<?php 
  // echo '<pre>';
  // print_r($args); 
  // echo '</pre>';
?>

<ul class="social-links">
  <?php foreach($args as $link) { ?>
    <li>
      <?php
        $href = (strpos($link['url'], '@')) ? 'mailto:'.$link['url'] : $link['url'];
      ?>
      <a href="<?php echo $href; ?>" rel="noreferrer" target="_blank" aria-label="<?php echo $link['site_name']; ?>">
        <?php
          if (array_key_exists('icon', $link)):
            echo wp_get_attachment_image($link['icon']['id'], "", false, ["class" => ""]); 
          endif;
          
          if (array_key_exists('site_name', $link)):
            echo '<span>'.$link['site_name'].'</span>';
          endif;
        ?>
      </a>
    </li>
  <?php } ?>
</ul>
