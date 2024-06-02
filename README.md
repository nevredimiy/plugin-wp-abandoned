# Custom Plugin for WP. Left orders from Woocommerce

## Description

This plugin collects customer data on the checkout page.
The data is written to a text file by the "onblur" event from the "billing_phone" input field. The data will be recorded regardless of whether the order is submit or not

## Getting Started


### Dependencies

* Wordpress and Woocommerce

### Installing

* The contents of the folder must be archived. Install in the usual way as a plugin for WordPress
* You may need to make the client_data_log.txt file accessible as 777. This is done on the server where the site is hosted
* That's all !

### Executing program

* After installation, a menu item for the plugin should appear in the admin menu - Abandoned. Here you can view the result of the plugin
* The plugin will write data to a text file after the onblur event on the billing_phone input field.
* Fields that will be written to the file:
- First name,
- Last name,
- phone number,
- email address

## Help

You can change the data recording conditions. For example, change the trigger field from billing_phone to billing_email.
To do this, you need to make changes in two files: abandoned-orders.php and /assets/abandoned-scripts.js.

## Authors

Plugin made by Artem Litvinov, my telegram:
[@artem_litvinov_8](https://t.me/artem_litvinov_8)

## Version History

* 1.0
    * Initial Release