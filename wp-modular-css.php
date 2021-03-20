<?php
/*
Plugin Name: WP Modular CSS
Plugin URI: https://github.com/luizbills/wp-modular-css
GitHub Plugin URI: luizbills/wp-modular-css
Description: Generate customized Tachyons easily
Version: 2.3.0
Author: Luiz Bills
Author URI: https://luizpb.com/en
Text Domain: wp-modular-css
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'WPINC' ) ) die();

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/inc/init.php';

class WP_Modular_CSS {

	const VERSION = '2.3.0'; // plugin version
	const TACHYONS_VERSION = '4.12.0'; // tachyons.css version

	const FILE = __FILE__;
	const DIR = __DIR__;
	const SLUG = 'wp-modular-css';
	const PREFIX = 'wp_modular_css_';

	protected static $plugin_uploads_folder = null;
	protected static $plugin_uploads_url = null;

	public static function write_file ( $content ) {
		file_put_contents( self::get_css_file_path(), $content );
	}

	public static function generate_css_file () {
		$value = self::get_setting( 'config_json' );
		$config = self::parse_json( $value );

		if ( false !== $config ) {
			$minify_css = self::get_setting( 'minify' ) === 'on';
			$builder = new WP_Modular_CSS_Builder( $config );

			self::write_file( $builder->get_output( $minify_css ) );
		}
	}

	public static function get_plugin_uploads_folder () {
		if ( is_null( self::$plugin_uploads_folder ) ) {
			$upload_dir = wp_upload_dir();

			if ( ! empty( $upload_dir['basedir'] ) ) {
				self::$plugin_uploads_folder = $upload_dir['basedir'] . '/' . self::SLUG . '/';

				if ( ! file_exists( self::$plugin_uploads_folder ) ) {
					if ( ! wp_mkdir_p( self::$plugin_uploads_folder ) ) {
						wp_die( esc_html__( 'could not create folder ' . self::$plugin_uploads_folder . '. Please check the permissions of your uploads folder.', 'wp-modular-css' ) );
					}
				}
			}
		}
		return self::$plugin_uploads_folder;
	}

	public static function get_plugin_uploads_url () {
		if ( is_null( self::$plugin_uploads_url ) ) {
			$upload_dir   = wp_upload_dir();
			self::$plugin_uploads_url = esc_url( $upload_dir['baseurl'] . '/' . self::SLUG . '/' );
		}
		return self::$plugin_uploads_url;
	}

	public static function get_css_file_name () {
		return 'style-' . self::VERSION . '.css';
	}

	public static function get_css_file_path () {
		return self::get_plugin_uploads_folder() . self::get_css_file_name();
	}

	public static function get_css_file_url () {
		return self::get_plugin_uploads_url() . self::get_css_file_name();
	}

	public static function parse_json ( $json_string, $utf8_encode = true ) {
		$json_string = stripslashes( $json_string );
		$json_string = $utf8_encode ? utf8_encode( $json_string ) : $json_string;

		$json = json_decode( $json_string, true );

		if ( ! is_null( $json ) && JSON_ERROR_NONE === json_last_error() ) {
			return $json;
		}

		return false;
	}

	public static function get_setting ( $key ) {
		$the_page = wp_get_admin_page( self::SLUG . '-settings' );
		return $the_page->get_field_value( $key );
	}
}
