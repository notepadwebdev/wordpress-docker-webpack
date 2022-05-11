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
    array(
      'title' => 'f-h1-size',
      'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
      'classes' => 'h1'
    ),
    array(
      'title' => 'f-h2-size',
      'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
      'classes' => 'h2'
    ),
    array(
      'title' => 'f-h3-size',
      'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
      'classes' => 'h3'
    ),
    array(
      'title' => 'f-h4-size',
      'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
      'classes' => 'h4'
    ),
    array(
      'title' => 'f-h5-size',
      'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
      'classes' => 'h5'
    ),
    array(
      'title' => 'f-h6-size',
      'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
      'classes' => 'h6'
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


// Custom WYSIWYG toolbars.
add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
function my_toolbars( $toolbars )
{
	// Uncomment to view format of $toolbars
	//
	// echo '< pre >';
	// 	print_r($toolbars);
	// echo '< /pre >';
	// die;
	//

	// Add a new toolbar called "Very Simple"
	// - this toolbar has only 1 row of buttons
	$toolbars['Headings' ] = array();
	$toolbars['Headings' ][1] = array('formatselect', 'alignleft', 'aligncenter', 'alignright', 'wp_adv');
  $toolbars['Headings' ][2] = array('styleselect');

	// Edit the "Full" toolbar and remove 'code'
	// - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
	if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
	{
	  unset( $toolbars['Full' ][2][$key]);
	}

	// remove the 'Basic' toolbar completely
	// unset( $toolbars['Basic' ] );

	// return $toolbars - IMPORTANT!
	return $toolbars;
}

?>
