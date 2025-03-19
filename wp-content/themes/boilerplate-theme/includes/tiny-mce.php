<?php

// Editor Formats.
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );
function my_mce_before_init_insert_formats($init_array) {

  $style_formats = array(
    array(
      'title' => 'Typography',
      'items' => array(
        array(
          'title' => 'Title',
          'items' => array(
            array(
              'title' => '3xl',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-3xl')
            ),
            array(
              'title' => '2xl',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-2xl')
            ),
            array(
              'title' => 'xl',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-xl')
            ),
            array(
              'title' => 'lg',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-lg')
            ),
            array(
              'title' => 'md',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-md')
            ),
            array(
              'title' => 'sm',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-sm')
            ),
            array(
              'title' => 'xs',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-xs')
            ),
            array(
              'title' => '2xs',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-title-2xs')
            ),
          ),
        ),
        array(
          'title' => 'Body',
          'items' => array(
            array(
              'title' => '2xl',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-body-2xl')
            ),
            array(
              'title' => 'xl',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-body-xl')
            ),
            array(
              'title' => 'lg',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-body-lg')
            ),
            array(
              'title' => 'md',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-body-md')
            ),
            array(
              'title' => 'sm',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-body-sm')
            ),
            array(
              'title' => 'xs',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-body-xs')
            ),
            array(
              'title' => '2xs',
              'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
              'classes' => array('f-body-2xs')
            ),
          ),
        ),
        array(
          'title' => 'Caps Text',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('f-caps')
        ),
      )
      ),
    array(
      'title' => 'Container',
      'items' => array(
        array(
          'title' => '11 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-11')
        ),
        array(
          'title' => '10 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-10')
        ),
        array(
          'title' => '9 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-9')
        ),
        array(
          'title' => '8 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-8')
        ),
        array(
          'title' => '7 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-7')
        ),
        array(
          'title' => '6 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-6')
        ),
        array(
          'title' => '5 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-5')
        ),
        array(
          'title' => '4 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-4')
        ),
        array(
          'title' => '3 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-3')
        ),
        array(
          'title' => '2 cols',
          'selector' => 'h1,h2,h3,h4,h5,h6,p,div',
          'classes' => array('l-max-width-2')
        )
      )
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

	// Add a new toolbar called "Headings"
	$toolbars['Headings' ] = array();
	$toolbars['Headings' ][1] = array('formatselect', 'alignleft', 'aligncenter', 'alignright', 'wp_adv');
  $toolbars['Headings' ][2] = array('styleselect');

  // Add a new toolbar called "Style Formats Only"
	$toolbars['Style Formats Only' ] = array();
	$toolbars['Style Formats Only' ][1] = array('wp_adv');
  $toolbars['Style Formats Only' ][2] = array('styleselect');

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
