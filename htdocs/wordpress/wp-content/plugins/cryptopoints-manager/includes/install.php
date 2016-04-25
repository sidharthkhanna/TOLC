<?php
/**
 * Cryptopoints Manager installation script
 */

function cpm_install() {

	// set default values
	add_option('pm_auth_key', substr(md5(uniqid()),3,10));
	add_option('pm_comment_points', 5);
	add_option('pm_reg_points', 100);
	add_option('pm_del_comment_points', 5);
	add_option('pm_post_points', 20);
	add_option('pm_prefix', '$');
	add_option('pm_suffix', '');
	add_option('pm_about_posts', true);
	add_option('pm_about_comments', true);
	add_option('pm_topfilter', array());
	add_option('pm_ver', PM_VER);

	// create database
	global $wpdb;
	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

	if($wpdb->get_var("SHOW TABLES LIKE '".PM_DB."'") != PM_DB || (int) get_option('pm_db_version') < 1.3) {
		$sql1 = "CREATE TABLE " . PM_DB . " (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			uid bigint(20) NOT NULL,
			type VARCHAR(256) NOT NULL,
			data TEXT NOT NULL,
			points bigint(20) NOT NULL,
			timestamp bigint(20) NOT NULL,
			UNIQUE KEY id (id)
		) {$charset_collate};";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta($sql1);
	}

	if($wpdb->get_var("SHOW TABLES LIKE '".PM_DB_PAYOUT."'") != PM_DB_PAYOUT || (int) get_option('pm_db_version') < 1.3) {
		$sql2 = "CREATE TABLE " . PM_DB_PAYOUT . " (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			uid bigint(20) NOT NULL,
			nickname VARCHAR(256) NOT NULL,
			coin VARCHAR(256),
			wallet VARCHAR(256),
			amount FLOAT NOT NULL,
			date_time DATETIME NOT NULL,
			UNIQUE KEY id (id)
		) {$charset_collate};";
		dbDelta($sql2);
		add_option("pm_db_version", 1.3);
	}

}
