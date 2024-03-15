<?php

/**
 *  Custom logo.
 */
add_filter( 'get_custom_logo', 'amend_custom_logo_html' );
function amend_custom_logo_html( $html ) {
  // Add aria-label to link.
  $html = str_replace( 'rel="home"', 'rel="home" aria-label="Link to Homepage"', $html);
  // Add style-svg class to the img.
  $html = str_replace( 'custom-logo', 'style-svg', $html );
  return $html;
}