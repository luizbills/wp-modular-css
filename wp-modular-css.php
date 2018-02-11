<?php
/*
Plugin Name: Health Check
Plugin URI: https://github.com/luizbills/wp-modular-css
Description: Generate customized Tachyons easily
Version: 0.1.0
Author: Luiz Bills
Author URI: https://luizpb.com/en
Text Domain: wp-modular-css
*/

if ( ! defined( 'WPINC' ) ) die();

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/inc/init.php';

class WP_Modular_CSS {

    const FILE = __FILE__;
    const DIR = __DIR__;
    const DOMAIN = 'wp-modular-css';
    const PREFIX = 'wp_modular_css_';
    const VERSION = '1.0.0';

    protected static $plugin_uploads_folder = null;

    public static function write_file ( $filename, $content ) {
        $dir = self::get_plugin_uploads_folder();

        file_put_contents( $dir . $filename, $content );
    }

    public static function get_plugin_uploads_folder () {
        if ( is_null( self::$plugin_uploads_folder ) ) {
            $upload_dir   = wp_upload_dir();

            if ( ! empty( $upload_dir['basedir'] ) ) {
                self::$plugin_uploads_folder = $upload_dir['basedir'] . '/modular-css/';

                if ( ! file_exists( self::$plugin_uploads_folder ) ) {
                    if ( ! wp_mkdir_p( self::$plugin_uploads_folder ) ) {
                        wp_die( esc_html__( 'could not create folder ' . self::$plugin_uploads_folder . '. Please check the permissions of your uploads folder.', self::DOMAIN ) );
                    }
                }
            }
        }

        return self::$plugin_uploads_folder;
    }

}