<?php

function theme_acf_google_map_api( $api ){
  
  // Define API keys
  $localhost_key = 'YOUR_LOCAL_DEVELOPMENT_API_KEY'; // Replace with your local development API key
  $production_key = 'YOUR_PRODUCTION_API_KEY'; // Replace with your production API key
  
  // Check if we're on localhost
  $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
  $is_localhost = (
    stripos($host, 'localhost') !== false || 
    stripos($host, '127.0.0.1') !== false ||
    preg_match('/localhost:\d+/', $host)
  );
  
  // Set the appropriate API key
  $api['key'] = $is_localhost ? $localhost_key : $production_key;
  
  return $api;

}
add_filter('acf/fields/google_map/api', 'theme_acf_google_map_api');