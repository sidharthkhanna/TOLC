<?php

	ob_start();

	/*
	Plugin Name: WordPress Chat by Chatwee
	Description: WordPress Chat by Chatwee is fully customizable social chat & comment platform for websites and blogs. With Chatwee you can engage your online community and provide real-time communication.
	Author: pawelq
	Version: 1.9.1
	Author URI: https://www.chatwee.com
	*/

	function chatwee_plugin_js() {
	
		wp_enqueue_script('jquery');
		$chatweePluginUrl = WP_CONTENT_URL.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		echo '<script type="text/javascript" src="'.$chatweePluginUrl.'chatwee_plugin.js"></script>';	
	}
	
	register_activation_hook( __FILE__, 'set_up_options' );

	function set_up_options(){
		
		 global $wpdb;

		$table_name = $wpdb->prefix . 'chatwee_moderators';

		$sql = "CREATE TABLE $table_name (
		  id int(11) NOT NULL AUTO_INCREMENT,
		  user_id int(11) DEFAULT NULL,
		  UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
			
		register_setting( 'chatwee-settings-group', 'is_home' ); 
		register_setting( 'chatwee-settings-group', 'is_search' );
		register_setting( 'chatwee-settings-group', 'is_archive' );
		register_setting( 'chatwee-settings-group', 'is_page' );
		register_setting( 'chatwee-settings-group', 'is_single' );
		register_setting( 'chatwee-settings-group', 'ssostatus' );
		register_setting( 'chatwee-settings-group', 'clientid' );
		register_setting( 'chatwee-settings-group', 'keyapi' );
		register_setting( 'chatwee-settings-group', 'loginallsubdomains' );
		register_setting( 'chatwee-settings-group', 'group_moderators_editor' );
		register_setting( 'chatwee-settings-group', 'group_moderators_author' );
		register_setting( 'chatwee-settings-group', 'group_moderators_contributor' );
		register_setting( 'chatwee-settings-group', 'group_moderators_subscriber' );
		register_setting( 'chatwee-settings-group', 'display_format' );
		
		update_option('chatwee-settings-group[is_home]',"on");
		update_option('chatwee-settings-group[display_format]',0);
		update_option('chatwee-settings-group[is_search]',"on");
		update_option('chatwee-settings-group[is_archive]',"on");
		update_option('chatwee-settings-group[is_single]',"on");
		update_option('chatwee-settings-group[is_page]',"on");
		update_option('chatwee-settings-group[ssostatus]',"on");
		update_option('chatwee-settings-group[loginallsubdomains]',"on");
		update_option('chatwee',"");
		
		}

		add_action('admin_menu', 'chatwee_create_menu');   
	
	if (check_sso_enable())
		add_action('wp_login', 'remote_login',7,2);
	if (check_sso_enable())
		add_action('wp_logout', 'remote_logout',7,2);
	

	function chatwee_create_menu() 
	{
		add_menu_page('Chatwee', 'Chatwee', 'administrator', 'chatwee_panel', 'chatwee_panel',  plugins_url( '/ico_fb_small_16x16.png', __FILE__ ));
		add_submenu_page( 'chatwee_panel', 'General Settings','General Settings', 'administrator','chatwee_panel', 'chatwee_panel');
		add_submenu_page( 'chatwee_panel', 'Chatwee Moderators Management','Moderation', 'administrator','chatwee_moderators', 'chatweeModeratorsPage');
	}
	
	function chatweeModeratorsPage() 
	{
		include( "chatwee-moderators.php" );	
	}
	
	function check_is_sso_error()
	{
		return get_option('chatwee-settings-group[ssoiserror]');
		
	}
	
	function belong_to_moderator_group($id) {
		
		$user = new WP_User( $id );

		if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
			foreach ( $user->roles as $role )
				if(get_option('chatwee-settings-group[group_moderators_'.$role.']') == 'on')
					return true; 
		}
	}
	
	function check_sso_enable()
	{
		$option = get_option('chatwee-settings-group[ssostatus]');
		$status = ($option == 'on') ? 1 : 0;
		
		if(!$status)
			update_option('chatwee-settings-group[ssoiserror]','');
		
		return $status;
	}
	
	function check_user_agent($type = NULL)
	{	
        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
        if ( $type == 'bot' ) {
                if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
                        return true;
                }
        } else if ( $type == 'browser' ) {
                if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
                        return true;
                }
        } else if ( $type == 'mobile' ) {
            
                if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
                        return true;
                } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
                        return true;
                }
        }
        return false;
	}
	
	function get_the_user_ip()
	{
	
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}
	
	function get_avatar_url_for_chatwee($user_id)
	{

		$img = get_avatar($user_id);
		preg_match("/src=('|\")(.*?)('|\")/i", $img, $matches);
		$url = $matches[2];

		return $url;
	}
	
	function get_response($url) 
	{
		$hand = curl_init();
		 
		curl_setopt($hand, CURLOPT_URL, $url);
		
		curl_setopt($hand, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($hand);
		 
		curl_close($hand);
		
		return $response;
	}

	function remote_logout() 
	{

		$chatId = get_option('chatwee-settings-group[clientid]');
		
		$clientKey = get_option('chatwee-settings-group[keyapi]');
		
		$sessionId = $_COOKIE['chch-SI'];
		
		$url = "http://chatwee-api.com/api/remotelogout?chatId=".$chatId."&clientKey=".$clientKey."&sessionId=".$sessionId;
		
		get_response($url);

		$hostChunks = explode(".", $_SERVER["HTTP_HOST"]);

		$hostChunks = array_slice($hostChunks, -2);

		$domain = "." . implode(".", $hostChunks);

		setcookie("chch-SI", "", time() - 1, "/", $domain);
	}

	function remote_login($user_login, $user)
	{		
		$user_login = get_chatwee_user_login($user_login);
		
		$chatId = get_option('chatwee-settings-group[clientid]');
		
		$clientKey = get_option('chatwee-settings-group[keyapi]');
		
		$isAdmin = 0;

		if (is_super_admin( $user->ID) || checkIfUserIsAdmin($user->ID) || belong_to_moderator_group($user->ID) )
			$isAdmin = 1;

		$ismobile = (check_user_agent('mobile')==true) ? 1 : 0;
		
		$ip = get_the_user_ip();
		
		$avatar = get_avatar_url_for_chatwee($user->ID);

		if(isSet($_COOKIE["chch-SI"]))
		{
			remote_logout(); 
		}
		
		if(isSet($_SESSION['chatwee'][$user_login]))
		{
			$previousSessionId = $_SESSION['chatwee'][$user_login];
		}
		
		else if(isSet($_COOKIE["chch-PSI"]))
		{
			$previousSessionId = $_COOKIE["chch-PSI"];
		}		
		else 
		{
			$previousSessionId = null;
		}

		$url = "http://chatwee-api.com/api/remotelogin?chatId=".$chatId."&clientKey=".$clientKey."&login=".$user_login."&isAdmin=".$isAdmin."&ipAddress=".$ip."&avatar=".$avatar."&isMobile=".$ismobile."&previousSessionId=".$previousSessionId;
	
		$url = str_replace(' ', '%20', $url);	
	
		$response = get_response($url);
			
		$sessionArray = json_decode($response);
		
		if($sessionArray->errorCode) 
		{
			update_option('chatwee-settings-group[ssoiserror]',$sessionArray->errorMessage);
		}
		else 
			update_option('chatwee-settings-group[ssoiserror]','');
		
		$sessionId = $sessionArray->sessionId;
	
		$fullDomain = $_SERVER["HTTP_HOST"];
		
		$isNumericDomain = preg_match('/\d|"."/',$fullDomain);
		
		if($isNumericDomain || !get_option('chatwee-settings-group[loginallsubdomains]'))
		{
			$CookieDomain = $fullDomain;
		}
		else 
		{
			$hostChunks = explode(".", $fullDomain);

			$hostChunks = array_slice($hostChunks, -2);	

			$CookieDomain = "." . implode(".", $hostChunks);
		}
		
		setcookie("chch-SI", $sessionId, time() + 2592000, "/", $CookieDomain);
		
		$_SESSION['chatwee'][$user_login] = $_SESSION['chatwee'][$user_login] == '' ? $sessionId : $_SESSION['chatwee'][$user_login];
	}

	function showMessage($message, $errormsg = false)
	{
		if ($errormsg) {
			echo '<div id="message" class="error">';
		}
		else {
			echo '<div id="message" class="updated fade">';
		}

		echo "<p>Chatwee Plugin SSO error:</p><p><strong>$message</strong>. After fixing this problem, please re-login.</p></div>";
	}    
	
	function showAdminMessages()
	{
		if(check_is_sso_error())
			showMessage(get_option('chatwee-settings-group[ssoiserror]'), true);
	}

	function chatwee_panel() 
	{ 
		include ('chatwee.php');
	}
		
	function chatwee_embedchat()
	{
		echo get_option('chatwee');	
	}
	function check_chat() 
	{
		
		$current_user = wp_get_current_user();	

		$username = get_chatwee_user_login($current_user->data->user_login);
				
		remote_login($username,$current_user);	
		
	}
	
	function chatwee_only_for_logging()
	{
		$option = get_option('chatwee-settings-group[is_for_users]');		
		if($option && is_user_logged_in() == true) 
			return true; //showing
		else if(!$option)
			return true;
		else 
			return false; // not showing
	}
	
	function get_chatwee_user_login($user_login) 
	{
		$option = intval(get_option('chatwee-settings-group[display_format]'));
	
		$current_user = get_user_by("login",$user_login);

		switch($option) {
			
			case 0:
			{
				$user_login = $current_user->data->display_name; 
				break;
			}
			case 1:
			{
			
				$user_login = $current_user->data->user_login;
			
				break;
			}  
			case 2:
			{
				$user_login = $current_user->user_firstname;
				break;
			}
			case 3:
			{
				$user_login = $current_user->user_lastname;
				break;
			}
			case 4:
			{
				$user_login = $current_user->user_firstname." ".$current_user->user_lastname;
				break;
			}
			case 5:
			{
				$user_login = $current_user->user_lastname." ".$current_user->user_firstname;
				break;
			}
			case 6:
			{
				$user_login = $current_user->user_firstname[0]." ".$current_user->user_lastname;
				break;
			}
			case 7:
			{
				$user_login = $current_user->user_firstname." ".$current_user->user_lastname[0];
				break;
			}
			case 8:
			{
				$user_login = $current_user->user_firstname[0]." ".$current_user->user_lastname[0];
				break;
			}
		}

		$user_login = str_replace(' ', '%20', $user_login);	

		return $user_login;
	}
	

	function chatwee_format_name() {
	
		$option_selected = get_option('chatwee-settings-group[display_format]');
							
		$options = array(0 => 'Display Name in WP',
						1 => 'Login in WP',
						2 => 'First Name',
						3 => 'Last Name',
						4 => 'First Name Last Name',
						5 => 'LastName, FirstName',
						6 => 'FirstInitial LastName',
						7 => 'FirstName LastInitial',
						8 => 'First and Last Initials');
	
		$out = ''; 
						
		foreach ($options as $option_value => $option_name) {
			if (intval($option_value) == intval($option_selected)) 
				$selected_text = 'selected="selected" ';
			else 
				$selected_text = '';
		
			$out.= '<option '.$selected_text.'value="'.$option_value.'">'.$option_name.'</option>'."\n";
		}

		return $out;
	}	
	
	function check_site()
	{
		if(!chatwee_only_for_logging())
			return;	
	
		if(!is_user_logged_in() && isSet($_COOKIE['chch-SI']) && check_sso_enable())
			remote_logout();
		
		if(get_option( 'chatwee-settings-group[is_home]') == "on" && is_home()) {
			add_action( 'wp_footer', 'chatwee_embedchat' );
		}
		 if(get_option( 'chatwee-settings-group[is_page]') == "on" && is_page()) {
			add_action( 'wp_footer', 'chatwee_embedchat' );
		}
		 if(get_option( 'chatwee-settings-group[is_search]') == "on" && is_search()) {
			add_action( 'wp_footer', 'chatwee_embedchat' );
		}
		 if(get_option( 'chatwee-settings-group[is_single]') == "on" && is_single()) {
			add_action( 'wp_footer', 'chatwee_embedchat' );
		}
		 if(get_option( 'chatwee-settings-group[is_archive]') == "on" && is_archive()) 
		 {
			add_action( 'wp_footer', 'chatwee_embedchat' );
		 }
		if(is_user_logged_in() && !isSet($_COOKIE['chch-SI']) && check_sso_enable())
		{
			add_action( 'wp_footer', 'check_chat' );
		}
	}
	
	function searchUser() {
		
		$search = '*'.$_POST['search_name'].'*'	;
	
		$user_query = new WP_User_Query( array(
		'search' => $search,
		'search_columns' => array(
			'user_login',
			'user_nicename',
			'user_email',
			'user_url',
			),
		));
		$users_found = $user_query->get_results();

		foreach ($users_found as $user) {
			$row.= "<tr><td>";
			$row.= $user->data->ID."</td><td>";
			$row.= $user->data->user_login."</td><td>";
			$row.= $user->data->display_name."</td> <td>";
			$row.= $user->data->user_email."</td> <td>";
			$row.= implode(', ', $user->roles) ."</td><td>";
			$row.= "<a id='user_".$user->data->ID."' onclick='setAsAdmin(this.id)'>Set as Chatwee Admin</a></td></tr>";
		}
		echo $row;
		exit;
	}
	
	function addUser() {
	
		$key = explode('_',$_POST['id']);
		$user_id = $key[1];
		global $wpdb;
		$table_name = $wpdb->prefix . 'chatwee_moderators';

		$row = $wpdb->get_row( $wpdb->prepare('SELECT * FROM '.$table_name.' WHERE user_id = %d', $user_id) );
		
		if($row)
			return;
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'user_id' => $user_id
			), 
			array( 
				'%d'
			) 
		);
	
		exit;
	}
	
	function get_role_names() {

		global $wp_roles;

		if ( ! isset( $wp_roles ) )
			$wp_roles = new WP_Roles();

		return $wp_roles->get_names();
	}
	
	function removeUser() {
		
		global $wpdb;

		$key = explode('_',$_POST['id']);
		$user_id = $key[1];
		$table_name = $wpdb->prefix . 'chatwee_moderators';
		
		$wpdb->delete( $table_name, array( 'user_id' => $user_id ) );
		
		exit;
	}
	
	function checkIfUserIsAdmin($id)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'chatwee_moderators';

		$row = $wpdb->get_row( $wpdb->prepare('SELECT * FROM '.$table_name.' WHERE user_id = %d', $id) );

		if($row->user_id)
			return true;
	}

	function getAllModerators() {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'chatwee_moderators';

		$all = $wpdb->get_results('SELECT * FROM '.$table_name);

		return $all;	
	}

	add_action("wp_footer","check_site", 5);
	add_action('admin_notices', 'showAdminMessages');  
	add_action('admin_head', 'chatwee_plugin_js');
	add_action('wp_ajax_chatweePlugin', 'searchUser');
	add_action('wp_ajax_chatweePluginAddUser', 'addUser');
	add_action('wp_ajax_chatweePluginRemoveUser', 'removeUser');
?>