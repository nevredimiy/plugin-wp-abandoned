<?php
/*
 * Plugin Name: Abandoned Orders
 * Description: Это плагин для сбора данных со страницы checkout, когда еще не нажата кнопка submit
 *
 * Authot URI: https://github.com/nevredimiy
 * Author:      Artem Litvinov
 */
if (! defined('ABSPATH')) {
	exit;
}
function my_dump($data){
	echo '<pre>' . print_r($data, 1) . '</pre>';
}

add_action('wp_ajax_get_client_data', 'ajax_save_client_data');
add_action('wp_ajax_nopriv_get_client_data', 'ajax_save_client_data');

function ajax_save_client_data(){
	if (empty($_POST['nonce'])){
		wp_die( '0' );
	}

	check_ajax_referer( 'abandoned', 'nonce', true );

	$arr_data = array(
		"first_name" => $_POST['firstName'],
		"last_name" => $_POST['lastName'],
		"phone" => $_POST['phone'],
		"email" => $_POST['email'],
		"product_name" => str_replace("\t", "", trim($_POST['productName'])),
		"price" => str_replace("\t", "", trim($_POST['price'])),
		"time" => $_POST['dNow']
	);

	create_table ();
	insert_table_data($arr_data);

	wp_die();
}

function create_table () {
	global $wpdb;
   	$charset_collate = $wpdb->get_charset_collate();
	$abandoned_db_version = '1.1';
	$installed_ver = get_option( "abandoned_db_version" );
	if ( $installed_ver != $abandoned_db_version ) {
		$table_name = $wpdb->prefix . "abandoned";
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			first_name tinytext DEFAULT NULL,
			last_name tinytext DEFAULT NULL,
			phone varchar(12) DEFAULT '',
			email varchar(55) DEFAULT '' NOT NULL,
			product_name varchar(55) DEFAULT '' NOT NULL,
			price varchar(55) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		update_option( "abandoned_db_version", $abandoned_db_version );
	}

	add_option( 'abandoned_db_version', $abandoned_db_version );
}

function insert_table_data($arr_data) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'abandoned';

	$wpdb->insert(
		$table_name,
		array(
			'time' => $arr_data["time"],
			'first_name' => $arr_data["first_name"],
			'last_name' => $arr_data["last_name"],
			'phone' => $arr_data["phone"],
			'email' => $arr_data["email"],
			'product_name' => $arr_data["product_name"],
			'price' => $arr_data["price"],
		)
	);
}

add_action( 'wp_enqueue_scripts', 'abandoned_assets');
function abandoned_assets() {
    wp_enqueue_script( 'abandoned-orders', plugins_url( '/assets/abandoned-script.js', __FILE__ ), array('jquery') );
	wp_localize_script('abandoned-orders', 'abandonedPlugin', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'href_checkout' => wc_get_checkout_url(),
		'nonce' => wp_create_nonce( 'abandoned' )
	));
}

add_action( 'admin_menu', 'abandoned_admin_page');
function abandoned_admin_page(){
	$hook_suffix = add_menu_page( 'Abandoned orders', 'Abandoned', 'manage_options', 'abandoned-orders', 'abandoned_menu_page', plugins_url( '/assets/images/icon.png', __FILE__ ), 80 );
	add_action( "admin_print_scripts-{$hook_suffix}", 'abandoned_admin_scripts' );
}
function abandoned_menu_page(){
	require plugin_dir_path(__FILE__) . 'assets/abandoned-options.php';
}
function abandoned_admin_scripts(){
	wp_enqueue_style( 'abandoned-main-style', plugins_url('/assets/admin-main.css', __FILE__) );
	wp_enqueue_script( 'abandoned-main-js', plugins_url( '/assets/admin-main.js', __FILE__ ), array('jquery'), false, true );
}
// Define the uninstall callback function
function my_plugin_uninstall() {
    global $wpdb;

    // Define the table name
    $table_name = $wpdb->prefix . 'abandoned';

    // Drop the table from the database
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

// Register the uninstall hook
register_uninstall_hook( __FILE__, 'my_plugin_uninstall' );
