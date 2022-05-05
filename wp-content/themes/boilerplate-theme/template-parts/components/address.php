<?php 
  // echo '<pre>';
  // print_r($args); 
  // echo '</pre>';
?>

<address class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">        
  <?php if ($args['street_address_1']) : ?>
    <span itemprop="streetAddress"><?php echo $args['street_address_1']; ?></span>
  <?php endif; ?>
  <?php if ($args['street_address_2']) : ?>
    <span itemprop="streetAddress"><?php echo $args['street_address_2']; ?></span>
  <?php endif; ?>
  <?php if ($args['towncity']) : ?>
    <span itemprop="addressLocality"><?php echo $args['towncity']; ?></span>
  <?php endif; ?>
  <?php if ($args['county']) : ?>
    <span itemprop="addressRegion"><?php echo $args['county']; ?></span>
  <?php endif; ?>
  <?php if ($args['post_code']) : ?>
    <span itemprop="postalCode"><?php echo $args['post_code']; ?></span>
  <?php endif; ?>
  <?php if ($args['country']) : ?>
    <span itemprop="addressCountry"><?php echo $args['country']; ?></span>
  <?php endif; ?>
</address>