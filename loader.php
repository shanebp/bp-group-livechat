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
	wp_enqueue_style( 'bp-group-livechat-style', plugin_dir_url( __FILE__ ) . 'includes/css/bp-group-livechat-display.css' );
}

add_action( 'bp_enqueue_scripts', 'bp_group_livechat_enqueue_styes' );

// create the tables
function bp_group_livechat_activate() {
	global $wpdb;

	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_group_livechat (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		group_id bigint(20) NOT NULL,
		  		user_id bigint(20) NOT NULL,
		  		message_content text
		 	   ) {$charset_collate};";

	$sql[] = "CREATE TABLE {$wpdb->base_prefix}bp_group_livechat_online (
		  		id bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  		group_id bigint(20) NOT NULL,
		  		user_id bigint(20) NOT NULL,
		  		timestamp int(11) NOT NULL
		 	   ) {$charset_collate};";

	require_once( ABSPATH . 'wp-admin/upgrade-functions.php' );

	dbDelta($sql);

	//update_site_option( 'bp-group-livechat-db-version', BP_GROUP_LIVECHAT_DB_VERSION );
}
register_activation_hook( __FILE__, 'bp_group_livechat_activate' );

?>