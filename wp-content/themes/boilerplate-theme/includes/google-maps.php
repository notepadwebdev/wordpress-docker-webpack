<?php

function theme_acf_google_map_api( $api ){
  $api['key'] = '';
  return $api;
}
add_filter('acf/fields/google_map/api', 'theme_acf_google_map_api');