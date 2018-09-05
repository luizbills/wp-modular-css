<?php

if ( ! defined( 'WPINC' ) ) die();

class WP_Modular_CSS_Settings_Page {

	public static $instance = null;

	protected $page = null;

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

			'lang'              => 'json',
			'height'            => 300,
			'tab-size'          => 4,
			'theme'             => 'monokai',
			'font-size'         => 14,
			'show-print-margin' => false,

			'sanitize_callback' => [ $this, 'sanitize_config_json' ],

			//'after' => '<button type="button" class="button" id="wp-modular-css-export">Export CSS</button>'
		]);

		$this->page->add_field([
			'type'    => 'code',
			'id'      => 'custom_css',
			'label'   => 'Custom CSS',
			'default' => $this->get_default_custom_css(),
			'lang'              => 'css',
			'height'            => 300,
			'tab-size'          => 4,
			'theme'             => 'monokai',
			'font-size'         => 14,
			'show-print-margin' => false,
		]);

		$this->page->add_field([
			'type'    => 'checkbox',
			'id'      => 'minify',
			'label'   => 'Minify generated CSS',
			'after'   => 'Enable',
		]);

		$this->page->add_field([
			'type'    => 'checkbox',
			'id'      => 'enqueue_style',
			'label'   => 'Add generated css to all front-end pages',
			'default' => 'on',
			'after'   => 'Enable'
		]);

		$this->page->add_field([
			'type'    => 'html',
			'id'      => 'useful_info',
			'label'   => 'Useful informations',
			'content'   => [ $this, 'useful_informations' ]
		]);
	}

	protected function get_default_custom_css () {
		return file_get_contents( WP_Modular_CSS::DIR . '/assets/css/custom-css.css' );
	}

	public function sanitize_config_json ( $value, $field_id ) {
		if ( 'reset' === $value ) {
			$value = $this->get_default_config();
		}

		$json = WP_Modular_CSS::parse_json( $value );
		if ( false === $json ) {
			$message = sprintf('Failed to parse json string "%s"', json_last_error_msg() );
			add_settings_error( $field_id, $field_id, $message );
		}
		return $value;
	}

	public function generate_css_file () {
		$the_page = wp_get_admin_page( 'wp-modular-css-settings' );
		$value = $the_page->get_field_value( 'config_json' );
		$config = WP_Modular_CSS::parse_json( $value );

		if ( false !== $config ) {
			$minify_css = $the_page->get_field_value( 'minify' ) === 'on';
			$filename = 'style.css';
			$builder = new WP_Modular_CSS_Builder( $config );

			WP_Modular_CSS::write_file( $filename, $builder->get_output( $minify_css ) );
		}
	}

	public function enqueue_scripts () {
		wp_enqueue_script(
			WP_Modular_CSS::SLUG . '-settings-page',
			plugins_url( 'assets/js/admin/settings-page.js', WP_Modular_CSS::FILE ),
			[
				'jquery',
				'ace-editor',
			],
			WP_Modular_CSS::VERSION,
			true
		);
	}

	public function useful_informations () {
		$Parsedown = new Parsedown();

		$reset_button = '<button type="button" class="button" id="reset-tachyons">Reset</button>';

		$desc = '';
		$desc .= '1. You can save using **ctrl + S** or **cmd + S** (with code editor focused)' . PHP_EOL;

		$desc .= '1. Your generated css is saved in `wp-content/uploads/wp-modular-css/style.css`' . PHP_EOL;

		$desc .= '1. Useful links:'  . PHP_EOL;
		$desc .= '[JSON props documentation](https://github.com/luizbills/wp-modular-css/blob/master/docs/json-config-syntax.md)';
		$desc .= ', [tachyons documentation](http://tachyons.io/docs)';
		$desc .= ', [Github repository](https://github.com/luizbills/wp-modular-css)' . PHP_EOL;

		$desc .= '1. Also, there is a [online version of this generator](https://tachyons.luizpb.com)' . PHP_EOL;

		$desc .= '1. To reset your configuration use this button: <br>' . $reset_button . PHP_EOL;

		$desc = $Parsedown->text( $desc );

		$desc = str_replace( '<a ', '<a target="_blank" ', $desc );

		echo $desc;
	}

	protected function get_default_config () {
		$file = WP_Modular_CSS::DIR . '/config-files/default.json';

		if ( file_exists( $file ) ) {
			return file_get_contents( $file );
		}

		throw new Exception( 'default Modular CSS configuration file missing.' );
	}

	public static function get_instance () {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}

WP_Modular_CSS_Settings_Page::get_instance();
