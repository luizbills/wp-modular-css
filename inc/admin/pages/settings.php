<?php

defined( 'WPINC' ) || die();

use HJSON\HJSONParser;

class WP_Modular_CSS_Settings_Page {

	public static $instance = null;

	public $page = null;

	private function __construct () {
		$this->page = wp_create_admin_page( [
			'id' => 'wp-modular-css-settings',
			'menu_name' => 'Modular CSS',
			'parent' => 'options-general.php',

			'prefix' => WP_Modular_CSS::PREFIX,
		] );

		$this->add_fields();

		$this->page->setup_page_hooks( [ $this, 'setup_page_hooks' ] );


	}

	public function setup_page_hooks( $hook_suffix ) {
		add_action( 'admin_print_styles-' . $hook_suffix, [ $this, 'enqueue_scripts' ], 20 );

		add_action( 'load-'. $hook_suffix, [ $this, 'generate_css_file' ], 10 );
	}

	public function add_fields () {

		$this->page->add_field([
			'type'    => 'code',
			'id'      => 'config_json',
			'label'   => 'Tachyons values',
			'default' => $this->get_default_config(),

			'desc' => $this->get_code_editor_desc(),

			'lang'              => 'json',
			'height'            => 300,
			'tab-size'          => 4,
			'theme'             => 'monokai',
			'font-size'         => 12,
			'show-print-margin' => false,

			'sanitize_callback' => [ $this, 'sanitize_config_json' ],

			//'before' => '<button type="button" class="button" id="wp-modular-css-export">Export</button><button type="button" class="button" id="wp-modular-css-import">Import</button>'
		]);

		$this->page->add_field([
			'type'    => 'checkbox',
			'id'      => 'minify',
			'label'   => 'Minify generated CSS',
			'default' => '',
		]);
	}

	public function sanitize_config_json ( $value, $field_id ) {
		$json = $this->parse_json( $value );
		if ( false === $json ) {
			$message = sprintf('Failed to parse json string "%s"', json_last_error_msg() );
			add_settings_error( $field_id, $field_id, $message );
		}
		return $value;
	}

	public function generate_css_file () {
		$the_page = wp_get_admin_page( 'wp-modular-css-settings' );
		$value = $the_page->get_field_value( 'config_json' );
		$json = $this->parse_json( $value );

		if ( false !== $json ) {
			$json = $this->setup_config_obj( $json );
			$minify_css = $the_page->get_field_value( 'minify' ) === 'on';
			$filename = 'style' . ( $minify_css ? '.min' : '' ) . '.css';
			$builder = new WP_Modular_CSS_Builder( $json );

			WP_Modular_CSS::write_file( $filename, $builder->get_output( $minify_css ) );
		}
	}

	public function enqueue_scripts () {
		wp_enqueue_script(
			WP_Modular_CSS::DOMAIN . '-settings-page',
			plugins_url( 'assets/js/admin/settings-page.js', WP_Modular_CSS::FILE ),
			[
				'jquery',
				'ace-editor',
			],
			WP_Modular_CSS::VERSION,
			true
		);
	}

	protected function get_code_editor_desc () {
		$desc = 'Useful links: ';
		$desc .= '<br>["JSON config file syntax docs" repository](https://github.com/luizbills/wp-modular-css/blob/master/docs/json-config-syntax.md)';
		$desc .= '<br>[Tachyons docs](http://tachyons.io/docs)';
		$desc .= '<br>["This Plugin" repository](https://github.com/luizbills/wp-modular-css)';

		return $desc;
	}

	protected function parse_json( $json_string ) {
		$json_string = utf8_encode( $json_string );
		$json = json_decode( $json_string, true );

		if ( ! is_null( $json ) && JSON_ERROR_NONE === json_last_error() ) {
			return $json;
		}

		return false;
	}

	protected function get_default_config () {
		$file = WP_Modular_CSS::DIR . '/config-files/default.json';

		if ( file_exists( $file ) ) {
			return file_get_contents( $file );
		}

		throw new Exception( 'default Modular CSS configuration file missing.' );
	}

	protected function setup_config_obj ( $json_obj ) {
		if ( ! isset( $json_obj['debug'] ) ) {
			$json_obj['debug'] = false;
		} else {
			if ( '@WP_DEBUG' === $json_obj['debug'] ) {
				$json_obj['debug'] = defined( 'WP_DEBUG' ) ? WP_DEBUG : false;
			} else if ( '@WP_DEBUG_SCRIPT' === $json_obj['debug'] ) {
				$json_obj['debug'] = defined( 'WP_DEBUG_SCRIPT' ) ? WP_DEBUG_SCRIPT : false;
			} else {
				$json_obj['debug'] = boolval( $json_obj['debug'] );
			}
		}

		if ( ! isset( $json_obj['media-queries'] ) ) {
			$json_obj['media-queries'] = [];
		}

		if ( ! isset( $json_obj['__enabled-modules'] ) ) {
			$json_obj['__enabled-modules'] = [];
		}

		return $json_obj;
	}

	public static function get_instance () {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

WP_Modular_CSS_Settings_Page::get_instance();