<?php

/** Get difference in time */
function cpm_relativeTime( $ts ) {
	if ( !ctype_digit( $ts ) )
		$ts = strtotime( $ts );

	$diff = time() - $ts;
	if ( $diff == 0 )
		return 'now';
	elseif ( $diff > 0 ) {
		$day_diff = floor( $diff / 86400 );
		if ( $day_diff == 0 ) {
			if ( $diff < 60 )
				return 'just now';
			if ( $diff < 120 )
				return '1 minute ago';
			if ( $diff < 3600 )
				return floor( $diff / 60 ) . ' minutes ago';
			if ( $diff < 7200 )
				return '1 hour ago';
			if ( $diff < 86400 )
				return floor( $diff / 3600 ) . ' hours ago';
		}
		if ( $day_diff == 1 )
			return 'Yesterday';
		if ( $day_diff < 7 )
			return $day_diff . ' days ago';
		if ( $day_diff < 31 )
			return ceil( $day_diff / 7 ) . ' weeks ago';
		if ( $day_diff < 60 )
			return 'last month';
		return date( 'F Y', $ts );
	} else {
		$diff = abs( $diff );
		$day_diff = floor( $diff / 86400 );
		if ( $day_diff == 0 ) {
			if ( $diff < 120 )
				return 'in a minute';
			if ( $diff < 3600 )
				return 'in ' . floor( $diff / 60 ) . ' minutes';
			if ( $diff < 7200 )
				return 'in an hour';
			if ( $diff < 86400 )
				return 'in ' . floor( $diff / 3600 ) . ' hours';
		}
		if ( $day_diff == 1 )
			return 'Tomorrow';
		if ( $day_diff < 4 )
			return date( 'l', $ts );
		if ( $day_diff < 7 + ( 7 - date( 'w' ) ) )
			return 'next week';
		if ( ceil( $day_diff / 7 ) < 4 )
			return 'in ' . ceil( $day_diff / 7 ) . ' weeks';
		if ( date( 'n', $ts ) == date( 'n' ) + 1 )
			return 'next month';
		return date( 'F Y', $ts );
	}
}

function millitime() {
	$microtime = microtime();
	$comps = explode( ' ', $microtime );

	return sprintf( '%03d', $comps[0] * 1000 );
}

function find_level( $array, $num ) {
	sort( $array );
	if ( $num >= $array[count( $array ) - 1] ) {
		return $array[count( $array ) - 1];
	}
	for ( $i = 0; $i < count( $array ); $i++ ) {
		if ( array_key_exists( $i + 1, $array ) ) {
			if ( ( $num / $array[$i] >= 1 ) && ( $num / $array[$i + 1] < 1 ) ) {
				return $array[$i];
			}
		}
	}
}

/** Class for cURL */
class CURL {
	var $callback = false;

	function setCallback( $func_name ) {
		$this->callback = $func_name;
	}

	function doRequest( $method, $url, $vars ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, 'cookie.txt' );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, 'cookie.txt' );
		if ( $method == 'POST' ) {
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $vars );
		}
		$data = curl_exec( $ch );
		curl_close( $ch );
		if ( $data ) {
			if ( $this->callback ) {
				$callback = $this->callback;
				$this->callback = false;

				return call_user_func( $callback, $data );
			} else {
				return $data;
			}
		} else {
			return false;
		}
	}

	function get( $url ) {
		return $this->doRequest( 'GET', $url, 'NULL' );
	}

	function post( $url, $vars ) {
		return $this->doRequest( 'POST', $url, $vars );
	}
}

/** Core function loaded */
function cpm_ready() {
	return true;
}

/** Get current logged in user */
function cpm_currentUser() {
	require_once( ABSPATH . WPINC . '/pluggable.php' );
	global $current_user;
	get_currentuserinfo();

	return $current_user->ID;
}

/** Get number of points */
function cpm_getPoints( $uid ) {
	$points = get_user_meta( $uid, 'cpoints', 1 );
	if ( $points == '' ) {
		return 0;
	} else {
		return $points;
	}
}

/** Update points */
function cpm_updatePoints( $uid, $points ) {
	// no negative points
	if ( $points < 0 ) {
		$points = 0;
	}
	$last_points = get_user_meta( $uid, 'cpoints', true );
	$last_rank = get_user_meta( $uid, 'rank', true );
	update_user_meta( $uid, 'cpoints', $points );

	$new_points = get_user_meta( $uid, 'cpoints', true );
	$new_rank = cpm_module_ranks_getRank( $uid );
	update_user_meta( $uid, 'rank', $new_rank );

	if ( ( $last_points < $new_points ) && ( $last_rank != $new_rank ) ) {
		do_action( 'rank_up', $uid );
	}
}

/** Alter points */
function cpm_alterPoints( $uid, $points ) {
	cpm_updatePoints( $uid, cpm_getPoints( $uid ) + $points );
}

/** Formats points with prefix and suffix */
function cpm_formatPoints( $points ) {
	if ( $points == 0 ) {
		$points = '0';
	}

	return get_option( 'pm_prefix' ) . $points . get_option( 'pm_suffix' );
}

/** Display points */
function cpm_displayPoints( $uid = 0, $return = 0, $format = 1 ) {
	if ( $uid == 0 ) {
		if ( !is_user_logged_in() ) {
			return false;
		}
		$uid = cpm_currentUser();
	}

	if ( $format == 1 ) {
		$fpoints = cpm_formatPoints( cpm_getPoints( $uid ) );
	} else {
		$fpoints = cpm_getPoints( $uid );
	}

	if ( !$return ) {
		echo $fpoints;
	} else {
		return $fpoints;
	}
}

/** Get points of all users into an array */
function cpm_getAllPoints( $amt = 0, $filter_users = array(), $start = 0 ) {
	global $wpdb;
	if ( $amt > 0 ) {
		$limit = ' LIMIT ' . $start . ',' . $amt;
	}
	$extraquery = '';
	if ( count( $filter_users ) > 0 ) {
		$extraquery = ' WHERE ' . $wpdb->base_prefix . 'users.user_login != \'';
		$extraquery .= implode( "' AND " . $wpdb->base_prefix . "users.user_login != '", $filter_users );
		$extraquery .= '\' ';
	}
	$array = $wpdb->get_results( 'SELECT ' . $wpdb->base_prefix . 'users.id, ' . $wpdb->base_prefix . 'users.user_login, ' . $wpdb->base_prefix . 'users.display_name, ' . $wpdb->base_prefix . 'usermeta.meta_value
		FROM `' . $wpdb->base_prefix . 'users`
		LEFT JOIN `' . $wpdb->base_prefix . 'usermeta` ON ' . $wpdb->base_prefix . 'users.id = ' . $wpdb->base_prefix . 'usermeta.user_id
		AND ' . $wpdb->base_prefix . 'usermeta.meta_key=\'' . 'cpoints' . '\'' . $extraquery . '
		ORDER BY ' . $wpdb->base_prefix . 'usermeta.meta_value+0 DESC'
	                             . $limit . ';'
		, ARRAY_A );
	foreach ( $array as $x => $y ) {
		$a[$x] = array( "id" => $y['id'], "user" => $y['user_login'], "display_name" => $y['display_name'], "points" => ( $y['meta_value'] == 0 ) ? 0 : $y['meta_value'], "points_formatted" => cpm_formatPoints( $y['meta_value'] ) );
	}

	return $a;
}

/** Adds transaction to logs database */
function cpm_log( $type, $uid, $points, $data ) {
	$userinfo = get_userdata( $uid );
	if ( $userinfo->user_login == '' ) {
		return false;
	}
	if ( $points == 0 && $type != 'reset' ) {
		return false;
	}
	global $wpdb;
	$wpdb->query( "INSERT INTO `" . PM_DB . "` (`id`, `uid`, `type`, `data`, `points`, `timestamp`)
				  VALUES (NULL, '" . $uid . "', '" . $type . "', '" . $data . "', '" . $points . "', " . time() . ");" );
	do_action( 'cpm_log', $type, $uid, $points, $data );

	return true;
}

/** Alter points and add to logs */
function cpm_points( $type, $uid, $points, $data ) {
	$points = apply_filters( 'cpm_points', $points, $type, $uid, $data );
	cpm_alterPoints( $uid, $points );
	cpm_log( $type, $uid, $points, $data );
}

/** Set points and add to logs */
function cpm_points_set( $type, $uid, $points, $data ) {
	$points = apply_filters( 'cpm_points_set', $points, $type, $uid, $data );
	$difference = $points - cpm_getPoints( $uid );
	cpm_updatePoints( $uid, $points );
	cpm_log( $type, $uid, $difference, $data );
}

/** Get total number of posts */
function cpm_getPostCount( $id ) {
	global $wpdb;

	return (int)$wpdb->get_var( 'SELECT count(id) FROM `' . $wpdb->base_prefix . 'posts` where `post_type`=\'post\' and `post_status`=\'publish\' and `post_author`=' . $id );
}

/** Get total number of comments */
function cpm_getCommentCount( $id ) {
	global $wpdb;

	return (int)$wpdb->get_var( 'SELECT count(comment_ID) FROM `' . $wpdb->base_prefix . 'comments` where `user_id`=' . $id );
}

/** Function to truncate a long string */
function cpm_truncate( $string, $length, $stopanywhere = false ) {
	$string = str_replace( '"', '&quot;', strip_tags( $string ) );

	//truncates a string to a certain char length, stopping on a word if not specified otherwise.
	if ( strlen( $string ) > $length ) {
		//limit hit!
		$string = substr( $string, 0, ( $length - 3 ) );
		if ( $stopanywhere ) {
			//stop anywhere
			$string .= '...';
		} else {
			//stop on a word.
			$string = substr( $string, 0, strrpos( $string, ' ' ) ) . '...';
		}
	}

	return $string;
}

/** Misc logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_misc', 10, 4 );
function cpm_admin_logs_desc_misc( $type, $uid, $points, $data ) {
	if ( $type != 'misc' ) {
		return;
	}
	echo $data;
}

/** Add Points logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_addpoints', 10, 4 );
function cpm_admin_logs_desc_addpoints( $type, $uid, $points, $data ) {
	if ( $type != 'addpoints' ) {
		return;
	}
	echo $data;
}

/** Comments hook */
add_action( 'comment_post', 'cpm_newComment', 10, 2 );
function cpm_newComment( $cid, $status ) {
	$cdata = get_comment( $cid );
	if ( $status == 1 ) {
		do_action( 'cpm_comment_add', $cid );
		cpm_points( 'comment', cpm_currentUser(), apply_filters( 'cpm_comment_points', get_option( 'pm_comment_points' ) ), $cid );
	}
}

/** Comment approved hook */
add_action( 'comment_unapproved_to_approved', 'cpm_commentApprove', 10, 1 );
add_action( 'comment_trash_to_approved', 'cpm_commentApprove', 10, 1 );
add_action( 'comment_spam_to_approved', 'cpm_commentApprove', 10, 1 );
function cpm_commentApprove( $cdata ) {
	do_action( 'cpm_comment_add', $cdata->comment_ID );
	cpm_points( 'comment', $cdata->user_id, apply_filters( 'cpm_comment_points', get_option( 'pm_comment_points' ) ), $cdata->comment_ID );
}

/** Comment unapproved hook */
add_action( 'comment_approved_to_unapproved', 'cpm_commentUnapprove', 10, 1 );
add_action( 'comment_approved_to_trash', 'cpm_commentUnapprove', 10, 1 );
add_action( 'comment_approved_to_spam', 'cpm_commentUnapprove', 10, 1 );
function cpm_commentUnapprove( $cdata ) {
	// check if points were indeed awarded for this comment
	global $wpdb;
	if ( $wpdb->get_var( 'SELECT COUNT(*) FROM ' . PM_DB . ' WHERE type = \'comment\' AND data = ' . $cdata->comment_ID ) == 0 ) {
		return;
	}
	do_action( 'cpm_comment_remove', $cdata->comment_ID );
	cpm_points( 'comment_remove', $cdata->user_id, apply_filters( 'cpm_del_comment_points', -get_option( 'pm_del_comment_points' ) ), $cdata->comment_ID );
}

/** Comments logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_comment', 10, 4 );
function cpm_admin_logs_desc_comment( $type, $uid, $points, $data ) {
	if ( $type != 'comment' ) {
		return;
	}
	$cdata = get_comment( $data );
	if ( $cdata == null ) {
		echo '<span title="' . __( 'Comment removed', 'pm' ) . '...">' . __( 'Comment', 'pm' ) . '</span>';

		return;
	}
	$pid = $cdata->comment_post_ID;
	$pdata = get_post( $pid );
	$ptitle = $pdata->post_title;
	$url = get_permalink( $pid ) . '#comment-' . $data;
	$detail = __( 'Comment', 'pm' ) . ': ' . cpm_truncate( strip_tags( $cdata->comment_content ), 100, false );
	echo '<span title="' . $detail . '">' . __( 'Comment on', 'pm' ) . ' &quot;<a href="' . $url . '">' . $ptitle . '</a>&quot;</span>';
}

/** Comments removal logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_comment_remove', 10, 4 );
function cpm_admin_logs_desc_comment_remove( $type, $uid, $points, $data ) {
	if ( $type != 'comment_remove' ) {
		return;
	}
	_e( 'Comment Deletion', 'pm' );
}

/** Post hook */
add_action( 'publish_post', 'cpm_newPost' );
function cpm_newPost( $pid ) {
	$post = get_post( $pid );
	$uid = $post->post_author;
	global $wpdb;
	$count = (int)$wpdb->get_var( "select count(id) from `" . PM_DB . "` where `type`='post' and `data`=" . $pid );
	if ( $count == 0 ) {
		cpm_points( 'post', $uid, apply_filters( 'cpm_post_points', get_option( 'pm_post_points' ) ), $pid );
	}
}

/** Post logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_post', 10, 4 );
function cpm_admin_logs_desc_post( $type, $uid, $points, $data ) {
	if ( $type != 'post' ) {
		return;
	}
	$post = get_post( $data );
	echo __( 'Post on', 'pm' ) . ' "<a href="' . get_permalink( $post ) . '">' . $post->post_title . '</a>"';
}

/** User registration hook */
add_action( 'user_register', 'cpm_newUser' );
function cpm_newUser( $uid ) {
	cpm_points( 'register', $uid, apply_filters( 'cpm_reg_points', get_option( 'pm_reg_points' ) ), $uid );
}

/** User registration logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_register', 10, 4 );
function cpm_admin_logs_desc_register( $type, $uid, $points, $data ) {
	if ( $type != 'register' ) {
		return;
	}
	_e( 'Registration', 'pm' );
}

/** Admin manage logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_admin', 10, 4 );
function cpm_admin_logs_desc_admin( $type, $uid, $points, $data ) {
	if ( $type != 'admin' ) {
		return;
	}
	$user = get_userdata( $data );
	echo __( 'Points adjusted by ', 'pm' ) . ' "' . $user->user_login . '"';
}

/** Remote site logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_remote', 10, 4 );
function cpm_admin_logs_desc_remote( $type, $uid, $points, $data ) {
	if ( $type != 'remote' ) {
		return;
	}
	list( $name, $url ) = explode( '^', $data );
	echo __( 'Points earned from ' ) . ' "<a href="' . $url . '">' . $name . '</a>"';
}

/** Custom logs hook */
add_action( 'cpm_logs_description', 'cpm_admin_logs_desc_custom', 10, 4 );
function cpm_admin_logs_desc_custom( $type, $uid, $points, $data ) {
	if ( $type != 'custom' ) {
		return;
	}
	echo $data;
}

/** Display top users in page */
add_shortcode( 'cubepoints_top', 'cpm_shortcode_top' );
function cpm_shortcode_top( $atts ) {
	$num = (int)$atts['num'];
	if ( $num < 1 ) {
		$num = 1;
	}
	$top = cpm_getAllPoints( $num, get_option( 'pm_topfilter' ) );
	if ( $atts['class'] != '' ) {
		$class = ' class="' . $atts['class'] . '"';
	}
	if ( $atts['style'] != '' ) {
		$style = ' style="' . $atts['style'] . '"';
	}
	switch ( $atts['display'] ) {
		case 'custom':
			if ( $atts['custom'] == null ) {
				$atts['custom'] = '%user% (%points%)';
			}
			$c = '';
			foreach ( $top as $x => $i ) {
				$text = apply_filters( 'cpm_displayUserInfo', $atts['custom'], $i, $x + 1 );
				$c .= $text;
			}
			break;
		case 'ol':
			$c = '<ol' . $class . $style . '>';
			if ( $atts['custom'] == null ) {
				$atts['custom'] = '<li>%user% (%points%)</li>';
			}
			foreach ( $top as $x => $i ) {
				$text = apply_filters( 'cpm_displayUserInfo', $atts['custom'], $i, $x + 1 );
				$c .= $text;
			}
			$c .= '</ol>';
			break;
		case 'table':
			$c = '<table' . $class . $style . '>';
			if ( $atts['custom'] == null ) {
				$atts['custom'] = '<tr><td>%user%</td><td>%points%</td></tr>';
			}
			foreach ( $top as $x => $i ) {
				$text = apply_filters( 'cpm_displayUserInfo', $atts['custom'], $i, $x + 1 );
				$c .= $text;
			}
			$c .= '</table>';
			break;
		default;
			$c = '<ul' . $class . $style . '>';
			if ( $atts['custom'] == null ) {
				$atts['custom'] = '<li>%user% (%points%)</li>';
			}
			foreach ( $top as $x => $i ) {
				$text = apply_filters( 'cpm_displayUserInfo', $atts['custom'], $i, $x + 1 );
				$c .= $text;
			}
			$c .= '</ul>';
			break;
	}

	return $c;
}

/** Display points info in page */
add_shortcode( 'cpmpoints', 'cpm_shortcode_user' );
function cpm_shortcode_user( $atts ) {
	if ( $atts['user'] != '' ) {
		$u = get_userdatabylogin( $atts['user'] );
		$uid = $u->ID;
		if ( $uid == '' ) {
			return '';
		}

		return cpm_displayPoints( $uid, 1, $atts['format'] );
	} else {
		$uid = cpm_currentUser();
		if ( $uid == '' ) {
			return $atts['not_logged_in'];
		}

		return cpm_displayPoints( $uid, 1, (bool)$atts['format'] );
	}

	return $c;
}

/** Format displays of users */
add_filter( 'cpm_displayUserInfo', 'cpm_displayUserInfo', 10, 3 );
function cpm_displayUserInfo( $string, $y, $place ) {
	$user = get_userdata( $y['id'] );
	$string = str_replace( '%points%', $y['points_formatted'], $string );
	$string = str_replace( '%npoints%', $y['points'], $string );
	$string = str_replace( '%user%', $y['user'], $string );
	$string = str_replace( '%username%', $y['display_name'], $string );
	$string = str_replace( '%userid%', $y['id'], $string );
	$string = str_replace( '%place%', $place, $string );
	$string = str_replace( '%emailhash%', md5( strtolower( $user->user_email ) ), $string );

	return $string;
}

/** Formatting tables */
add_filter( 'cpm_displayTable', 'cpm_displayTable' );
function cpm_displayTable( $string ) {
	$string = '<tr><td>' . $string;
	$string = str_replace( '%d%', '</td><td>', $string );
	$string .= '</td></tr>';

	return $string;
}

/** Hook to process admin manage ajax post request to update points */
add_action( 'wp_ajax_pm_manage_form_submit', 'cpm_manage_form_submit' );
function cpm_manage_form_submit() {

	header( "Content-Type: application/json" );

	if ( !current_user_can( 'manage_options' ) ) {
		$response = json_encode( array( 'error' => __( 'You do not have sufficient permission to manage points!', 'pm' ) ) );
		echo $response;
		exit;
	}

	if ( $_POST['points'] != '' && $_POST['user_id'] != '' ) {
		$points = (int)$_POST['points'];
		$uid = (int)$_POST['user_id'];
		$user = get_userdata( $uid );
		if ( $user->ID == null ) {
			$response = json_encode( array( 'error' => __( 'User does not exist!', 'pm' ) ) );
			echo $response;
			exit;
		}
		if ( $points < 0 ) {
			$points = 0;
		}
		cpm_points_set( 'admin', $uid, $points, cpm_currentUser() );
	} else {
		$response = json_encode( array( 'error' => __( 'Invalid request!', 'pm' ) ) );
		echo $response;
		exit;
	}

	$response = json_encode( array(
		                         'error'            => 'ok',
		                         'points'           => cpm_displayPoints( $uid, 1, 0 ),
		                         'points_formatted' => cpm_displayPoints( $uid, 1, 1 ),
		                         'username'         => $user->user_login,
		                         'user_id'          => $user->ID
	                         ) );
	echo $response;
	exit;

}

/** Hook for add-points autocomplete user suggestion */
add_action( 'wp_ajax_pm_add_points_user_suggest', 'cpm_add_points_user_suggest' );
function cpm_add_points_user_suggest() {

	header( "Content-Type: application/json" );

	if ( !current_user_can( 'manage_options' ) || $_REQUEST['q'] == '' ) {
		$response = json_encode( array() );
		echo $response;
		exit;
	}

	global $wpdb;
	$users = $wpdb->get_results( 'SELECT * from `' . $wpdb->prefix . 'users` WHERE `user_login` LIKE \'' . $_REQUEST['q'] . '%\' LIMIT 10', ARRAY_A );

	$response = array();

	foreach ( $users as $user ) {
		$response[] = implode( "|", array( $user['user_login'], $user['ID'], $user['display_name'], $user['user_email'], md5( trim( strtolower( $user['user_email'] ) ) ) ) );
	}
	$response = json_encode( implode( "\n", $response ) );
	echo $response;
	exit;

}

/** Hook for add-points user query */
add_action( 'wp_ajax_pm_add_points_user_query', 'cpm_add_points_user_query' );
function cpm_add_points_user_query() {

	header( "Content-Type: application/json" );

	if ( !current_user_can( 'manage_options' ) || $_REQUEST['q'] == '' ) {
		$response = json_encode( array() );
		echo $response;
		exit;
	}

	global $wpdb;
	$user = $wpdb->get_row( 'SELECT * from `' . $wpdb->prefix . 'users` WHERE `user_login` LIKE \'' . $wpdb->prepare( trim( $_REQUEST['q'] ) ) . '\' LIMIT 1', ARRAY_A );
	if ( $user['ID'] == null ) {
		$response = json_encode( array() );
		echo $response;
		exit;
	}
	$response = json_encode( array(
		                         'id'           => $user['ID'],
		                         'user_login'   => $user['user_login'],
		                         'display_name' => $user['display_name'],
		                         'email'        => $user['user_email'],
		                         'points'       => cpm_getPoints( $user['ID'] ),
		                         'hash'         => md5( trim( strtolower( $user['user_email'] ) ) )
	                         ) );
	echo $response;
	exit;

}

/** Hook for add-points user update */
add_action( 'wp_ajax_pm_add_points_user_update', 'cpm_add_points_user_update' );
function cpm_add_points_user_update() {

	header( "Content-Type: application/json" );

	if ( !current_user_can( 'manage_options' ) || $_POST['id'] == '' || $_POST['points'] == '' || $_POST['description'] == '' ) {
		$response = json_encode( array( 'status' => 'failed' ) );
		echo $response;
		exit;
	}

	cpm_points( 'addpoints', (int)$_POST['id'], (int)$_POST['points'], htmlentities( $_POST['description'] ) );
	$response = json_encode( array(
		                         'status'    => 'ok',
		                         'newpoints' => cpm_getPoints( (int)$_POST['id'] )
	                         ) );
	echo $response;
	exit;

}

function cpm_menu_icon() {
	echo '<style>#adminmenu div.wp-menu-image.dashicons-admin-generic:before { content: "\f313"; }</style>';
}

add_action( 'admin_head', 'cpm_menu_icon' );

function cpm_module_ranks_getRank( $uid ) {
	return cpm_module_ranks_pointsToRank( cpm_getPoints( $uid ) );
}

function cpm_module_ranks_pointsToRank( $points ) {
	$ranks = get_option( 'threshold' );
	if ( is_array( $ranks ) ) {
		foreach ( $ranks as $rank ) {
			if ( $rank['level'] == '' || $rank['level'] < 0 || $rank['name'] = '' ) {
				unset( $ranks[$rank['id']] );
			}
		}

		ksort( $ranks );
		$ranks = array_reverse( $ranks, 1 );
		foreach ( $ranks as $rank ) {
			if ( $points >= $rank['level'] ) {
				return $rank['name'];
			}
		}
	}
}

function cpm_module_ranks_widget() {
	if ( is_user_logged_in() ) {
		?>
		<li><?php _e( 'Rank', 'cp' ); ?>: <?php echo cpm_module_ranks_getRank( cpm_currentUser() ); ?></li>
	<?php
	}
}

add_action( 'cpm_pointsWidget', 'cpm_module_ranks_widget' );

function cpm_social() {
	$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	?>

	<div id="fb-root"></div>
	<script></script>
	<fb:like href="<?php echo $url; ?>" layout="button_count" action="like" show_faces="false" share="false"></fb:like>
	<div id="twitter"></div>
	<div id="google"></div>

	<script>
		jQuery(document).ready(function ($) {
			window.fbAsyncInit = function () {
				FB.init({
					appId: '569695146484890',
					version: 'v2.0',
					status: false,
					cookie: false,
					xfbml: true
				});

				FB.Event.subscribe('edge.create', function (response) {
					if (response) {
						var ajaxurl = '<?php echo home_url() ?>/wp-admin/admin-ajax.php';
						$.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {
								"action": "social_add_points",
								"uid": "<?php echo cpm_currentUser() ?>",
								"points": "<?php echo get_option('pm_facebook_points'); ?>",
								"type": "facebook",
								"negative": "false"
							}
						});
					} else {
						console.log('error');
					}
				});
				FB.Event.subscribe('edge.remove', function (response) {
					if (response) {
						var ajaxurl = '<?php echo home_url() ?>/wp-admin/admin-ajax.php';
						$.ajax({
							type: 'POST',
							url: ajaxurl,
							data: {
								"action": "social_add_points",
								"uid": "<?php echo cpm_currentUser() ?>",
								"points": "<?php echo get_option('pm_facebook_points'); ?>",
								"type": "facebook",
								"negative": "true"
							}
						});
					} else {
						console.log('error');
					}
				});
			};

			// Load the SDK
			(function (d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s);
				js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));

			$('#twitter').twitterbutton({
				layout: 'horizontal',
				ontweet: function () {
					var ajaxurl = '<?php echo home_url() ?>/wp-admin/admin-ajax.php';
					jQuery.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							"action": "social_add_points",
							"uid": "<?php echo cpm_currentUser() ?>",
							"points": "<?php echo get_option('pm_twitter_points'); ?>",
							"type": "twitter",
							"negative": "false"
						}
					});
				}
			});
		});

		(function (d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js&appId=569695146484890&version=v2.0";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));

	</script>
<?php
}

add_action( 'wp_ajax_nopriv_social_add_points', 'social_add_points' );
add_action( 'wp_ajax_social_add_points', 'social_add_points' );
function social_add_points() {
	$uid = $_POST['uid'];
	$points = $_POST['points'];
	$type = $_POST['type'];
	if ( $_POST['negative'] == 'true' ) {
		$points = ( -1 ) * $points;
	}
	cpm_points( $type, $uid, $points, '' );
	die( 1 );
}

add_action( 'init', 'cpm_award_visit_site' );
function cpm_award_visit_site() {

	$current_user = wp_get_current_user();
	if ( $current_user->ID != 0 ) {

		$points = get_option( 'pm_login_points' );
		$last_visit_date = get_user_meta( $current_user->ID, 'last_visit_date', true );
		$now = current_time( 'Y-m-d' );

		if ( $last_visit_date != $now ) {
			cpm_points( 'login', $current_user->ID, $points, '' );
			update_user_meta( $current_user->ID, 'last_visit_date', $now );
		}
	}

}

function cpm_explode( $string ) {
	$arr = array_map( 'trim', explode( 'to', $string ) );
	if ( count( $arr ) < 2 ) {
		$arr = array_map( 'trim', explode( '-', $string ) );
	}
	if ( count( $arr ) == 2 ) {
		$data['from'] = $arr[0];
		$data['to'] = $arr[1];

		return $data;
	} else {
		return false;
	}
}

function lcg_randf( $min, $max ) {
	return $min + lcg_value() * abs( $max - $min );
}

add_filter( 'cron_schedules', 'cron_add_more_schedules' );
function cron_add_more_schedules( $schedules ) {
	$schedules['weekly'] = array(
		'interval' => 7 * 24 * 60 * 60,
		'display'  => __( 'Once Weekly' )
	);
	$schedules['monthly'] = array(
		'interval' => 30 * 24 * 60 * 60,
		'display'  => __( 'Once Monthly' )
	);

	return $schedules;
}

function sum_points() {
	$users = get_users();
	$sum_points = 0;
	foreach ( $users as $user ) {
		$sum_points += cpm_getPoints( $user->id );
	}

	return $sum_points;
}

function to_slug( $string ) {
	$new = preg_replace( '/\s*/', '', $string );
	$new = strtolower( $new );

	return $new;
}

if ( !function_exists( 'json_encode' ) ) {
	function json_encode( $a = false ) {
		// Some basic debugging to ensure we have something returned
		if ( is_null( $a ) )
			return 'null';
		if ( $a === false )
			return 'false';
		if ( $a === true )
			return 'true';
		if ( is_scalar( $a ) ) {
			if ( is_float( $a ) ) {
				// Always use '.' for floats.
				return floatval( str_replace( ',', '.', strval( $a ) ) );
			}
			if ( is_string( $a ) ) {
				static $jsonReplaces = array( array( '\\', '/', "\n", "\t", "\r", "\b", "\f", '"' ), array( '\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"' ) );

				return '"' . str_replace( $jsonReplaces[0], $jsonReplaces[1], $a ) . '"';
			} else
				return $a;
		}
		$isList = true;
		for ( $i = 0, reset( $a ); true; $i++ ) {
			if ( key( $a ) !== $i ) {
				$isList = false;
				break;
			}
		}
		$result = array();
		if ( $isList ) {
			foreach ( $a as $v )
				$result[] = json_encode( $v );

			return '[' . join( ',', $result ) . ']';
		} else {
			foreach ( $a as $k => $v )
				$result[] = json_encode( $k ) . ':' . json_encode( $v );

			return '{' . join( ',', $result ) . '}';
		}
	}
}

if ( !function_exists( 'json_decode' ) ) {
	function json_decode( $json ) {
		$comment = false;
		$out = '$x=';
		for ( $i = 0; $i < strlen( $json ); $i++ ) {
			if ( !$comment ) {
				if ( ( $json[$i] == '{' ) || ( $json[$i] == '[' ) )
					$out .= ' array(';
				else if ( ( $json[$i] == '}' ) || ( $json[$i] == ']' ) )
					$out .= ')';
				else if ( $json[$i] == ':' )
					$out .= '=>';
				else
					$out .= $json[$i];
			} else
				$out .= $json[$i];
			if ( $json[$i] == '"' && $json[( $i - 1 )] != "\\" )
				$comment = !$comment;
		}
		eval( $out . ';' );

		return $x;
	}
}

add_filter( 'the_content', 'cpm_social_icons', 20 );
function cpm_social_icons( $content ) {
	if ( is_single() ) {
		$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$content .= '<div class="post-share">
						<div id="twitter"></div>
						<div id="fb-root"></div>
						<fb:like href="' . $url . '" layout="button_count" action="like" show_faces="false" share="false"></fb:like>
					</div>';
	}
	return $content;
}

/*
 * Clear all crons
 * @param array $hooks
 */

function clear_all_crons( $hooks ) {
	$crons = _get_cron_array();
	if ( empty( $crons ) ) {
		return;
	}
	foreach ( $hooks as $hook ) {
		foreach ( $crons as $timestamp => $cron ) {
			if ( empty( $cron ) ) {
				unset( $crons[$timestamp] );
			}
			if ( !empty( $cron[$hook] ) ) {
				unset( $crons[$timestamp] );
			}
		}
	}
	_set_cron_array( $crons );
}

// Export to CSV
function cpm_csv_export() {
	global $wpdb;
	$table_name = PM_DB_PAYOUT;
	$qry = "SELECT uid, nickname, coin, wallet, amount, date_time FROM $table_name ORDER BY date_time DESC";
	$result = $wpdb->get_results( $qry, ARRAY_A );

	if ( $wpdb->num_rows > 0 ) {
		$date = new DateTime();
		$ts = $date->format( "Y-m-d-G-i-s" );

		$filename = "report-$ts.csv";
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename=' . $filename );

		$fp = fopen( 'php://output', 'w' );
		$hrow = $result[0];
		fputcsv( $fp, array_keys( $hrow ) );
		foreach ( $result as $data ) {
			fputcsv( $fp, $data );
		}
		fclose( $fp );
	}
}
