<?php
/*
|--------------------------------------------------------------------------
| Automated Reward Distribution Settings
|--------------------------------------------------------------------------
*/

function cpm_clear_all_crons( $hook ) {
	$crons = _get_cron_array();
	if ( empty($crons) ) {
		return;
	}
	foreach ( $crons as $timestamp => $cron ) {
		if ( !empty($cron[$hook]) ) {
			unset($crons[$timestamp][$hook]);
		}
	}
	_set_cron_array( $crons );
}

function cpm_create_frequency_payout_schedule( $frequency, $args ) {
	if ( !wp_next_scheduled( 'pm_create_frequency_payout', $args ) ) {
		wp_schedule_event( strtotime( $args['start_date'] ), $frequency, 'cpm_create_frequency_payout', array( $args ) );
	}
}

add_action( 'cpm_create_frequency_payout', 'cpm_auto_sent_all_users' );
function cpm_auto_sent_all_users( $args ) {
	$number_coins = get_option( 'number_coins' );
	$account = get_option( 'account' );
	for ( $i = 1; $i <= $number_coins; $i++ ) {
		$w[$i] = to_slug( $account[$i]['name'] );
	}
	$sum_points = sum_points();
	$users = get_users();
	foreach ( $users as $user ) {
		for ( $i = 1; $i <= $number_coins; $i++ ) {
			if ( $w[$i] != to_slug( $args['name'] ) ) continue;
			$user_wallet[$i] = get_user_meta( $user->id, $w[$i], 1 );
			$percent = cpm_getPoints( $user->id ) / $sum_points;
			if ( $account[$i]['enabled'] && $user_wallet[$i] ) {
				if ( !empty($args['fixed_payout']) && empty($args['random_payout']) ) {
					$amount = $args['fixed_payout'];
					if ( $args['type'] == 'ratio' ) $amount *= $percent;
					$argsx = array(
						'username'    => $account[$i]['username'],
						'password'    => $account[$i]['password'],
						'ip'          => $account[$i]['ip'],
						'rpc_port'    => $account[$i]['rpc_port'],
						'user_wallet' => $user_wallet[$i],
						'amount'      => $amount,
					);
					cpm_send_coins( $argsx['username'], $argsx['password'], $argsx['ip'], $argsx['rpc_port'], $w[$i], $argsx['user_wallet'], $amount, $user->id );
				} elseif ( empty($args['fixed_payout']) && !empty($args['random_payout']) ) {
					$rand = cpm_explode( $args['random_payout'] );
					if ( $rand ) {
						$amount = lcg_randf( $rand['from'], $rand['to'] );
						if ( $args['type'] == 'ratio' ) $amount *= $percent;
					} else {
						$amount = 0.0001;
					}
					$argsx = array(
						'username'    => $account[$i]['username'],
						'password'    => $account[$i]['password'],
						'ip'          => $account[$i]['ip'],
						'rpc_port'    => $account[$i]['rpc_port'],
						'user_wallet' => $user_wallet[$i],
						'amount'      => $amount,
					);
					cpm_send_coins( $argsx['username'], $argsx['password'], $argsx['ip'], $argsx['rpc_port'], $w[$i], $argsx['user_wallet'], $amount, $user->id );
				}
			}
		}
	}
}

/*
|--------------------------------------------------------------------------
| Leaderboard Payout Events
|--------------------------------------------------------------------------
*/
function cpm_sent_single_user( $uid ) {
	$number_coins = get_option( 'number_coins' );
	$account = get_option( 'account' );
	$number_thresholds = get_option( 'number_thresholds' );
	for ( $i = 1; $i <= $number_coins; $i++ ) {
		$w[$i] = to_slug( $account[$i]['name'] );
	}
	$threshold = get_option( 'threshold' );
	$levels = array( $threshold[1]['level'], $threshold[2]['level'], $threshold[3]['level'], $threshold[4]['level'] );
	$v = find_level( $levels, cpm_getPoints( $uid ) );

	for ( $j = 1; $j <= $number_thresholds; $j++ ) {
		if ( $threshold[$j]['level'] == $v ) {
			$row = $threshold[$j];
		}
	}

	for ( $i = 1; $i <= 3; $i++ ) {
		$user_wallet[$i] = get_user_meta( $uid, $w[$i], 1 );
		if ( $account[$i]['enabled'] ) {
			$u = $account[$i]['username'];
			$pw = $account[$i]['password'];
			$ip = $account[$i]['ip'];
			$p = $account[$i]['rpc_port'];
			cpm_send_coins( $u, $pw, $ip, $p, to_slug( $account[$i]['name'] ), $user_wallet[$i], $row[$w[$i]], $uid );
		}
	}
}

function cpm_send_coins( $username, $password, $ip, $rpc_port, $coin, $wallet, $amount, $uid ) {
	require_once dirname( __FILE__ ) . '/RPCcoin.php';
	$rpccoin = new RPCcoin( $username, $password, $ip, $rpc_port );
	$rpccoin->sendtoaddress( $wallet, floatval( $amount ) );

	require_once ABSPATH . 'wp-includes/pluggable.php';
	$user_info = get_userdata( $uid );

	global $wpdb;
	$table_name = PM_DB_PAYOUT;

	$sql = $wpdb->prepare(
		"INSERT INTO $table_name (uid, nickname, coin, wallet, amount, date_time) VALUES (%d, %s, %s, %s, %s, %s)",
		$uid, $user_info->user_login, $coin, $wallet, $amount, date( 'Y-m-d H:i:s', time() )
	);

	$wpdb->query( $sql );
}

add_action( 'rank_up', 'cpm_rank_up', 10, 1 );
function cpm_rank_up( $uid ) {
	$method_level = get_option( 'method_level' );
	if ( $method_level )
		cpm_sent_single_user( $uid );
}

/*
|--------------------------------------------------------------------------
| One-Time Manual Payout Events
|--------------------------------------------------------------------------
*/
function cpm_manual_sent_all_users( $manual ) {
	$number_coins = get_option( 'number_coins' );
	$account = get_option( 'account' );
	for ( $i = 1; $i <= $number_coins; $i++ ) {
		$w[$i] = to_slug( $account[$i]['name'] );
	}

	$users = get_users();
	foreach ( $users as $user ) {
		for ( $i = 1; $i <= 3; $i++ ) {
			$user_wallet[$i] = get_user_meta( $user->id, $w[$i], 1 );
			if ( $account[$i]['enabled'] && $manual[$i]['enabled'] && $user_wallet[$i] ) {
				$percent = cpm_getPoints( $user->id ) / sum_points();
				if ( !empty($manual[$i]['fixed_payout']) && empty($manual[$i]['random_payout']) ) {
					$amount = $manual[$i]['fixed_payout'];
					if ( $manual[$i]['type'] == 'ratio' ) $amount *= $percent;
					cpm_send_coins( $account[$i]['username'], $account[$i]['password'], $account[$i]['ip'], $account[$i]['rpc_port'], to_slug( $account[$i]['name'] ), $user_wallet[$i], $amount, $user->id );
				} elseif ( empty($manual[$i]['fixed_payout']) && !empty($manual[$i]['random_payout']) ) {
					$rand = cpm_explode( $manual[$i]['random_payout'] );
					if ( $rand ) {
						$amount = lcg_randf( $rand['from'], $rand['to'] );
						if ( $manual[$i]['type'] == 'ratio' ) $amount *= $percent;
					} else {
						$amount = 0.0001;
					}
					cpm_send_coins( $account[$i]['username'], $account[$i]['password'], $account[$i]['ip'], $account[$i]['rpc_port'], to_slug( $account[$i]['name'] ), $user_wallet[$i], $amount, $user->id );
				}
			}
		}
	}
}
