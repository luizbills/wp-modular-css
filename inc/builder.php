<?php

use MatthiasMullie\Minify;

class WP_Modular_CSS_Builder {

	protected $config;
	protected $output;
	protected $output_min = null;
	protected $css_modules;

	function __construct( $config_json ) {
		$this->config = $config_json;

		$this->register_shortcodes();
		$this->load_modules();
		$this->build();
	}

	public function get_output ( $minify = false ) {
		if ( $minify ) {
			return $this->minify();
		}
		return $this->output;
	}

	protected function register_shortcodes () {
		add_shortcode( 'mcss_responsive', [ $this, 'shortcode_responsive' ] );
		add_shortcode( 'mcss_use', [ $this, 'shortcode_use' ] );
		add_shortcode( 'mcss_foreach', [ $this, 'shortcode_foreach' ] );
	}

	public function load_modules () {
		$modules = [];
		$type = 'tachyons-default';
		$base_path = WP_Modular_CSS::DIR . '/css-modules/' . $type . '/';
		$files = array_diff( scandir( $base_path ), [ '.', '..' ] );

		foreach ( $files as $file_name ) {
			$modules[ str_replace( '.css', '', $file_name ) ] = $this->get_module( $base_path . $file_name );
		}

		$this->css_modules = apply_filters( WP_Modular_CSS::PREFIX . 'css_modules' , $modules, $type );
	}

	protected function build () {
		$output = '';

		foreach( $this->config['__enabled-modules'] as $module_name => $module_props ) {
			$module_content = isset( $this->css_modules[ $module_name ] ) ? $this->css_modules[ $module_name ] : false;
			if ( empty( $module_content ) ) continue;

			$module_content = apply_filters( WP_Modular_CSS::PREFIX . 'css_module_' . $module_name, $module_content, $module_name );

			$module_content = $this->setup_special_syntax( $module_content, $module_props );
			$module_content = do_shortcode( $module_content );

			// remove @default
			$module_content = str_replace( [ '-@default', '@default' ], '', $module_content );

			$output .= trim( $module_content ) . PHP_EOL . PHP_EOL;
		}

		$this->output = $output;
	}

	public function shortcode_responsive ( $args = [], $content = '' ) {
		$config = $this->config;
		$breakpoints = $config['media-queries'];
		$css = $content;
		$defaults = [
			'disabled' => '',
		];
		$args = is_array( $args ) ? $args : [];
		$args = array_merge( $defaults, $args );

		if ( ! empty( $args['disabled'] ) ) return '';

		$output = '';
		foreach( $breakpoints as $index => $media ) {
			$output .= '@media ' . $media . ' {' ;
			$output .= str_replace( '$screen-size$', $index, $css );
			$output .= '}';
			$output = trim( $output ) . PHP_EOL . PHP_EOL;
		}

		return do_shortcode( $output );
	}

	public function shortcode_foreach ( $args = [], $content = '' ) {
		$config = $this->config;
		$key = $args[0];
		$values = isset( $this->config[ $key ] ) ? $this->config[ $key ] : false;
		$css = $content;
		$output = '';

		if ( false === $values ) return '';

		foreach( $values as $index => $value ) {
			if ( is_array( $value ) ) continue;

			$value = do_shortcode( $this->setup_special_syntax( $value ) );
			$rule = str_replace( [ '$index$', '$value$' ], [ $index, $value ], $css );
			$rule = rtrim( $rule );

			$output .= $rule;
		}

		return ltrim( do_shortcode( $output ) );
	}

	public function shortcode_use ( $args = [], $content = '' ) {
		$config = get_config();
		$value = $this->get_config_value( implode( '.', $args ) );

		return $value;
	}

	protected function setup_special_syntax ( $string, $props = [] ) {
		$result = $string;

		if ( ! empty( $result ) ) {
			$is_responsive = in_array( 'responsive', $props );

			if ( $is_responsive ) {
				$result = str_replace( '[[@responsive]]', '[mcss_responsive]', $result );
			} else {
				$result = str_replace( '[[@responsive]]', '[mcss_responsive disabled=1]', $result );
			}

			$result = str_replace( '[[/@responsive]]', '[/mcss_responsive]', $result );
			$result = str_replace( '[[@screen-size]]', '$screen-size$', $result );

			$result = str_replace( '[[@foreach', '[mcss_foreach ', $result );
			$result = str_replace( '[[/@foreach]]', '[/mcss_foreach]', $result );
			$result = str_replace( '[[@index]]', '$index$', $result );
			$result = str_replace( '[[@value]]', '$value$', $result );

			$result = str_replace( '[[@use', '[mcss_use ', $result );

			$result = str_replace( ']]', ' ]', $result );
		}
		return $result;
	}

	protected function get_config_value ( $path ) {
		$parts = explode('.', $path);
		$target = '';

		while ( 0 < count( $parts ) ) {
			if ( ! $target ) $target = $this->config;
			$key = array_shift( $parts );
			if ( false ===  array_key_exists( $key, $target ) || '__' === substr( $key, 0, 2 ) ) {
				return '';
			}
			$target = $target[ $key ];
		}

		if ( is_string( $target ) && strpos( $target, '[[@use ') !== false ) {
			$target = do_shortcode( $this->setup_special_syntax( $target ) );
		}

		return $target;
	}

	protected function get_module ( $file_path ) {
		if ( file_exists( $file_path ) ) {
			ob_start();
			include( $file_path );
			return ob_get_clean();
		}
		return false;
	}

	protected function minify () {
		if ( is_null( $this->output_min ) ) {
			$minifier = new Minify\CSS( $this->output );
			$this->output_min = $minifier->minify();
		}
		return $this->output_min;
	}
}