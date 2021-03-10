<?php

// Editor Formats.
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );
function my_mce_before_init_insert_formats($init_array) {

  $style_formats = array(
    array(
      'title' => 'Lede',
      'block' => 'div',
      'classes' => 'lede',
      'wrapper' => true,
    ),
  );
  $init_array['style_formats'] = json_encode($style_formats);
  return $init_array;
}

// Display formats button.
add_filter( 'mce_buttons_2', 'my_mce_buttons_2' );
function my_mce_buttons_2($buttons) {
  array_unshift( $buttons, 'styleselect' );
  return $buttons;
}

?>
