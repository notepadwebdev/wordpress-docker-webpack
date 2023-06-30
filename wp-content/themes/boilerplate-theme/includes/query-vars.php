<?php

function add_query_vars_filter( $vars ){
  // $vars[] = "term";
  // $vars[] = "tag";
  return $vars;
}

//Add custom query vars
add_filter( 'query_vars', 'add_query_vars_filter' );