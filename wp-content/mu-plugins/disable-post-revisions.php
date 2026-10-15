<?php
/**
 * Plugin Name: Disable Post Revisions
 * Description: Prevents revisions and server-side autosaves for every post type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hydramotion_disable_revisions_for_post_type( $post_type ) {
	remove_post_type_support( $post_type, 'revisions' );
}

add_action( 'registered_post_type', 'hydramotion_disable_revisions_for_post_type', PHP_INT_MAX );

add_action(
	'init',
	static function () {
		foreach ( get_post_types() as $post_type ) {
			hydramotion_disable_revisions_for_post_type( $post_type );
		}
	},
	PHP_INT_MAX
);

add_filter( 'wp_revisions_to_keep', '__return_zero', PHP_INT_MAX );

add_filter(
	'wp_insert_post_empty_content',
	static function ( $maybe_empty, $post_data ) {
		if ( isset( $post_data['post_type'] ) && 'revision' === $post_data['post_type'] ) {
			return true;
		}

		return $maybe_empty;
	},
	PHP_INT_MAX,
	2
);
