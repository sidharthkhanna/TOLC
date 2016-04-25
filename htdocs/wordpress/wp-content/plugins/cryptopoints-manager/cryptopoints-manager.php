<?php
/*
Plugin Name: Cryptopoints Manager
Plugin URI: http://cryptidcurrency.org/cryptopoints-manager/
Description: Cryptopoints Manager, a joint collaboration between CodeAndMore.com and Cryptidcurrency.org, is a gamification style point management system where users can earn points on a WP site by completing tasks specified by the site administrator. Users earn their points by regularly logging in, posting comments, sharing on social media and creating posts. As users increase points, their ranking also increases and is displayed using ready-to-go widgets. The best part of all (this is where cryptocurrency comes in to play); users can receive cryptocurrency rewards based on their accumulated points and leaderboard ranking! 
Version: 1.2.3
Author: Cryptidcurrency.org & CodeAndMore.com
Author URI: http://cryptidcurrency.org
License: GPLv2
*/

/*  Copyright 2014  Cryptidcurrency.org  (email : librarian [at] cryptidcurrency [dot] org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

global $wpdb;

/** Define constants */
define('PM_VER', '1.2.0');
define('PM_DB', $wpdb->base_prefix . 'pm');
define('PM_DB_PAYOUT', $wpdb->base_prefix . 'pm_payout');
define('PM_URL', WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ));
define('PM_PATH', dirname( __FILE__ ) . '/');

require_once PM_PATH . 'admin.php';
require_once PM_PATH . 'includes/functions.php';
require_once PM_PATH . 'includes/widgets.php';
require_once PM_PATH . 'includes/install.php';
require_once PM_PATH . 'includes/upgrade.php';
require_once PM_PATH . 'payout/payout.php';

/** Hook for plugin installation */
register_activation_hook( __FILE__, 'cpm_activate' );
function cpm_activate() {
	cpm_install();
}

function cpm_version_check() {
	global $wp_version, $pm_plugin_info;
	$pm_plugin_info = get_plugin_data( __FILE__ );
	$require_wp = "3.9"; /* Wordpress at least requires version */
	$plugin = plugin_basename( __FILE__ );
	if ( version_compare( $wp_version, $require_wp, "<" ) ) {
		if ( is_plugin_active( $plugin ) ) {
			deactivate_plugins( $plugin );
			wp_die( "<strong>" . $pm_plugin_info['Name'] . " </strong> requires <strong>WordPress " . $require_wp . "</strong> or higher, that is why it has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to the WordPress <a href='" . get_admin_url( null, 'plugins.php' ) . "'>Plugins page</a>." );
		}
	}

	$current_version = get_option( 'pm_ver', '1.0.0' );
	$upgraded = false;

	if ( version_compare( $current_version, '1.2.0', '<' ) ) {
		$upgraded = true;
	}
	if ( $upgraded ) {
		update_option( 'pm_ver', PM_VER );
	}
}

register_deactivation_hook( __FILE__, 'cpm_deactivate' );
function cpm_deactivate() {
	clear_all_crons( array( 'cpm_create_frequency_payout' ) );
}
