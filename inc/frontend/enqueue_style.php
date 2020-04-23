<?php

if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp_enqueue_scripts', 'wp_modular_css_enqueue_style', 0 );
function wp_modular_css_enqueue_style () {
	$css_file_path = WP_Modular_CSS::get_css_file_path();

	if ( ! file_exists( $css_file_path ) ) {
		WP_Modular_CSS::generate_css_file();
	}

	if ( 'on' === WP_Modular_CSS::get_setting( 'enqueue_style' ) ) {
		wp_enqueue_style(
			'tachyons-custom',
			WP_Modular_CSS::get_css_file_url(),
			[],
			WP_Modular_CSS::VERSION
		);
	}
}