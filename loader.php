<?php
/*
Plugin Name: BuddyPress Group Live Chat
Plugin URI: https://wordpress.org/plugins/bp-group-livechat
Description: Ajax live chat for groups
Version: 1.2.0
Requires at least: WordPress 3.5.1, BuddyPress 1.7.2
Tested up to: 5.1
License: GPL V2
Author: Venutius
Author URI: http://buddyuser.com
*/
if ( !defined( 'ABSPATH' ) ) exit;

define ( 'BP_GROUP_LIVECHAT_IS_INSTALLED', 1 );
define ( 'BP_GROUP_LIVECHAT_VERSION', '1.0' );
define ( 'BP_GROUP_LIVECHAT_DB_VERSION', '1.3' );
if ( !defined( 'BP_GROUP_LIVECHAT_SLUG' ) )
	define ( 'BP_GROUP_LIVECHAT_SLUG', 'live-chat' );


/* Only load the component if BuddyPress is loaded and initialized. */
function bp_group_livechat_init() {
	require( dirname( __FILE__ ) . '/includes/bp-group-livechat-core.php' );
}
add_action( 'bp_init', 'bp_group_livechat_init' );

function bp_group_livechat_enqueue_styes() {

	if ( bp_is_group() ) {

		wp_enqueue_style( 'bp-group-livechat-style', plugin_dir_url( __FILE__ ) . 'includes/css/bp-group-livechat-display.css' );
		wp_enqueue_script( 'bp-group-livecht-times', plugin_dir_url( __FILE__ ) . 'includes/js/jquery-timers-1.2.js' );
		wp_register_script( 'bp-group-livechat-frontend', plugin_dir_url( __FILE__ ) . 'includes/js/bp-group-livechat-frontend.js');
		wp_enqueue_script( 'bp-group-livechat-frontend' );
		wp_localize_script( 'bp-group-livechat-frontend', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php'), 'check_nonce' => wp_create_nonce('bpgl-nonce') ) );

	}
}

add_action( 'bp_enqueue_scripts', 'bp_group_livechat_enqueue_styes' );

// create the tables
function bp_group_livechat_activate() {
	global $wpdb;

	$charset_collate = '';

	if ( ! empty ( $wpdb->charset ) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

	if ( ! empty ( $wpdb->collate ) )
		$charset_collate .= " COLLATE $wpdb->collate";

	$table_name = $wpdb->base_prefix . "bp_group_livechat";

	if ( $wpdb->get_var("show tables like '$table_name'") != $table_name ) {
		$sql = "CREATE TABLE $table_name (
			id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
			group_id BIGINT UNSIGNED NOT NULL,
			user_id BIGINT UNSIGNED NOT NULL,
			message_content TEXT
		) $charset_collate;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	$table_name = $wpdb->base_prefix . "bp_group_livechat_online";

	if ( $wpdb->get_var("show tables like '$table_name'") != $table_name ) {
		$sql = "CREATE TABLE $table_name (
		  		id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
		  		group_id BIGINT UNSIGNED NOT NULL,
		  		user_id BIGINT UNSIGNED NOT NULL,
		  		timestamp INT UNSIGNED NOT NULL
		) $charset_collate;";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	//update_site_option( 'bp-group-livechat-db-version', BP_GROUP_LIVECHAT_DB_VERSION );

}
register_activation_hook( __FILE__, 'bp_group_livechat_activate' );


