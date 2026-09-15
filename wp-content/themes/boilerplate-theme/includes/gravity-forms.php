<?php

add_filter( 'gform_disable_css', '__return_true' );
add_filter( 'gform_disable_form_theme_css', '__return_true' );
add_filter( 'gform_disable_form_legacy_css', '__return_true' );
add_filter( 'gform_confirmation_anchor', '__return_false' );

add_filter( 'gform_field_content', 'ylem_power_required_field_indicator', 10, 2 );

function ylem_power_required_field_indicator( $field_content, $field ) {
	if ( ! $field->isRequired ) {
		return $field_content;
	}

	return str_replace(
		'<span class="gfield_required gfield_required_text">(Required)</span>',
		'<span class="form-required-indicator" aria-hidden="true">*</span><span class="visually-hidden">Required</span>',
		$field_content
	);
}