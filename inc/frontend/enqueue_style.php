<?php

if ( ! defined( 'WPINC' ) ) die();

add_action( 'wp_enqueue_scripts', 'wp_modular_css_enqueue_style', 0 );
function wp_modular_css_enqueue_style () {
	$settings_page = wp_get_admin_page( 'wp-modular-css-settings' );
	$css_file = WP_Modular_CSS::get_plugin_uploads_folder() . 'style.css';

	if ( ! file_exists( $css_file ) ) {
		$settings_page = wp_get_admin_page( 'wp-modular-css-settings' );
		$value = $settings_page->get_field_value( 'config_json' );
		$config = WP_Modular_CSS::parse_json( $value );

		if ( false !== $config ) {
			$minify_css = $settings_page->get_field_value( 'minify' ) === 'on';
			$filename = 'style.css';
			$builder = new WP_Modular_CSS_Builder( $config );

			WP_Modular_CSS::write_file( $filename, $builder->get_output( $minify_css ) );
		}
	}

	if ( 'on' === $settings_page->get_field_value( 'enqueue_style' ) ) {
		wp_enqueue_style(
			'tachyons-custom',
			WP_Modular_CSS::get_plugin_uploads_url() . 'style.css',
			[],
			WP_Modular_CSS::TACHYONS_VERSION
		);
	}
}