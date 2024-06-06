<?php 
/**
 *  Images.
 */

add_theme_support('post-thumbnails');
add_theme_support('custom-logo');

if ( function_exists( 'add_image_size' ) ) {

  // Crops.
  // add_image_size( "photo-thumb",            300, 200, true);

  // Resizes.
  // add_image_size( "hero",                   2560, 0, false);

  // Image Component. (1440px max-width)
  add_image_size( "2560w",    2560, 0, false);  // Full bleed.
  add_image_size( "1440w",    1440, 0, false);  // 12 col.
  add_image_size( "1319w",    1319, 0, false);  // 11 col.
  add_image_size( "1197w",    1197, 0, false);  // 10 col.
  add_image_size( "1075w",    1075, 0, false);  // 9 col.
  add_image_size( "954w",     954, 0, false);   // 8 col.
  add_image_size( "832w",     832, 0, false);   // 7 col.
  add_image_size( "710w",     710, 0, false);   // 6 col.
  add_image_size( "589w",     589, 0, false);   // 5 col.
  add_image_size( "467w",     467, 0, false);   // 4 col.
  add_image_size( "345w",     345, 0, false);   // 3 col.
  add_image_size( "224w",     224, 0, false);   // 2 col.
  
}

/**
 *  Custom sizes attribute.
 */
// add_filter( 'wp_calculate_image_sizes', 'adjust_image_sizes_attr', 10 , 2 );
// function adjust_image_sizes_attr( $sizes, $size ) {
//   // print_r($size);
//   $intrinsicWidth = $size[0];
//   $intrinsicHeight = $size[1];

//   switch ($intrinsicWidth) {
//     // Half screen.
//     case 1280:
//       $sizes = '(max-width: 1024px) 512vw, (max-width: 1536px) 768vw, (max-width: 2048px) 1024vw, 1280px';
//       break;
//   }
//   return $sizes;
// }
?>
