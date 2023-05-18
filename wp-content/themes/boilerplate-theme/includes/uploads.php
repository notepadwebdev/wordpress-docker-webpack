<?php
/**
 *  Allow unfiltered uploads.
 */
add_filter( 'user_has_cap', 'unfiltered_upload' );
function unfiltered_upload( $caps ) {
  $caps['unfiltered_upload'] = 1;
  return $caps;
}

add_filter('upload_mimes', 'jf_mime_types');
function jf_mime_types($mimes) {
  $mimes['json'] = 'application/json'; 
  return $mimes; 
} 
?>