=== WP Modular CSS ===
Contributors: luizbills
Donate link: https://www.luizpb.com/en/
Tags: css, tachyons, modular, atomic, functional
Requires at least: 4.6
Tested up to: 4.9
Stable tag: 1.0
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Generate customized Tachyons CSS easily

== Description ==

Generate a custom [tachyons CSS](http://tachyons.io) build from a json configuration.

Just go to **Settings -> Modular CSS** and change the default tachyons values to better meet your needs.

Useful links:

- [Github Repository](https://github.com/luizbills/wp-modular-css)
- [JSON config documentation](https://github.com/luizbills/wp-modular-css/blob/master/docs/json-config-syntax.md)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wp-modular-css` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings -> Modular CSS screen to configure the plugin

== Frequently Asked Questions ==

= I can choose what parts of tachyons will be responsive? =
Yes. In your config, put "responsive" word inside of module's array in "__enabled-modules" property.

= I can delete/remove any module of my custom tachyons? =
Yes. In your config, remove the module's declaration in "__enabled-modules" property.

== Screenshots ==

1. Choose your own values like colors and spacing...

== Changelog ==

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.0 =
* Initial release
