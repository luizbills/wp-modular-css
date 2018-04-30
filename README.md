# WP Modular CSS

- Contributors: luizbills
- Donate link: https://www.luizpb.com/en/
- Tags: css, tachyons, modular, atomic, functional
- Requires at least: 4.6
- Tested up to: 4.9
- Stable tag: 1.1.1
- Requires PHP: 5.4
- License: GPLv2 or later
- License URI: https://www.gnu.org/licenses/gpl-2.0.html

*Generate customized Tachyons CSS easily*

## Description

Generate a custom [tachyons CSS](http://tachyons.io) build from a json configuration.

Just go to **Settings -> Modular CSS** and change the default tachyons values to better meet your needs.

Useful links:

- [Online Generator](https://tachyons.luizpb.com/)
- [Github Repository](https://github.com/luizbills/wp-modular-css)

## Installation

1. Upload the plugin files to the `/wp-content/plugins/wp-modular-css` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings -> Modular CSS screen to configure the plugin

## Frequently Asked Questions

1. **I can choose what parts of tachyons will be responsive?**
Yes. In your config, put "responsive" word inside of module's array in "__enabled-modules" property.

1. **I can delete/remove any module of my custom tachyons?**
Yes. In your config, remove the module's declaration in "__enabled-modules" property.

## Screenshots

![Choose your own values like colors and spacing...](wp-assets/screenshot-1.png)

## Changelog

- complete CHANGELOG in https://github.com/luizbills/wp-modular-css/blob/master/CHANGELOG

## Upgrade Notice

**1.0**
- Initial release