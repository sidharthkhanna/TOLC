<?php
/**
 * Cryptopoints Manager Upgrade Script
 */

if ( get_option( 'pm_db_version' ) < 1.3 ) {
	if ( is_admin() ) {
		cpm_install();
		update_option( 'pm_db_version', 1.3 );
		global $wpdb;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $wpdb->base_prefix . "pm'" ) == $wpdb->base_prefix . "pm" ) {
			$rows = $wpdb->get_results( 'DESCRIBE ' . $wpdb->base_prefix . "pm" );
			$cols = array();
			foreach ( $rows as $row ) {
				$cols[] = $row->Field;
			}
			if ( !in_array( 'source', $cols ) ) {
				//Nothing to import, old database has wrong database structure
			} else {
				$results = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "pm" );
				$count = 0;
				$count1 = 0;
				$left = array();
				foreach ( $results as $result ) {
					$count1++;
					if ( $result->type == 'comment' || $result->type == 'admin' || $result->type == 'post' || $result->type == 'reg' || $result->type == 'login' || $result->type == 'donate' || $result->type == 'login' ) {
						if ( $result->type == 'login' ) {
							$result->type = 'dailypoints';
						}
						$wpdb->query( "INSERT INTO `" . PM_DB . "` (`id`, `uid`, `type`, `data`, `points`, `timestamp`) VALUES (NULL, '" . $result->uid . "', '" . $result->type . "', " . $result->source . ", '" . $result->points . "', " . $result->timestamp . ");" );
						// Not removing entries from old database
						//$wpdb->query("DELETE FROM ".$wpdb->base_prefix."pm WHERE id=".$result->id);
						$count++;
					} else {
						$left[] = $result->type;
					}
				}
				echo '<div class="updated"><p><strong>' . __( 'Points Manager Updated' ) . ': </strong>' . __( 'Your database has been updated and  ', 'pm' ) . ' ' . $count . ' ' . __( 'out of', 'pm' ) . ' ' . $count1 . ' ' . __( 'log items were imported', 'pm' ) . '.</p></div>';
				$left = array_unique( $left );
				if ( count( $left ) > 0 ) {
					echo '<div class="error"><p><strong>' . __( 'The following log types were not imported', 'pm' ) . ':</strong> ' . implode( $left, ', ' ) . '</p></div>';
				}
			}
		} else {
			//Nothing to import, old database not found
		}
	}
}

?>
