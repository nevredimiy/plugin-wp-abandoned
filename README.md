# Custom Plugin for WP. Left orders from Woocommerce

## Description

The plugin is based on Wordpress hooks and jQuery. After installation, the plugin needs to be configured. You need to select the appropriate elements using selectors that are used in jQuery. These could be CSS selectors. For example, as elements (p, span, div, table, etc.) or as identifiers and classes (.wc-block-components-product-name, #billing_telephon), or by attributes (input[aria-label='Name')
You can read more about [jQuery selectors](https://api.jquery.com/all-selector/)

## Getting Started


### Dependencies

* Wordpress and Woocommerce

### Installing
* The contents of the folder must be archived. Install in the usual way as a plugin for WordPress. Another way is to copy the files to the plugins folder on the server.
* That's all !

### For the plugins to work correctly, you need to configure it:

* In the admin menu, go to the plugin settings - Abandoned -> Settings;
* In the "Tracking element selectors" section, select the appropriate selectors;
* In the "Select element and event" section, select the element and event for which data will be recorded. There are three options: blur, focus, click;
* Don't forget to save your changes.

## Authors

Plugin made by Artem Litvinov, my telegram:
[@artem_litvinov_8](https://t.me/artem_litvinov_8)

## Version History
* 1.1
    * Added the ability to select elements and triggers from the admin menu
* 1.0
    * Initial Release