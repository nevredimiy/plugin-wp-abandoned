<?php
/*
 * Plugin Name: Abandoned Orders
 * Description: This plugin collects data from the checkout page when the "Checkout" button is clicked.
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
	insert_table_data($arr_data);
	wp_die();
}

function abandoned_create_table () {
	global $wpdb;
  	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . "abandoned";
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		first_name tinytext DEFAULT NULL,
		last_name tinytext DEFAULT NULL,
		phone varchar(12) DEFAULT '' NOT NULL,
		email varchar(55) DEFAULT '' NULL,
		product_name varchar(55) DEFAULT '' NULL,
		price varchar(55) DEFAULT '' NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}


function insert_table_data($arr_data) {

	global $wpdb;

	$table_name = $wpdb->prefix . 'abandoned';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		abandoned_create_table();
	}

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
	$href_checkout = '';
	if(function_exists('wc_get_checkout_url')){
		$href_checkout = wc_get_checkout_url();
	}
    wp_enqueue_script( 'abandoned-orders', plugins_url( '/assets/abandoned-script.js', __FILE__ ), array('jquery') );
	wp_localize_script('abandoned-orders', 'abandonedPlugin', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'href_checkout' => $href_checkout,
		'nonce' => wp_create_nonce( 'abandoned' ),
		'selectors' => array(
			'first_name' => get_option( 'first_name' ),
			'last_name' => get_option( 'last_name' ),
			'phone' => get_option( 'phone' ),
			'email' => get_option( 'email' ),
			'product_name' => get_option( 'product_name' ),
			'price' => get_option( 'price' ),
			'trigger_element' => get_option( 'trigger_element' ),
			'event_el' => get_option( 'event_el' ),
			)
	));
}

add_action( 'admin_menu', 'abandoned_admin_page');
function abandoned_admin_page(){
	$hook_suffix = add_menu_page( 'Abandoned orders', 'Abandoned', 'manage_options', 'abandoned-orders', 'abandoned_menu_page', plugins_url( '/assets/images/icon.png', __FILE__ ), 80 );
	add_action( "admin_print_scripts-{$hook_suffix}", 'abandoned_admin_scripts' );
	add_submenu_page( 'abandoned-orders', 'Settings', 'Abandoned orders', 'manage_options', 'abandoned-orders', 'abandoned_menu_page', 1 );
	add_submenu_page( 'abandoned-orders', 'Settings', 'Settings', 'manage_options', 'abandoned-settings', 'abandoned_settings_page', 2 );
}
function abandoned_menu_page(){
	require plugin_dir_path(__FILE__) . 'assets/template/abandoned-options.php';
}
function abandoned_settings_page(){
	require plugin_dir_path( __FILE__ ) . 'assets/template/abandoned-settings.php';
}

function abandoned_admin_scripts(){
	wp_enqueue_style( 'abandoned-main-style', plugins_url('/assets/admin-main.css', __FILE__) );
	wp_enqueue_script( 'abandoned-main-js', plugins_url( '/assets/admin-main.js', __FILE__ ), array('jquery'), false, true );
}

function abandoned_activate() {
	abandoned_create_table ();
}
register_activation_hook( __FILE__, 'abandoned_activate' );

function abandoned_deactivate() {
	global $wpdb;
    $table_name = $wpdb->prefix . 'abandoned';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}
register_deactivation_hook( __FILE__, 'abandoned_deactivate' );


// Define the uninstall callback function
function abandoned_uninstall() {
    global $wpdb;

    // Define the table name
    $table_name = $wpdb->prefix . 'abandoned';

    // Drop the table from the database
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}
// Register the uninstall hook
register_uninstall_hook( __FILE__, 'abandoned_uninstall' );

// ---------------- Abandoned Options -------------------------
add_action( 'admin_init', 'abandoned_custom_settings' );
function abandoned_custom_settings() {
	register_setting( 'abandoned_general_group', 'first_name' );
	register_setting( 'abandoned_general_group', 'last_name' );
	register_setting( 'abandoned_general_group', 'phone' );
	register_setting( 'abandoned_general_group', 'email' );
	register_setting( 'abandoned_general_group', 'product_name' );
	register_setting( 'abandoned_general_group', 'price' );
	register_setting( 'abandoned_general_group', 'trigger_element' );
	register_setting( 'abandoned_general_group', 'event_el' );
	add_settings_section( 'abandoned_general_section', 'Choosing a selector for an element', '', 'abandoned-orders' );
	add_settings_section( 'abandoned_trigger_section', 'Element and trigger selection', function () {
		echo 'An event that will trigger a client data record';
	}, 'abandoned-orders' );
	add_settings_field( 'first_name', 'First Name', 'abandoned_first_name', 'abandoned-orders', 'abandoned_general_section', array( 'label_for' => 'first_name' ) );
	add_settings_field( 'last_name', 'Last Name', 'abandoned_last_name', 'abandoned-orders', 'abandoned_general_section', array( 'label_for' => 'last_name' ) );
	add_settings_field( 'phone', 'Phone', 'abandoned_phone', 'abandoned-orders', 'abandoned_general_section', array( 'label_for' => 'phone' ) );
	add_settings_field( 'email', 'Email', 'abandoned_email', 'abandoned-orders', 'abandoned_general_section', array( 'label_for' => 'email' ) );
	add_settings_field( 'product_name', 'Product Name', 'abandoned_product_name', 'abandoned-orders', 'abandoned_general_section', array( 'label_for' => 'product_name' ) );
	add_settings_field( 'price', 'Price', 'abandoned_price', 'abandoned-orders', 'abandoned_general_section', array( 'label_for' => 'price' ) );
	add_settings_field( 'trigger_element', 'Element', 'abandoned_trigger_event', 'abandoned-orders', 'abandoned_trigger_section', array( 'label_for' => 'trigger_element' ) );
	add_settings_field( 'event_el', 'Event', 'abandoned_event', 'abandoned-orders', 'abandoned_trigger_section' );
}
function abandoned_first_name() {
	$first_name_selection = esc_attr( get_option( 'first_name' ) );
	echo '<input type="text" name="first_name" id="first_name" value="' . $first_name_selection . '" placeholder="#billing_first_name">';
}
function abandoned_last_name() {
	$last_name_selection = esc_attr( get_option( 'last_name' ) );
	echo '<input type="text" name="last_name" id="last_name" value="' . $last_name_selection . '" placeholder="#billing_last_name" >';
}
function abandoned_phone() {
	$phone_selection = esc_attr( get_option( 'phone' ) );
	echo '<input type="text" name="phone" id="phone" value="' . $phone_selection . '" placeholder="#billing_phone">';
}
function abandoned_email() {
	$email_selection = esc_attr( get_option( 'email' ) );
	echo '<input type="text" name="email" id="email" value="' . $email_selection . '" placeholder="#billing_email" >';
}
function abandoned_product_name() {
	$product_name_selection = esc_attr( get_option( 'product_name' ) );
	echo '<input type="text" name="product_name" id="product_name" value="' . $product_name_selection . '" placeholder="#billing_product_name">';
}
function abandoned_price() {
	$price_selection = esc_attr( get_option( 'price' ) );
	echo '<input type="text" name="price" id="price" value="' . $price_selection . '" placeholder="#billing_price" >';
}
function abandoned_trigger_event() {
	$trigger_element = esc_attr( get_option( 'trigger_element' ) );
	echo '<input type="text" name="trigger_element" id="trigger_element" value="' . $trigger_element . '" placeholder="Selector, exmp. #billing_phone" >';
}

function abandoned_event () {
	$events = array('blur', 'focus', 'click');
	$event_el = esc_attr( get_option( 'event_el' ) );
	echo '<select name="event_el">';
		foreach ($events as $event) {
			if($event == $event_el) {
		?>
				<option value="<?php echo $event; ?>" selected="selected"><?php echo $event; ?></option>
		<?php
			} else {
		?>
				<option value="<?php echo $event; ?>"><?php echo $event; ?></option>
		<?php
			}
		}
	echo '</select>';
}