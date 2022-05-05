<?php

add_theme_support( 'editor-styles' );

add_action('admin_head', 'theme_admin_styles');
function theme_admin_styles() {
 echo '<style>
  .editor-styles-wrapper {
    background-color: white !important;
  }
 </style>';
}

?>