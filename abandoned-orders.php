<?php
/*
 * Plugin Name: Abandoned Orders
 * Description: Это плагин для сбора данных со страницы checkout, когда еще не нажата кнопка submit
 *
 * Authot URI: https://github.com/nevredimiy
 * Author:      Artem Litvinov
 */

add_action('wp_ajax_get_client_data', 'ajax_save_client_data');
add_action('wp_ajax_nopriv_get_client_data', 'ajax_save_client_data');

function ajax_save_client_data(){
	$file = plugin_dir_path(__FILE__) . 'client_data_log.txt';

	$format = "%s %s %s %s - %s \n";
	$data = sprintf($format, $_POST['firstName'], $_POST['lastName'], $_POST['phone'], $_POST['email'], $_POST['dNow']);

	// Записываем содержимое в файл в конец строки

	$open = fopen($file, "a");
    $write = fputs($open, $data);
    fclose($open);

	wp_die();
}

add_action( 'wp_enqueue_scripts', 'abandoned_assets');
function abandoned_assets() {
    wp_enqueue_script( 'abandoned-orders', plugins_url( '/assets/abandoned-script.js', __FILE__ ), array('jquery') );
	wp_localize_script('abandoned-orders', 'myPlugin', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
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
?>