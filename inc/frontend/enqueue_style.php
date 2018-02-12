<?php

if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp_enqueue_scripts', 'wp_modular_css_enqueue_style' );
function wp_modular_css_enqueue_style () {
	$settings_page = wp_get_admin_page( 'wp-modular-css-settings' );

	if ( 'on' === $settings_page->get_field_value( 'enqueue_style' ) ) {
		wp_enqueue_style(
			'tachyons-custom',
			WP_Modular_CSS::get_plugin_uploads_url() . 'style.css',
			[],
			WP_Modular_CSS::TACHYONS_VERSION
		);
	}
}