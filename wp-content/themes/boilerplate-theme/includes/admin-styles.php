<?php

add_theme_support( 'editor-styles' );
add_editor_style( 'dist/css/styles.css' );
add_editor_style( 'admin.css' );

add_action('admin_head', 'theme_admin_styles');
function theme_admin_styles() {
 echo '<style>
  html :where(.wp-block) {
		max-width: none !important;
    margin: 0 !important;
  }
  .editor-styles-wrapper {
    background-color: white !important;
  }
	.mce-container iframe {
		width: 100%;
	}

	/* Fix for broken Categories height flex in ACF side panel. */
  .components-flex:has(.editor-post-taxonomies__hierarchical-terms-list) {
    height: auto;
  }

	/* 
		Force height of ACF block-padding field. 
	*/
	.acf-field-64662ed4e6535 select {
		min-height: 340px !important;
	}

	/* 
		Content Block: 
		Hide Layout code field in ACF as the value is managed via the Grid Layout Builder interface. 
	*/
	.acf-field.acf-field-number.acf-field-691de5e0ba491,
	.acf-field.acf-field-acfe-code-editor.acf-field-691de566ba490 {
		display: none;
	}
 </style>';
}

// Remove ACF inline styles for WYSIWYG.
function my_acf_input_admin_footer() { ?>
	<script type="text/javascript">
		(function($) {
			acf.add_action('wysiwyg_tinymce_init', function( ed, id, mceInit, $field ){
				$(".acf-field .acf-editor-wrap iframe").removeAttr("style");
			});
		})(jQuery);	
	</script>
<?php }
add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');

?>