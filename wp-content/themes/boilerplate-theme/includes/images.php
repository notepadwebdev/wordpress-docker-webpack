<?php 
/**
 *  Images.
 */

add_theme_support('post-thumbnails');

if ( function_exists( 'add_image_size' ) ) {

  // Crops.
  // add_image_size( "photo-thumb",            300, 200, true);

  // Resizes.
  // add_image_size( "hero",                   2560, 0, false);
  
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
