<?php
/*
Plugin Name: Chats
Plugin URI: http://www.wp-chat.com
Description: Web Page Chats for Websites
Version: 1.0.6.7
Author: wp-chat
Author URI: http://www.wp-chat.com
Text Domain: chats
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! class_exists( 'Chats' ) ) {
	
    class Chats
    {
        public static $plugin_name      = 'chats';
        public static $plugin_version   = '1.0.6.7';
        public static $table_prefix     = 'chats_';
        public static $tag_prefix       = 'chats';
        public static $optionParameters = 'chats_options';
        public static $setting_page_url = '/wp-admin/admin.php?page=chats_settings_page';
        public static $defaultOptions   = array(
            'panel_background'          => '#2785C1',
            'panel_border_color'        => '#2785C1',
            'body_background'           => '#FFFFFF',
            'btn_finish_background'     => '#FCFCFB',
            'btn_finish_color'          => '#333333',
            'btn_finish_border_color'   => '#C4C4C3',
            'btn_expand_background'     => '#46A0DA',
            'admin_signature_color'     => '#627AAD',
            'admin_text_color'          => '#333333',
            'user_signature_color'      => '#000000',
            'user_text_color'           => '#333333',
            'time_color'                => '#909090',
            'message_border_color'      => '#F2F2F2',
            'write_panel_background'    => '#F0F0EF',
            'write_area_background'     => '#FFFFFF',
            'write_area_color'          => '#333333',

            'width'                     => 300,
            'position'                  => 'right',
            'status'                    => 1,

            'admin_signature'           => 'Admin',
            'user_signature'            => 'You',
            'hello_message'             => 'Hello. Do you have any questions?',
            'panel_title'               => 'Chat with us',
            'enter_text_placeholder'    => 'Enter your message...',
            'btn_finish_text'           => 'Finish',
            'thank_message'             => 'Thank you for using Live Chat. To help us serve you better, please take a moment to complete a short survey. It will display as you close the chat by clicking the chat bubble at the top right of the screen. Thanks for chatting. Please click the "Close" icon, and then tell us how we did.',
            'offline_message'           => 'Sorry, but we are offline now. Please, leave your contact email and your message. We will communicate with you as soon as possible.',
            'email_label'               => 'Email',
            'name_label'                => 'Name',
            'message_label'             => 'Message',
            'send_email'                => 'Send email',
            'offline_thank_message'     => 'Your message was sent. Thank you.',

            'email_for_offline_msg'     => '',
            'email_for_first_msg'       => '',

            'show_avatars'  => 0,
            'admin_avatar'  => '/wp-content/plugins/chats/assets/admin_avatar.png',
            'user_avatar'   => '/wp-content/plugins/chats/assets/user_avatar.png'
        );
        public static $cookiePrefix     = 'chats_hash';
        public static $translation = array(
            'en'    => array(
                'page_settings_title'               => 'Chats Plugin',
                'settings_tab_color'                => 'Chat Interface',
                'settings_width'                    => 'Width',
                'settings_panel_background'         => 'Title panel Background',
                'settings_panel_border_color'       => 'Chat border color',
                'settings_body_background'          => 'Message panel background',
                'settings_btn_finish_background'    => 'Finish button background',
                'settings_btn_finish_color'         => 'Finish button color',
                'settings_btn_finish_border_color'  => 'Finish button border color',
                'settings_btn_expand_background'    => 'Expand button background',
                'settings_admin_signature_color'    => 'Admin signature color',
                'settings_admin_text_color'         => 'Admin text color',
                'settings_user_signature_color'     => 'User signature color',
                'settings_user_text_color'          => 'User text color',
                'settings_time_color'               => 'Message time color',
                'settings_message_border_color'     => 'Message border color',
                'settings_write_panel_background'   => 'Write panel background',
                'settings_write_area_background'    => 'Write area background',
                'settings_write_area_color'         => 'Write area color',
                'settings_show_avatars'             => 'Show avatars?',
                'settings_admin_avatar'             => 'Admin avatar',
                'settings_user_avatar'              => 'User avatar',

                'settings_tab_template'             => 'Advanced settings',
                'settings_position'                 => 'Position',
                'settings_position_left'            => 'Left',
                'settings_position_right'           => 'Right',
                'settings_status'                   => 'Chat status',
                'settings_status_1'                 => 'Chat online',
                'settings_status_2'                 => 'Chat hidden',
                'settings_status_3'                 => 'Chat offline',

                'settings_tab_text'                 => 'Chat texts',
                'settings_admin_signature'          => 'Admin Signature',
                'settings_user_signature'           => 'User Signature',
                'settings_hello_message'            => '"Hello" message',
                'settings_panel_title'              => 'Panel title',
                'settings_enter_text_placeholder'   => 'Enter text placeholder',
                'settings_btn_finish_text'          => 'Finish button label',
                'settings_thank_message'            => '"Thank" message',
                'settings_offline_message'          => '"Offline chat" message',
                'settings_email_label'              => 'Email',
                'settings_name_label'               => 'Name',
                'settings_message_label'            => 'Message label',
                'settings_send_email'               => 'Send email button',
                'settings_offline_thank_message'    => '"Offline thank" message',

                'settings_tab_auth'                 => 'Chat Activation',
                'settings_tab_auth2'                => 'Chat Registration and Activation',
                'settings_personal_key'             => 'Personal key',
                'settings_personal_key_desc'        => 'Be careful, this key should be the same as in admin panel of site',
                'settings_auth_email'               => 'Login (E-mail)',
                'settings_auth_password1'           => 'Password',
                'settings_auth_password2'           => 'Confirm password',
                'settings_auth_register_btn'        => 'Register & Activate',
                'settings_auth_login_btn'           => 'Login',
                'settings_existing_user'            => 'Existing user',
                'settings_new_user'                 => 'New user',

                //server answer
                'answer_server_not_answer'          => 'Could not connect to server',
                'answer_incorrect_url'              => 'Incorrect url of domain',
                'answer_incorrect_data'             => 'Form was filed incorrectly',
                'answer_incorrect_login_or_password'=> 'Incorrect username or password',
                'answer_user_not_found'             => 'Username not found',
                'answer_this_username_busy'         => 'Username is busy or incorrect',
                'answer_login_ok'                   => 'Login successful',

                //notification
                'settings_tab_notification'         => 'Sending of online & offline Notifications',
                'settings_email_for_offline_msg'    => 'Send "offline" messages to this email',
                'settings_email_for_first_msg'      => 'If new "online" chat started, send notifications to this email',
                'test_mail'                         => 'Test',
                'mail_is_empty'                     => 'Please, type some email address. Without email address this functionality is not in use.',
                'test_mail_was_successfully'        => 'Email was sent successfully! Please, check your mailbox.',
                'test_mail_was_not_sent'            => '<strong>Email was not sent!</strong><br /> Possible reasons:<br />- email address was entered incorrectly or <br />- mail() function on your server/hosting was\'t found or not configured.<br /> <strong>Check your configuration.</strong>',

                //text constant

                'subject_offline_message'           => 'Chats - new offline message from',
                'subject_email_for_first_msg'       => 'Chats - new chat started on',
                'user_ip'                           => 'User IP',
                'user_message'                      => 'User Message',
                'user_message_page'                 => 'Referer URL',
                // notice 5 stars
                'close'                             => 'hide this message',
                // smtp 
                'settings_smtp'                     => 'Email Settings / SMTP Settings',
                'send_mail_with'                    => 'Send mail notifications using one of three methods:',
                'with_wpchatcom_service'            => 'wp-chat.com service',
                'with_server_service'               => 'this website',
                'with_own_smtp_service'             => 'my own smtp server',
                'test_smtp'                         => 'Test SMTP',
                'settings_smtp_host'                => 'SMTP Server',
                'settings_smtp_user'                => 'SMTP User',
                'settings_smtp_pass'                => 'SMTP Password',
                'settings_smtp_port'                => 'SMTP Port',
                'test_smtp'                         => 'Test SMTP',
                'smtp_test_was_successfully'        => 'Test SMTP was Successful',
                'notification_email_empty'          => '"Email notifications" are empty, please fill these fields',
                'desc_mail_notification'            => 'To deactivate sending of notification messages - just delete email address from corresponding field',
                'website_smtp_desc'                 => 'Email messages will be sent through this website, using functionality of your installed WordPress',
                'wpchat_smtp_desc'                  => 'Email messages will be sent through wp-chat.com online service. Can be useful, if mail server on this hosting/server is not available',
                'info_smtp_desc'                    => 'All email messages will be sent via the specified mailbox. You can get SMTP setup data in your hosting panel or just ask your webmaster',
                'method_1'                          => 'Method 1',
                'method_2'                          => 'Method 2',
                'method_3'                          => 'Method 3',
                
                
                //page/post setting
                'title_page_post_setting'           => 'Chat window on Posts and Pages',
                'pps_access_view'                   => 'Visibility of chat window on',
                'pps_none'                          => '- select -',
                'pps_category'                      => 'Category',
                'pps_post_page'                     => 'Post/Page',
                'pps_main'                          => 'Main page',
                'pps_category_name'                 => 'Category name',
                'pps_category_posts'                => 'Posts in category',
                'pps_deny'                          => 'Deny',
                'pps_allow'                         => 'Allow',
                'pps_page_post_name'                => 'Post/Page title',
                'pps_type'                          => 'Type',
                'pps_show_main'                     => 'Main page',
                'pps_yes'                           => 'Yes',
                'pps_no'                            => 'No',
                
                // button
                'save_visibility_button'            => 'Save visibility settings',
            )
        );
        public static $week_time = 259200; // 1 - 604800 week time  259200 - 3 days
        
        public static $error = '';
        
        public static function includeCss()
        {
            if (is_admin_bar_showing()) {
                wp_enqueue_style( 'css-admin-chats' , plugins_url( self::$plugin_name . "/assets/admin.css" ) );
            }
        }
        
        public static function admin_bar($wp_admin_bar)
        {
            $wp_admin_bar->add_node( array(
                    'id' => 'chats-settings',
                    'title' => 'Chats',
                    'href' => esc_url( admin_url("admin.php?page=chats_settings_page") ),
                    'meta' => array('class' => 'admin-bar-icon-chats')
                    ));
        }
        
        public static function notice5()
        {
            $stars = 'stars5';
            $notice = get_option(self::$table_prefix . 'notice');
            if (isset($notice[$stars]['time']) && ( $notice[$stars]['time'] + self::$week_time ) <= time()) {
                if ($notice === false || !isset($notice[$stars]['hide']) || (isset($notice[$stars]['hide']) && $notice[$stars]['hide'] === false)) {
                    ?>
                    <div class="clear"></div>
                    <div class="updated notice" style="position: relative;">
                        <p >
                            <div style="font-size:16px; margin-bottom: 15px; font-weight: 800;"><?php echo self::trans('Please, Support us!'); ?></div>
                            <div style="font-size:14px; line-height: 26px;">                     
                                <?php echo self::trans('Help us to make the Chats plugin better for you!'); ?> 
                                <br /> 
                                <?php echo self::trans('Follow this link:'); ?> 
                                <a href="https://wordpress.org/support/view/plugin-reviews/chats?filter=5" target="_blank">https://wordpress.org/support/view/plugin-reviews/chats?filter=5</a> 
                                <br />
                                <?php echo self::trans('to leave your'); ?>
                                <a href="https://wordpress.org/support/view/plugin-reviews/chats?filter=5" target="_blank"><?php echo self::trans('5 stars review') ?></a> 
                                <?php echo self::trans('for chat plugin, that you use.'); ?>
                            </div>
                            <div style="font-size:14px; margin-top: 15px;">                     
                                <?php echo self::trans('Thanks for supporting us'); ?>
                            </div>
                            <a title="<?php echo self::trans('close');?>" href="<?php echo admin_url( 'admin-post.php?action=chats_hide_notice&type=stars5' ); ?>" style="right: 0; font-size: 12px; line-height: 22px; top:0; margin-right: 5px; margin-top:5px; position: absolute;">[<?php echo self::trans('close');?>]</a>
                        </p>
                    </div>
                    <?php
                }
            } else {
                add_option(self::$table_prefix . 'notice', array($stars => array('time' => time() ) ) );
            }
        }
        
         public static function hide_notice()
         {
             if (isset($_GET['type']) && $_GET['type'] == 'stars5') {
                 $notice = get_option(self::$table_prefix . 'notice');
                 $notice[$_GET['type']]['hide'] = true;
                 update_option(self::$table_prefix . 'notice', $notice);
             }
             header('location:' . $_SERVER['HTTP_REFERER']);
             exit;
         }

        /**
         * Listen incoming request from js
         */
        public static function js_process(){
            $personalKey = (string)get_option(ChatsAction::$optionKey, '');
            if(empty($personalKey)){
                return true;
            }

            global $current_user;
            get_currentuserinfo();

            $mode           = @$_POST['mode'];
            $hash           = @$_COOKIE[self::$cookiePrefix];
            $ip             = self::getUserIP();
            $browser        = @$_SERVER['HTTP_USER_AGENT'];
            $currentUserID  = @(int)$current_user->ID;

            $message        = @strip_tags(trim($_POST['message']));
            $message_page   = @strip_tags(trim($_POST['message_page']));
            $queryMode      = @$_POST['queryMode'];
            $countMessage   = @$_POST['countMessage'];
            //offline data
            $text   = @strip_tags(trim($_POST['text']));
            $name   = @strip_tags(trim($_POST['name']));
            $email  = @strip_tags(trim($_POST['email']));
            //time of request
            $offset             = @timezone_offset_get( new \DateTimeZone( date_default_timezone_get() ), new \DateTime() );
            $user_timezone      = @floatval($_POST['user_timezone']);
            $server_timezone    = @floatval( sprintf( "%s%02d", ( $offset >= 0 ) ? '+' : '-', abs( $offset / 3600 ) ) );

            if( empty($mode) or empty($hash) or !in_array($mode, array('add', 'read', 'finish', 'send_email')) ){
                die();
            }

            $created    = date('Y-m-d H:i:s');

            //save message from user
            if( $mode == 'add' and !empty($message) ){
                $addRes = self::add_messages($hash, $ip, $browser, $message, $created, 0, $message_page, $currentUserID);
                if($addRes){
                    self::send_messages($hash, $ip, $browser, $message, $created, $message_page, $currentUserID, $user_timezone, $server_timezone);
                }

                print json_encode(array('result' => $addRes));exit;
            }

            //read message of user
            if( $mode == 'read' and !empty($hash) and !empty($ip) and !empty($browser) ){
                $messages = self::read_messages($hash, $ip, $browser, $currentUserID, array('queryMode' => $queryMode, 'countMessage' => $countMessage));

                print json_encode(array('messages' => $messages));exit;
            }

            //finish chat
            if( $mode == 'finish' and !empty($hash) and !empty($ip) and !empty($browser) ){
                //send log operation "finish"
                self::send_log($hash, $ip, $browser, $created, 'finish', array(), $message_page, $currentUserID, $user_timezone, $server_timezone);

                //get all messages for sending log to user

                //remove all messages
                self::remove_messages($hash, $ip, 'user');
            }

            //send message
            if( $mode == 'send_email' and !empty($hash) and !empty($ip) and !empty($browser) and !empty($text) and !empty($name) and !empty($email) ){
                $sendRes = self::send_offline_messages($hash, $ip, $browser, $text, $name, $email, $created, $message_page, $currentUserID, $user_timezone, $server_timezone);

                print json_encode(array('result' => $sendRes));exit;
            }

            exit;
        }

        /**
         * Init chat. Should be started at all pages on frontend
         */
        public static function wp_head(){
        	//delete_option( ChatsAction::$optionKey );
            $personalKey = (string)get_option(ChatsAction::$optionKey, '');
            if(empty($personalKey)){
                return true;
            }
            
            $options_access = self::plugin_options('get', array(), 'chats_access');
            if ( isset($options_access['access']) && !is_front_page() && $options_access['access'] != -1) {
                $post = get_post( null, ARRAY_A );
                if ($options_access['access'] == 1) {
                    $n = count($post['post_category']);
                    for($i = 0; $i < $n; $i++) {
                        if ($options_access['category']['deny'][$post['post_category'][$i]]) {
                            return true;
                        }
                    }
                } elseif($options_access['access'] == 2) {
                    if (isset($options_access['post_page']['deny'][$post['ID']])) {
                        return true;
                    }
                }
            }
            
            if (isset($options_access['show_main']) && (int)$options_access['show_main'] == 0) {
                if (is_front_page()) {
                    return true;
                }
            }
            
            

            //parameters of chat
            $options  = self::plugin_options( 'get' );
            if( (int)$options['status'] == 2){
                return true;
            }

            //only for frontend
            if ( !is_admin() ) {
                wp_register_script('chats_js', plugins_url(self::$plugin_name . '/assets/chats.js'), array(), self::$plugin_version, false);
                wp_register_style( 'chats_css', plugins_url( self::$plugin_name . '/assets/chats.css' ) );

                //add parameters in js
                $js_parameters  = array(
                    'site_url'               => site_url(),
                    'request_url'            => site_url() . '/wp-admin/admin-ajax.php',
                    'cookie_prefix'          => self::$cookiePrefix,
                    'tag_prefix'             => self::$tag_prefix,
                    'sound_path'             => plugins_url(self::$plugin_name . '/assets'),
                    'show_copyright_time'    => ( isset( $options['show_copyright_time'] ) ? $options['show_copyright_time'] : 0 ),
                    'show_copyright'         => ( isset( $options['show_copyright'] ) ? $options['show_copyright'] : 1 ),
                    'text'  => array(
                        'admin_signature'           => $options['admin_signature'],
                        'user_signature'            => $options['user_signature'],
                        'hello_message'             => $options['hello_message'],
                        'panel_title'               => $options['panel_title'],
                        'enter_text_placeholder'    => $options['enter_text_placeholder'],
                        'btn_finish_text'           => $options['btn_finish_text'],
                        'offline_message'           => $options['offline_message'],
                        'email_label'               => $options['email_label'],
                        'name_label'                => $options['name_label'],
                        'message_label'             => $options['message_label'],
                        'send_email'                => $options['send_email'],
                        'offline_thank_message'     => $options['offline_thank_message'],
                    ),
                    'color' => array(
                        'panel_background'          => $options['panel_background'],
                        'panel_border_color'        => $options['panel_border_color'],
                        'body_background'           => $options['body_background'],
                        'btn_finish_background'     => $options['btn_finish_background'],
                        'btn_finish_color'          => $options['btn_finish_color'],
                        'btn_finish_border_color'   => $options['btn_finish_border_color'],
                        'btn_expand_background'     => $options['btn_expand_background'],
                        'admin_signature_color'     => $options['admin_signature_color'],
                        'admin_text_color'          => $options['admin_text_color'],
                        'user_signature_color'      => $options['user_signature_color'],
                        'user_text_color'           => $options['user_text_color'],
                        'time_color'                => $options['time_color'],
                        'message_border_color'      => $options['message_border_color'],
                        'write_panel_background'    => $options['write_panel_background'],
                        'write_area_background'     => $options['write_area_background'],
                        'write_area_color'          => $options['write_area_color'],
                    ),
                    'template'  => array(
                        'width'     => $options['width'],
                        'position'  => $options['position'],
                        'status'    => $options['status'],
                        'show_avatars'  => $options['show_avatars'],
                        'admin_avatar'  => $options['admin_avatar'],
                        'user_avatar'   => $options['user_avatar']
                    )
                );
                wp_localize_script('chats_js', 'chats_parameters', $js_parameters);

                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'chats_js' );
                wp_enqueue_style( 'chats_css' );

                // Stop any output until cookies are set
                ob_start();
            } 
            
           
        }

        /**
         * Add html in footer
         */
        public static function wp_footer(){

        }

        public static function admin_init(){
            if (get_option(self::$optionParameters.'_redirect', false)) {
                delete_option(self::$optionParameters.'_redirect');
                wp_redirect(site_url().self::$setting_page_url);exit;
            }
        }

        public static function admin_menu(){
            if(is_admin()) {
                //settings menu for admin
                add_menu_page('Chat', 'Chat', 'manage_options', 'chats_settings_page', array('Chats', 'chats_settings_page'),plugins_url(self::$plugin_name . '/assets/icon16.png'));
                add_action( 'admin_init', array('Chats', 'register_settings') );
            }
        }

        /**
         * Plugin options
         */
        public static function plugin_options($mode = 'add', $options = array(), $key = ''){
            if(empty($options)){
                $options = self::$defaultOptions;
            }

            if (empty($key)) {
                if($mode == 'add'){
                    update_option(self::$optionParameters, $options);
                }
            } else {
                if($mode == 'add'){
                    update_option($key, $options);
                }
            }

            
                if( $mode == 'get' ){
                    if (empty($key)) {
                        $optionValue = get_option(self::$optionParameters, $options);
                        foreach(self::$defaultOptions as $k_op => $v_op){
                            $optionValue[$k_op] = (empty($optionValue[$k_op]) ? $v_op : $optionValue[$k_op]);
                        }
                        return $optionValue;
                    } else {
                        $optionValue = get_option($key, $options);
                        return $optionValue;
                    }
                }
            
            if( $mode == 'remove' ){
                if (empty($key)) {
                    delete_option( ChatsAction::$optionKey );//delete personal key of plugin
                    delete_option( self::$optionParameters );//delete settings options
                } else {
                    delete_option( $key );
                }
            }
        }

        /**
         * Processing message from user
         */
        public static function send_messages($hash, $ip, $browser, $message, $created, $message_page = '', $currentUserID = 0, $user_timezone = 0, $server_timezone = 0){
            //maybe we should get email of logged users?

            $personalKey    = (string)get_option(ChatsAction::$optionKey, '');
            $options        = self::plugin_options( 'get' );

            //send first message to admin
            $send_res       = false;
            $countMessages  = (int)count(self::read_messages($hash, $ip, $browser, 0, array('queryMode' => 'user_last', 'countMessage' => 2)));
            if( !empty($options['email_for_first_msg']) and $countMessages == 1 ){
                $headers = array();
                $headers['charset'] = 'Content-Type: text/html; charset=UTF-8';
                $headers['from']    = 'From: Chats <'.str_replace(array('/www.','http://','https://'),'',trim(site_url(),'/')).'>';
                $body = self::trans('user_ip').': '.$ip."\n<br />";
                $body .= self::trans('user_message').': '.$message."\n<br />";
                $body .= self::trans('user_message_page').': '.$message_page."\n<br />";
                //$send_res = wp_mail( $options['email_for_first_msg'], self::trans('subject_email_for_first_msg'), $body, $headers );
                self::sendEmail($options['email_for_first_msg'], self::trans('subject_email_for_first_msg'), $body, $headers);
            }

            $data = array(
                'action'                => 'message',
                'key'                   => $personalKey,
                'hash'                  => $hash,
                'ip'                    => $ip,
                'browser'               => $browser,
                'message'               => $message,
                'message_page'          => $message_page,
                'created'               => $created,
                'user_tz'               => $user_timezone,
                'server_tz'             => $server_timezone,
                'notification_result'   => (int)$send_res,
                'plugin_version'        => self::$plugin_version,
                'domain'                => site_url()
            );
            $requestVars = array('body' => array(ChatsAction::$tagAnswer => ChatsAction::convertString($data,'encode')));

            return ChatsAction::requestServer( $requestVars );
        }
		
		private static function sendEmail($email, $subject, $message, $headers = array(), $test = false)
		{
            //  mail_with (0 - wp_mail, 1 - wp-chat, 2 - smtp) 
			$is_mail_send = false;
            $options = self::plugin_options( 'get' );
            try {
			    if (isset($options['mail_with']) && $options['mail_with'] == 1) { // send with wp-chat.com
                    $personalKey = (string)get_option(ChatsAction::$optionKey, '');
                    
                    $data = array(
                       'action'            => 'send_mail',
                       'key'               => $personalKey,
                       'plugin_version'    => self::$plugin_version,
                       'site'              => site_url(),
                       'subject'           => $subject,
                       'to'                => $email,
                       'from'              => 'Chats <chats@'.str_replace( array('/www.','http://','https://'),'',trim(site_url(),'/') ).'>',
                       'message'           => $message,
                    );
                    $requestVars = array('body' => array(ChatsAction::$tagAnswer => ChatsAction::convertString($data,'encode')));

                    $requestAnswer = ChatsAction::requestServer( $requestVars, 1 );
                    $answerBody     = @trim($requestAnswer['body']);
                    $answerBodyData = array();
                    if( !empty($answerBody) ){
                        $answerBodyData = ChatsAction::convertString($answerBody, 'decode');
                    }   
                         
                    if(@(int)$answerBodyData['status'] == 1){
                        $is_mail_send = true;
                    } else {
                        $is_mail_send = false;
                    }
			    } elseif (isset($options['mail_with']) && $options['mail_with'] == 0) {	
                    $is_mail_send = wp_mail( $email, $subject . ' ' . str_replace( array('/www.','http://','https://'),'',trim(site_url(),'/') ), $message, $headers );
			    } else {
				    $smtp_host = isset($options['smtp_host']) ? $options['smtp_host'] : '';
				    $smtp_user = isset($options['smtp_user']) ? $options['smtp_user'] : '';
				    $smtp_password = isset($options['smtp_password']) ? $options['smtp_password'] : '';
				    $smtp_port = isset($options['smtp_port']) ? $options['smtp_port'] : '';
                
				    if (!empty($smtp_host) && !empty($smtp_user) && !empty($smtp_password) && !empty($smtp_port)) {
					    if (!class_exists('PHPMailer')) {
						    include ABSPATH . WPINC . '/class-phpmailer.php';
					    }
					    
					    $phpmailer = new PHPMailer();
					    $phpmailer->addAddress($email, '');
					    $phpmailer->SMTPAuth = true;
					    $phpmailer->SMTPSecure = "ssl";
					    $phpmailer->Host = isset($options['smtp_host']) ? $options['smtp_host'] : '';
					    $phpmailer->Port = isset($options['smtp_port']) ? (int)$options['smtp_port'] : '';
					    $phpmailer->Username = isset($options['smtp_user']) ? $options['smtp_user'] : '';
					    $phpmailer->Password = isset($options['smtp_password']) ? $options['smtp_password'] : '';
					    $phpmailer->isSMTP();     
					    $phpmailer->From = isset($options['smtp_user']) ? $options['smtp_user'] : ''; 
					    $phpmailer->FromName = isset($options['smtp_user']) ? $options['smtp_user'] : '';
					    
					    $phpmailer->Subject = $subject . ' ' . str_replace( array('/www.','http://','https://'),'',trim(site_url(),'/') );
					    $phpmailer->Body = $message;
					    $is_mail_send = $phpmailer->Send();
                        if ($test) {
                            self::$error = $phpmailer->ErrorInfo;
                        }
				    }
			    }
            } catch(phpmailerException $e) {
                self::$error = $e->getMessage();
            }
           
			return $is_mail_send; 
		}

        /**
         * Send message from admin to user in offline mode
         */
        public static function send_offline_messages($hash, $ip, $browser, $message, $name, $email, $created, $message_page, $currentUserID, $user_timezone = 0, $server_timezone = 0){
            $personalKey    = (string)get_option(ChatsAction::$optionKey, '');
            $options        = self::plugin_options( 'get' );

            //send offline messages
            $send_res = false;
			$is_mail_send = false;
            if( !empty($options['email_for_offline_msg']) ) {
				$headers = array();
				$headers['charset'] = 'Content-Type: text/html; charset=UTF-8';
                $is_mail_send = self::sendEmail($options['email_for_offline_msg'], self::trans('subject_offline_message'), $message, $headers);
            }

            //send to admin panel
            $data = array(
                'action'                => 'offline_message',
                'key'                   => $personalKey,
                'hash'                  => $hash,
                'ip'                    => $ip,
                'browser'               => $browser,
                'message'               => $message,
                'user_name'             => $name,
                'user_email'            => $email,
                'message_page'          => $message_page,
                'created'               => $created,
                'user_tz'               => $user_timezone,
                'server_tz'             => $server_timezone,
                'notification_result'   => (int)$send_res,
                'plugin_version'        => self::$plugin_version,
                'domain'                => site_url(),
				'is_mail_send'          => $is_mail_send,
            );
            $requestVars = array('body' => array(ChatsAction::$tagAnswer => ChatsAction::convertString($data,'encode')));

            return ChatsAction::requestServer( $requestVars );
        }

        /**
         * Processing log from user
         */
        public static function send_log($hash, $ip, $browser, $created, $logCommand, $logData = array(), $referer_page = '', $currentUserID = 0, $user_timezone = 0, $server_timezone = 0){
            //maybe we should get email of logged users?

            $personalKey = (string)get_option(ChatsAction::$optionKey, '');

            $data = array(
                'action'            => 'log',
                'key'               => $personalKey,
                'hash'              => $hash,
                'ip'                => $ip,
                'browser'           => $browser,
                'log_command'       => $logCommand,
                'log_data'          => $logData,
                'referer_page'      => $referer_page,
                'created'           => $created,
                'user_tz'           => $user_timezone,
                'server_tz'         => $server_timezone,
                'plugin_version'    => self::$plugin_version,
                'domain'            => site_url()
            );
            $requestVars = array('body' => array(ChatsAction::$tagAnswer => ChatsAction::convertString($data,'encode')));

            return ChatsAction::requestServer( $requestVars );
        }

        /**
         * Processing update of options
         */
        public static function send_options($options){
            //maybe we should get email of logged users?

            $personalKey = (string)get_option(ChatsAction::$optionKey, '');

            $data = array(
                'action'            => 'update_options',
                'key'               => $personalKey,
                'options'           => json_encode($options),
                'plugin_version'    => self::$plugin_version,
                'domain'            => site_url()
            );
            $requestVars = array('body' => array(ChatsAction::$tagAnswer => ChatsAction::convertString($data,'encode')));

            return ChatsAction::requestServer( $requestVars );
        }

        /**
         * Authorization of user from plugin
         */
        public static function send_auth($mode, $vars = array()){
            $personalKey    = (string)get_option(ChatsAction::$optionKey, '');
            $personalKey    = (empty($personalKey) ? md5(time().rand(1,9999).microtime().rand(1,9999)) : $personalKey );
            $site           = site_url();
            $data           = array();

            if($mode == 'check'){
                $data = array(
                    'action'            => 'auth_check',
                    'plugin'            => self::$plugin_name,
                    'plugin_version'    => self::$plugin_version,
                    'domain'            => site_url()
                );
            }
            if($mode == 'register'){
                $data = array(
                    'action'            => 'auth_register',
                    'key'               => $personalKey,
                    'username'          => $vars['username'],
                    'password'          => $vars['password'],
                    'plugin'            => self::$plugin_name,
                    'plugin_version'    => self::$plugin_version,
                    'domain'            => site_url()
                );
            }
            if($mode == 'login'){
                $data = array(
                    'action'            => 'auth_login',
                    'key'               => $personalKey,
                    'username'          => $vars['username'],
                    'password'          => $vars['password'],
                    'plugin'            => self::$plugin_name,
                    'plugin_version'    => self::$plugin_version,
                    'domain'            => site_url()
                );
            }

            $requestVars    = array('body' => array(ChatsAction::$tagAnswer => ChatsAction::convertString($data,'encode')));
            $requestAnswer  =  ChatsAction::requestServer( $requestVars, 1 );

            $answerBody     = @trim($requestAnswer['body']);
            $answerBodyData = array();
            if( !empty($answerBody) ){
                $answerBodyData = ChatsAction::convertString($answerBody,'decode');
            }

            if(@(int)$answerBodyData['status'] == 1){
                //save key
                add_option(ChatsAction::$optionKey, $personalKey);

                //save username of user
                $options = self::plugin_options('get');
                $options['auth_email'] = $vars['username'];
                self::plugin_options('add',$options);

            }

            return array(
                'status'        => @(int)$answerBodyData['msg'],
                'msg'           => self::trans( (empty($answerBodyData['msg']) ? 'answer_server_not_answer' : $answerBodyData['msg']) ),
                'redirect_url'  => @$answerBodyData['redirect_url']
            );
        }

        /**
         * Read messages from db
         */
        public static function read_messages($hash, $ip, $browser = '', $currentUserID = 0, $parameters = array()){
            global $wpdb;

            $list       = array();
            $options    = self::plugin_options( 'get' );

            $limit = @(int)$parameters['countMessage'];
            $limit = (empty($limit) ? 1 : $limit);

            if( empty($parameters['queryMode']) ){

            }

            if( $parameters['queryMode'] == 'api_read' ){
                $sql =  $wpdb->prepare('
                    SELECT * FROM  `'.$wpdb->base_prefix.self::$table_prefix.'messages`
                    WHERE
                        user_hash = %s AND user_ip = %s
                    ORDER BY
                        created DESC
                    ',
                    $hash, $ip
                );
                $list = $wpdb->get_results($sql);
            }

            if( $parameters['queryMode'] == 'user_last' ){
                $sql =  $wpdb->prepare('
                    SELECT id, message, created, message_type FROM  `'.$wpdb->base_prefix.self::$table_prefix.'messages`
                    WHERE
                        user_hash = %s AND user_ip = %s AND ( (user_browser = %s AND message_type = 0) OR (user_browser = "" AND message_type = 1) )
                    ORDER BY
                        created DESC
                    LIMIT 0, '.$limit.'
                    ',
                    $hash, $ip, $browser
                );
                $messages = $wpdb->get_results($sql);

                if( !empty($messages) ){
                    foreach($messages as $k_item => $v_item){
                        $list[] = array(
                            'name'          => ($v_item->message_type == 0 ? $options['user_signature'] : $options['admin_signature']),
                            'message_id'    => $v_item->id.'_'.$hash,
                            'text'          => nl2br($v_item->message),
                            'time'          => date('H:i', @strtotime($v_item->created)),
                            'type'          => $v_item->message_type
                        );
                    }

                    if($limit > 1){
                        $list = array_reverse($list);
                    }
                }
            }

            return $list;
        }

        /**
         * Add message in db
         */
        public static function add_messages($hash, $ip, $browser, $message, $created = '', $message_type = 0, $message_page = '', $currentUserID = 0){
            global $wpdb;

            $created = ( empty($created) ? date('Y-m-d H:i:s') : $created);

            $sql =  $wpdb->prepare(
                'INSERT INTO `'.$wpdb->base_prefix.self::$table_prefix.'messages` SET
                    user_hash = %s,
                    user_ip = %s,
                    user_browser = %s,
                    message = %s,
                    message_page = %s,
                    created = %s,
                    message_type = %d
                ',
                $hash, $ip, $browser, $message, $message_page, $created, $message_type
            );
            return $wpdb->query( $sql );
        }

        /**
         * Remove messages from db
         *
         * @param $hash
         * @param $ip
         *
         * @return bool
         */
        public static function remove_messages($hash, $ip, $mode = '' ){
            global $wpdb;

            if( empty($mode) ){

            }

            //remove sll message of user
            if($mode == 'user'){
                $sql =  $wpdb->prepare('
                    DELETE FROM  `'.$wpdb->base_prefix.self::$table_prefix.'messages` WHERE user_hash = %s AND user_ip = %s',
                    $hash, $ip
                );
                $wpdb->query($sql);
            }

            if($mode == 'all'){
                $sql =  'TRUNCATE TABLE `'.$wpdb->base_prefix.self::$table_prefix.'messages`';
                $wpdb->query($sql);
            }

            return true;
        }

        public static function getUserIP(){
            $user_ip = '';
            if ( getenv('REMOTE_ADDR') ){
                $user_ip = getenv('REMOTE_ADDR');
            }elseif ( getenv('HTTP_FORWARDED_FOR') ){
                $user_ip = getenv('HTTP_FORWARDED_FOR');
            }elseif ( getenv('HTTP_X_FORWARDED_FOR') ){
                $user_ip = getenv('HTTP_X_FORWARDED_FOR');
            }elseif ( getenv('HTTP_X_COMING_FROM') ){
                $user_ip = getenv('HTTP_X_COMING_FROM');
            }elseif ( getenv('HTTP_VIA') ){
                $user_ip = getenv('HTTP_VIA');
            }elseif ( getenv('HTTP_XROXY_CONNECTION') ){
                $user_ip = getenv('HTTP_XROXY_CONNECTION');
            }elseif ( getenv('HTTP_CLIENT_IP') ){
                $user_ip = getenv('HTTP_CLIENT_IP');
            }

            $user_ip = trim($user_ip);
            if ( empty($user_ip) ){
                return '';
            }
            if ( !preg_match("/^([1-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$/", $user_ip) ){
                return '';
            }
            return $user_ip;
        }

        public static function trans($var, $lang = 'en'){
            $text = $var;
            if( isset(self::$translation[$lang]) ){
                if( isset(self::$translation[$lang][$var]) ){
                    $text = self::$translation[$lang][$var];
                }elseif( isset(self::$translation['en'][$var]) ){
                    $text =  self::$translation['en'][$var];
                }
            }

            return $text;
        }

        public static function chats_settings_page(){
            wp_register_script('chats_settings_js', plugins_url(self::$plugin_name . '/assets/jquery.minicolors.min.js'), array(), self::$plugin_version, false);
            wp_register_script('chats_settings_js1', plugins_url(self::$plugin_name . '/assets/jquery.arcticmodal.min.js'), array(), self::$plugin_version, false);
            wp_register_script('chats_settings_js2', plugins_url(self::$plugin_name . '/assets/admin.js'), array(), self::$plugin_version, false);
            wp_register_style( 'chats_settings_css', plugins_url( self::$plugin_name . '/assets/jquery.minicolors.css' ) );
            wp_register_style( 'chats_settings_css1', plugins_url( self::$plugin_name . '/assets/jquery.arcticmodal.css' ) );
            wp_register_style( 'chats_settings_css2', plugins_url( self::$plugin_name . '/assets/admin.css' ) );

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'chats_settings_js' );
            wp_enqueue_script( 'chats_settings_js1' );
            wp_enqueue_script( 'chats_settings_js2' );
            wp_enqueue_style( 'chats_settings_css' );
            wp_enqueue_style( 'chats_settings_css1' );
            wp_enqueue_style( 'chats_settings_css2' );
            wp_enqueue_media();
            
            if (isset($_POST['testMail']) && isset($_POST['type_send']) && isset($_POST['email_test'])) {
                if (!empty($_POST['email_test']) && preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/", $_POST['email_test'] ) ) {
                    $headers['charset'] = 'Content-Type: text/html; charset=UTF-8';
                    $headers['from']    = 'From: Chats <'.str_replace(array('/www.','http://','https://'),'',trim(site_url(),'/')).'>';
                    $options = self::plugin_options( 'get' );
                    $options['mail_with'] = (int)$_POST['type_send']; 
                    self::plugin_options( 'add', $options );
                    $send = self::sendEmail($_POST['email_test'], 'Test email from Chats plugin', 'Hello, this is test email from Chats plugin <br />', $headers, true);
                    if ($send) {
                        ?>
                            <div class="updated" style="background:#a4ffc4; border-left-color:#007618; font-weight:700;"><p><?php echo self::trans('test_mail_was_successfully'); ?></p></div>
                        <?php
                    } else {
                        ?>
                        <div class="error"><p style="font-size:15px;"><?php echo self::trans( 'test_mail_was_not_sent' ); ?></p></div>
                        <?php
                    }
                } else {
                    ?> 
                    <div class="error"><p style="font-size:15px;"><?php echo self::trans('mail_is_empty');?></p></div>
                    <?php
                }
            }
            
            if (isset($_POST['test-smtp'])) {
                $smtp_host = isset($_POST['test_smtp_host']) ? $_POST['test_smtp_host'] : '';
                $smtp_user = isset($_POST['test_smtp_user']) ? $_POST['test_smtp_user'] : '';
                $smtp_password = isset($_POST['test_smtp_pass']) ? $_POST['test_smtp_pass'] : '';
                $smtp_port = isset($_POST['test_smtp_port']) ? $_POST['test_smtp_port'] : '';
                if (!empty($smtp_host) && !empty($smtp_user) && !empty($smtp_password) && !empty($smtp_port)) {
                    
                    if (!class_exists('PHPMailer')) {
                        include ABSPATH . WPINC . '/class-phpmailer.php';
                    }
                    $options        = self::plugin_options( 'get' );    
                    if ( !empty($options['email_for_offline_msg']) || !empty($options['email_for_first_msg']) )   {
                        set_time_limit(30);
                        $headers['charset'] = 'Content-Type: text/html; charset=UTF-8';
                        $headers['from']    = 'From: Chats <'.str_replace(array('/www.','http://','https://'),'',trim(site_url(),'/')).'>';
                        $email = @$options['email_for_offline_msg'];
                        if(empty($email)) {
                            $email = $options['email_for_first_msg'];
                        }
                        $options['smtp_host'] = $smtp_host;
                        $options['smtp_user'] = $smtp_user;
                        $options['smtp_password'] = $smtp_password;
                        $options['smtp_port'] = $smtp_port;
                        self::plugin_options( 'add', $options );
                        self::sendEmail($email, 'Test email from Chats plugin', 'Hello, this is test email from Chats plugin <br />', $headers, true);
                        if ( !empty( self::$error ) ) {
                            ?>
                            <div class="error"><p><?php echo self::$error; ?></p></div>
                            <?php
                        } else {
                            ?>
                            <div class="updated" style="background:#a4ffc4; border-left-color:#007618; font-weight:700;"><p><?php echo self::trans('smtp_test_was_successfully'); ?></p></div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="error"><p><?php echo self::trans('notification_email_empty'); ?></p></div>
                        <?php
                    }
                }
            }

            $options        = self::plugin_options( 'get' );
            $options_access = self::plugin_options('get', array(), 'chats_access');
            $personalKey    = (string)get_option(ChatsAction::$optionKey, '');
        ?>
            <div class="wrap" id="chat_settings_page_over">
                <h2><?php echo self::trans('page_settings_title');?></h2>
                <div id="chat_settings_page" style="position:relative;">
                    <form method="post" action="options.php">
                    <?php
                    settings_fields( self::$optionParameters );
                    //do_settings_sections( self::$plugin_name );
                    ?>
                    <div id="chats_auth_tab" class="tab">
                        <table class="form-table">
                            <tr class="row_title">
                                <th scope="row"><?php echo self::trans('settings_tab_auth');?></th>
                            </tr>
                            <tr>
                                <th style="text-align: center"><input onclick="popupAuth(this, '<?php echo admin_url( 'admin-post.php?action=chats_auth_page' );?>');" class="button button-primary" id="auth_but" type="button" value="<?php echo (empty($personalKey) ? self::trans('settings_auth_register_btn') : self::trans('settings_auth_login_btn'));?>" /></th>
                            </tr>
                        </table>
                    </div>
                    <?php if(!empty($personalKey)){ ?>
                    <div id="chats_template_tab" class="tab">
                        <table class="form-table">
                            <tr class="row_title">
                                <th scope="row" colspan="2"><?php echo self::trans('settings_tab_template');?></th>
                            </tr>
                            <tr>
                                <th style="width: 120px !important;" scope="row"><?php echo self::trans('settings_width');?>:</th>
                                <td><input style="width:90px;" type="text" name="<?php echo self::$optionParameters;?>[width]" value="<?php echo esc_attr( @$options['width'] ); ?>" /> px</td>
                            </tr>
                            <tr>
                                <th style="width: 120px !important;" scope="row"><?php echo self::trans('settings_position');?>:</th>
                                <td>
                                    <select style="width: 185px;" name="<?php echo self::$optionParameters;?>[position]">
                                        <option <?php echo ( (empty($options['position']) or @$options['position'] == 'right') ? 'selected="selected"' : '');?> value="right"><?php echo self::trans('settings_position_right');?></option>
                                        <option <?php echo ( @$options['position'] == 'left' ? 'selected="selected"' : '');?> value="left"><?php echo self::trans('settings_position_left');?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 120px !important;" scope="row"><?php echo self::trans('settings_status');?>:</th>
                                <td>
                                    <select style="width: 185px;" name="<?php echo self::$optionParameters;?>[status]">
                                        <option <?php echo ( @(int)$options['status'] == 1 ? 'selected="selected"' : '');?> value="1"><?php echo self::trans('settings_status_1');?></option>
                                        <option <?php echo ( @(int)$options['status'] == 2 ? 'selected="selected"' : '');?> value="2"><?php echo self::trans('settings_status_2');?></option>
                                        <option <?php echo ( @(int)$options['status'] == 3 ? 'selected="selected"' : '');?> value="3"><?php echo self::trans('settings_status_3');?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 120px !important;" scope="row"><?php echo self::trans('settings_show_avatars');?>:</th>
                                <td>
                                    <input <?php echo ( @(int)$options['show_avatars'] == 1 ? 'checked="checked"' : '');?> type="checkbox" name="<?php echo self::$optionParameters;?>[show_avatars]" value="1" />
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 120px !important;" scope="row"><?php echo self::trans('settings_admin_avatar');?>:</th>
                                <td>
                                    <input id="admin_avatar_input" type="text" name="<?php echo self::$optionParameters;?>[admin_avatar]" value="<?php echo esc_attr( @$options['admin_avatar'] ); ?>" />
                                    <input type="submit" class="button upload_image_button" value="<?php _e('Select'); ?>" data-target-id="admin_avatar_input"/>
                                </td>
                            </tr>
                            <tr>
                                <th style="width: 120px !important;" scope="row"><?php echo self::trans('settings_user_avatar');?>:</th>
                                <td>
                                    <input id="user_avatar_input" type="text" name="<?php echo self::$optionParameters;?>[user_avatar]" value="<?php echo esc_attr( @$options['user_avatar'] ); ?>" />
                                    <input type="submit" class="button upload_image_button" value="<?php _e('Select'); ?>" data-target-id="user_avatar_input"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php } ?>
                    <div style="float:left;width:100%;height: 0px;">&nbsp;</div>

                    <?php if(!empty($personalKey)){ ?>
                    <div id="chats_color_tab" class="tab">
                        <table class="form-table">
                            <thead>
                                <tr class="row_title">
                                    <th scope="row" colspan="2">
                                        <?php echo self::trans('settings_tab_color');?>
                                        <span onclick="showFullContent(this);" class="chats_tab_action">&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="width: 283px" scope="row"><?php echo self::trans('settings_panel_background');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[panel_background]" value="<?php echo esc_attr( @$options['panel_background'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_btn_finish_background');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[btn_finish_background]" value="<?php echo esc_attr( @$options['btn_finish_background'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_btn_finish_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[btn_finish_color]" value="<?php echo esc_attr( @$options['btn_finish_color'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_btn_finish_border_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[btn_finish_border_color]" value="<?php echo esc_attr( @$options['btn_finish_border_color'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_btn_expand_background');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[btn_expand_background]" value="<?php echo esc_attr( @$options['btn_expand_background'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_panel_border_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[panel_border_color]" value="<?php echo esc_attr( @$options['panel_border_color'] ); ?>" /></td>
                                </tr>
                                <tr class="row_border"><td colspan="2">&nbsp;</td></tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_body_background');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[body_background]" value="<?php echo esc_attr( @$options['body_background'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_admin_signature_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[admin_signature_color]" value="<?php echo esc_attr( @$options['admin_signature_color'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_admin_text_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[admin_text_color]" value="<?php echo esc_attr( @$options['admin_text_color'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_user_signature_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[user_signature_color]" value="<?php echo esc_attr( @$options['user_signature_color'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_user_text_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[user_text_color]" value="<?php echo esc_attr( @$options['user_text_color'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_time_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[time_color]" value="<?php echo esc_attr( @$options['time_color'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_message_border_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[message_border_color]" value="<?php echo esc_attr( @$options['message_border_color'] ); ?>" /></td>
                                </tr>
                                <tr class="row_border"><td colspan="2">&nbsp;</td></tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_write_panel_background');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[write_panel_background]" value="<?php echo esc_attr( @$options['write_panel_background'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_write_area_background');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[write_area_background]" value="<?php echo esc_attr( @$options['write_area_background'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_write_area_color');?>:</th>
                                    <td><input class="settings_colorpicker" type="text" name="<?php echo self::$optionParameters;?>[write_area_color]" value="<?php echo esc_attr( @$options['write_area_color'] ); ?>" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="chats_text_tab" class="tab">
                        <table class="form-table">
                            <thead>
                                <tr class="row_title">
                                    <th scope="row" colspan="2">
                                        <?php echo self::trans('settings_tab_text');?>
                                        <span onclick="showFullContent(this);"  class="chats_tab_action">&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_panel_title');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[panel_title]" value="<?php echo esc_attr( @$options['panel_title'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_btn_finish_text');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[btn_finish_text]" value="<?php echo esc_attr( @$options['btn_finish_text'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_admin_signature');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[admin_signature]" value="<?php echo esc_attr( @$options['admin_signature'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_user_signature');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[user_signature]" value="<?php echo esc_attr( @$options['user_signature'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_email_label');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[email_label]" value="<?php echo esc_attr( @$options['email_label'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_name_label');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[name_label]" value="<?php echo esc_attr( @$options['name_label'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_message_label');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[message_label]" value="<?php echo esc_attr( @$options['message_label'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_send_email');?>:</th>
                                    <td><input type="text" name="<?php echo self::$optionParameters;?>[send_email]" value="<?php echo esc_attr( @$options['send_email'] ); ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_enter_text_placeholder');?>:</th>
                                    <td><textarea name="<?php echo self::$optionParameters;?>[enter_text_placeholder]"><?php echo esc_attr( @$options['enter_text_placeholder'] ); ?></textarea></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_hello_message');?>:</th>
                                    <td><textarea name="<?php echo self::$optionParameters;?>[hello_message]"><?php echo esc_attr( @$options['hello_message'] ); ?></textarea></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_thank_message');?>:</th>
                                    <td><textarea name="<?php echo self::$optionParameters;?>[thank_message]"><?php echo esc_attr( @$options['thank_message'] ); ?></textarea></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_offline_message');?>:</th>
                                    <td><textarea name="<?php echo self::$optionParameters;?>[offline_message]"><?php echo esc_attr( @$options['offline_message'] ); ?></textarea></td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php echo self::trans('settings_offline_thank_message');?>:</th>
                                    <td><textarea name="<?php echo self::$optionParameters;?>[offline_thank_message]"><?php echo esc_attr( @$options['offline_thank_message'] ); ?></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div style="float:left;width:100%;height: 0px;">&nbsp;</div>
                    <div id="chats_smtp_tab" class="tab">
                        <table class="form-table">
                            <thead>
                                <tr class="row_title">
                                    <th scope="row" colspan="4">
                                        <?php echo self::trans('settings_smtp'); ?>
                                        <span onclick="showFullContent(this);"  class="chats_tab_action">&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:0;">
                                    
                                    <div style="border:1px solid #ddd; position:relative; padding:15px 15px 10px;margin-top:20px;margin-bottom:10px; line-height: 26px;"  >
                                        <div style="font-weight: 600; position:absolute;top:-15px; background:#f8f8f8; padding:7px; line-height: 18px;">
                                            <?php echo self::trans('send_mail_with');?>
                                        </div>
                                        <style>
                                        .smtp-service {
                                            display: <?php echo isset($options['mail_with']) && $options['mail_with'] == 2 ? 'table-row' : 'none'; ?>    
                                        }
                                        </style>
                                        <div >
                                            <div class="change-smtp-service">
                                                <div>
                                                    <span ><?php echo self::trans('method_1');?>:</span><br />
                                                    <input type="radio" onclick="checkedSMTP(this)" name="<?php echo self::$optionParameters;?>[mail_with]" value="0" <?php echo isset($options['mail_with']) && $options['mail_with'] == 0 ? 'checked="checked"' : (!isset($options['mail_with']) ? 'checked="checked"' : ''); ?> id="with_server_service" style="margin:0;" /> <label for="with_server_service"><?php echo self::trans('with_server_service');?></label><br />
                                                </div>
                                                <div>
                                                    <span ><?php echo self::trans('method_2');?>:</span><br />
                                                    <input type="radio" onclick="checkedSMTP(this);" name="<?php echo self::$optionParameters;?>[mail_with]" value="1" <?php echo isset($options['mail_with']) && $options['mail_with'] == 1 ? 'checked="checked"' : '';?> id="with_wpchatcom_service" style="margin:0;" /> <label for="with_wpchatcom_service"><?php echo self::trans('with_wpchatcom_service');?></label><br />
                                                </div>
                                                <div>
                                                    <span ><?php echo self::trans('method_3');?>:</span><br />
                                                    <input type="radio" onclick="checkedSMTP(this);" name="<?php echo self::$optionParameters;?>[mail_with]" value="2" <?php echo isset($options['mail_with']) && $options['mail_with'] == 2 ? 'checked="checked"' : '';?> id="with_own_smtp_service" style="margin:0;" /> <label for="with_own_smtp_service"><?php echo self::trans('with_own_smtp_service');?></label><br />
                                                </div>
                                            </div>
                                            <div class="description-smtp-service">
                                                <div class="description"><?php echo self::trans('website_smtp_desc');?></div>
                                                <div class="description"><?php echo self::trans('wpchat_smtp_desc');?></div>
                                                <div class="description"><?php echo self::trans('info_smtp_desc');?></div>
                                            </div>
                                            <div class="clear"></div>
                                        <div>
                                        <div class="smtp-block-service">
                                            <div class="smtp-service">
                                                <div class="label-smtp-service" style="padding-top:10px;"><?php echo self::trans('settings_smtp_user');?>:</div>
                                                <div class="field-smtp-service" style="padding-top:10px;"><input type="text" name="<?php echo self::$optionParameters;?>[smtp_user]" id="smtp_user_id" value="<?php echo esc_attr( @$options['smtp_user'] ); ?>" /></div>
                                            </div>
                                            <div class="smtp-service">
                                                <div class="label-smtp-service" ><?php echo self::trans('settings_smtp_pass');?>:</div>
                                                <div class="field-smtp-service" ><input type="text" name="<?php echo self::$optionParameters;?>[smtp_password]" id="smtp_pass_id" value="<?php echo esc_attr( @$options['smtp_password'] ); ?>" /></div>
                                                
                                            </div>
                                            <div class="smtp-service" >
                                                <div class="label-smtp-service" ><?php echo self::trans('settings_smtp_host');?>:</div>
                                                <div class="field-smtp-service" ><input type="text" name="<?php echo self::$optionParameters;?>[smtp_host]" id="smtp_host_id" value="<?php echo esc_attr( @$options['smtp_host'] ); ?>" /></div>
                                                
                                            </div>
                                            <div class="smtp-service">
                                                <div class="label-smtp-service" ><?php echo self::trans('settings_smtp_port');?>:</div>
                                                <div class="field-smtp-service" ><input type="text" name="<?php echo self::$optionParameters;?>[smtp_port]" id="smtp_port_id" value="<?php echo esc_attr( @$options['smtp_port'] ); ?>" /></div>
                                            </div> 
                                            <div class="smtp-service"  >
                                                <div class="label-smtp-service">&nbsp;</div>
                                                <div class="field-smtp-service" style="padding-top:5px;padding-bottom:10px"><input class="button" type="button" value="<?php echo self::trans('test_smtp');?>" onclick="testSMTP();" /></div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;" >
                                    
                                    <div class="notification_email" style="border:1px solid #ddd; position:relative; padding:15px 15px 10px; margin-top:10px;margin-bottom:10px;" >
                                        <div style="font-weight: 600; position:absolute;top:-15px; background:#f8f8f8; padding:7px;">
                                            <?php echo self::trans('settings_tab_notification');?>
                                        </div>   
                                        <label for="email_first_msg"><?php echo self::trans('settings_email_for_first_msg');?>:</label>
                                        <input class="button email-service-button" type="button" value="<?php echo self::trans('test_mail');?>" onclick="testMail('#email_first_msg');" />
                                        <input type="text" id="email_first_msg" name="<?php echo self::$optionParameters;?>[email_for_first_msg]" value="<?php echo esc_attr( @$options['email_for_first_msg'] ); ?>" />
                                        
                                        <div class="clear"></div>
                                        <label for="email_offline_msg"><?php echo self::trans('settings_email_for_offline_msg');?>:</label>
                                        <input class="button email-service-button" type="button" value="<?php echo self::trans('test_mail');?>" onclick="testMail('#email_offline_msg');" />
                                        <input type="text" id="email_offline_msg" name="<?php echo self::$optionParameters;?>[email_for_offline_msg]" value="<?php echo esc_attr( @$options['email_for_offline_msg'] ); ?>" />
                                        <div class="clear"></div>
                                        <p style="line-height: 18px; font-style: italic;">
                                        <?php echo self::trans('desc_mail_notification');?>
                                        </p>
                                    </div>
                                    </td>
                                </tr>
                                
                                
                            </tbody>
                        </table>
                        
                    </div>
                    <div style="float:left;width:100%;height: 0px;">&nbsp;</div>
                    <div style="position:absolute;bottom:0;"><?php submit_button(); ?></div>
                     </form> 
                    <div id="chats_posts_page_setting" class="tab" style="margin-bottom:40px;">
                    <form method="post" action="options.php" >
                    <?php settings_fields( 'chats_access' ); ?>
                        <table class="form-table">
                            <thead>
                                <tr class="row_title">
                                    <th scope="row" colspan="4">
                                        <?php echo self::trans('title_page_post_setting'); ?>
                                        <span onclick="showFullContent(this);"  class="chats_tab_action">&nbsp;</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        <?php echo self::trans('pps_access_view'); ?> <?php echo self::trans('pps_show_main'); ?>:
                                    </th>
                                    <td>
                                        <input <?php echo isset( $options_access['show_main'] ) && $options_access['show_main'] == 1 ? 'checked="checked"' : ( !isset($options_access['show_main']) ? 'checked="checked"' : '' ); ?> type="radio" value="1" name="chats_access[show_main]"  id="chats_access_show_main_yes"><label for="chats_access_show_main_yes"><?php echo self::trans('pps_yes')?></label>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input  <?php echo isset( $options_access['show_main'] ) && $options_access['show_main'] == 0 ? 'checked="checked"' : ''; ?> type="radio" value="0" name="chats_access[show_main]" id="chats_access_show_main_no"><label for="chats_access_show_main_no"><?php echo self::trans('pps_no')?></label>
                                    </td>
                                 <tr>  
                                <tr>
                                    <th>
                                        <label for="pps_view"><?php echo self::trans('pps_access_view'); ?></label>:
                                    </th>
                                    <td>
                                        <div style="float:left;">
                                            <select onchange="getAccessForm(this)" name="chats_access[access]" id="pps_view">
                                                <option <?php echo isset( $options_access['access'] ) && $options_access['access'] == -1 ? 'selected="selected"' : ( !isset($options_access['access']) ? 'selected="selected"' : '' ); ?> value="-1"><?php echo self::trans('pps_none'); ?></option>
                                                <option <?php echo isset( $options_access['access'] ) && $options_access['access'] == 1 ? 'selected="selected"' : ''; ?> value="1"><?php echo self::trans('pps_category'); ?></option>
                                                <option <?php echo isset( $options_access['access'] ) && $options_access['access'] == 2 ? 'selected="selected"' : ''; ?> value="2"><?php echo self::trans('pps_post_page'); ?></option>
                                            </select>
                                        </div>
                                        <div id="loading-access-setting" style="float:left;margin-top:5px; display:none; margin-left:5px;">
                                            <img src="<?php echo plugins_url( self::$plugin_name . '/assets/loader.gif' )?>" />
                                        </div>
                                    </td>
                                 <tr>  
                                 <tr>
                                    <td colspan=2 id="form-show-access">
                                        <?php 
                                        $access = isset($options_access['access']) ? $options_access['access'] : -1;
                                        self::get_access_post_page_category( $access );
                                        ?>
                                    </td>
                                 </tr> 
                            </tbody>
                        </table>
                      </form>
                    </div>    
                    
                    <?php } ?>
                   
                    <form action="" method="POST" name="test_mail_form">
                        <input type="hidden" name="type_send" value="<?php echo isset($options['mail_with']) ? $options['mail_with'] : 0; ?>">
                        <input type="hidden" name="email_test" value="">
                        <input type="hidden" name="testMail" value="1">
                    </form>
                    <form action="" method="POST" name="test_smtp_form">
                        <input type="hidden" name="test-smtp" value="1"  />
                        <input type="hidden" name="test_smtp_host" value=""  />
                        <input type="hidden" name="test_smtp_user" value=""  />
                        <input type="hidden" name="test_smtp_pass" value=""  />
                        <input type="hidden" name="test_smtp_port" value=""  />
                    </form>
                </div>
            </div>
            <script>
                jQuery(document).ready( function() {
                jQuery.each(jQuery('.settings_colorpicker'), function() {
                    jQuery(this).minicolors({
                        defaultValue: jQuery(this).attr('data-defaultValue') || '',
                        inline: jQuery(this).attr('data-inline') === 'true',
                        letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
                        position: jQuery(this).attr('data-position') || 'bottom left',
                        theme: 'default'
                    });

                });
                })
            </script>
        <?php
        }
        
        public static function get_access_post_page_category($form = '')
        {
            if ( ( !isset( $_POST['form'] ) || empty( $_POST['form'] ) ) && !empty($form) ) {
                $form_id = (int)$form;
            } else {
                $form_id = (int)$_POST['form'];
            }
            
            if (isset($form_id)) {
                $options = self::plugin_options('get', array(), 'chats_access');
                switch((int)$form_id) {
                    case 1: 
                        $categoryes = get_categories( array('hide_empty' => 0) );
                        if ( ( $n = count($categoryes) ) > 0) {
                        ?>
                        <div class="access-table">
                         <?php submit_button(self::trans('save_visibility_button')); ?>
                        <table class="wp-list-table widefat striped posts">
                            <tbody style="display:table-row-group;">
                                <tr>
                                    <th><?php echo self::trans('pps_category_name');?></th>
                                    <th><?php echo self::trans('pps_category_posts');?></th>
                                    <th><?php echo self::trans('pps_allow');?></th>
                                    <th><?php echo self::trans('pps_deny');?></th>
                                </tr>
                                <?php 
                                
                                for($i = 0; $i < $n; $i++) {
                                    $deny = $allow = false;
                                    if (isset($options['category']['deny'][$categoryes[$i]->term_id])) {
                                        $deny = true;
                                    }
                                    if (isset($options['category']['allow'][$categoryes[$i]->term_id])) {
                                        $allow = true;
                                    }
                                    if (!$deny && !$allow) {
                                        $allow = true;
                                    }
                                    ?><tr>
                                        <td>
                                            <?php echo $categoryes[$i]->name;?>
                                        </td>
                                        <td>
                                            <a href="<?php echo admin_url() . 'edit.php?category_name=' . $categoryes[$i]->slug; ?>"><?php echo $categoryes[$i]->count;?></a>
                                        </td>
                                        <td>
                                            <input <?php echo $allow ? 'checked="checked"' : ''; ?> type="radio" name="chats_access[category][<?php echo $categoryes[$i]->term_id;?>]" id="category_<?php echo $categoryes[$i]->term_id;?>" value="allow"/>
                                        </td>
                                        <td>
                                            <input <?php echo $deny ? 'checked="checked"' : ''; ?> type="radio" name="chats_access[category][<?php echo $categoryes[$i]->term_id?>]"  id="category_<?php echo $categoryes[$i]->term_id;?>" value="deny" />
                                        </td>
                                    </tr>
                                    <?php
                                }?>
                            </tbody>
                        </table>
                        <?php submit_button(self::trans('save_visibility_button')); ?>  
                        </div>
                        <?php
                        }
                        break;
                    case 2 :
                        $posts = get_posts(array('numberposts' => -1));
                        $pages = get_posts( array('numberposts' => -1, 'post_type' => 'page') );
                        $list = array_merge($posts, $pages);
                        //var_dump($list);
                        if ( ( $n = count($list) ) > 0) {
                            ?>
                            <div class="access-table">
                            <?php submit_button(self::trans('save_visibility_button')); ?>
                            <table class="wp-list-table widefat striped posts">
                                <tbody style="display:table-row-group;">
                                    <tr>
                                        <th><?php echo self::trans('pps_page_post_name');?></th>
                                        <th><?php echo self::trans('pps_type');?></th>
                                        <th><?php echo self::trans('pps_allow');?></th>
                                        <th><?php echo self::trans('pps_deny');?></th>
                                        
                                    </tr>
                                    <?php 
                                    for($i = 0; $i < $n; $i++) {
                                        $deny = $allow = false;
                                        if (isset($options['post_page']['deny'][$list[$i]->ID])) {
                                            $deny = true;
                                        }
                                        if (isset($options['post_page']['allow'][$list[$i]->ID])) {
                                            $allow = true;
                                        }
                                        if (!$deny && !$allow) {
                                            $allow = true;
                                        }
                                        ?><tr>
                                            <td>
                                                <a href="<?php echo admin_url() . 'post.php?action=edit&post=' . $list[$i]->ID ?>"><?php 
                                                $title_post = urldecode($list[$i]->post_name);
                                                if (strlen($title_post) > 95) {
                                                    echo substr($title_post, 0, 95) . '...';
                                                } else {
                                                    echo $title_post; 
                                                }
                                                ?></a>
                                                &nbsp;
                                                <a href="<?php echo $list[$i]->guid; ?>" target="_blank" style="width:16px; height:16px;"><span style="font-size:15px;" class="dashicons dashicons-external"></span></a>
                                            </td>
                                            <td align="left">
                                                <?php echo $list[$i]->post_type;?>
                                            </td>
                                             <td>
                                                <input <?php echo $allow ? 'checked="checked"' : ''; ?> type="radio" name="chats_access[post_page][<?php echo $list[$i]->ID;?>]" id="post_page_<?php echo $list[$i]->ID;?>" value="allow"/>
                                            </td>
                                            <td>
                                                <input <?php echo $deny ? 'checked="checked"' : ''; ?> type="radio" name="chats_access[post_page][<?php echo $list[$i]->ID?>]"  id="post_page_<?php echo $list[$i]->ID;?>" value="deny" />
                                            </td>
                                           
                                        </tr>
                                        <?php
                                    }?>
                                </tbody>
                            </table> 
                            <?php submit_button(self::trans('save_visibility_button')); ?>
                            </div> 
                            <?php
                        }
                        break;
                    default:
                        echo submit_button(self::trans('save_visibility_button'));
                        break;
               }
            }
            if (empty($form)) {
                exit;
            }
        }

        public static function chats_auth_page(){
            $personalKey    = (string)get_option(ChatsAction::$optionKey, '');
            $options        = self::plugin_options( 'get' );

            $authEmail      = @trim($_POST[self::$optionParameters]['auth_email']);
            $authPassword1  = @trim($_POST[self::$optionParameters]['auth_password1']);
            $authPassword2  = @trim($_POST[self::$optionParameters]['auth_password2']);
            $auth_type      = @(int)$_REQUEST[self::$optionParameters]['auth_type'];
            $processRes     = array();

            if($auth_type){
                if($auth_type == 2){
                    //register
                    if( !empty($authEmail) and !empty($authPassword1) and !empty($authPassword2) and $authPassword1 == $authPassword2 ){
                        $processRes = self::send_auth('register', array('username' => $authEmail, 'password' => $authPassword1));
                    }else{
                        $processRes = array('status' => 0, 'msg' => self::trans('answer_incorrect_login_or_password'));
                    }
                }else{
                    //login
                    if( !empty($authEmail) and !empty($authPassword1) ){
                        $processRes = self::send_auth('login', array('username' => $authEmail, 'password' => $authPassword1));
                    }else{
                        $processRes = array('status' => 0, 'msg' => self::trans('answer_incorrect_login_or_password'));
                    }
                }
                print json_encode($processRes);exit;
            }

            ob_start();
            ?>
            <form method="post" action="<?php echo admin_url( 'admin-post.php?action=chats_auth_page' )?>">
                <div id="chats_popup_auth_tab" class="tab">
                    <table class="form-table">
                        <tr class="row_title">
                            <th scope="row" colspan="2"><?php echo self::trans('settings_tab_auth');?></th>
                        </tr>
                        <tr class="row_title">
                            <td colspan="2">
                                <ul>
                                    <li>
                                        <input class="auth_type_field" onchange="popupAuthType(this,1);" <?php echo ((!empty($personalKey) or $auth_type == 1) ? 'checked="checked"' : '');?> type="radio" value="1" name="<?php echo self::$optionParameters;?>[auth_type]" />
                                        <label><?php echo self::trans('settings_existing_user');?></label>
                                    </li>
                                    <li>
                                        <input class="auth_type_field" onchange="popupAuthType(this,2);"  <?php echo ((empty($personalKey) or $auth_type == 2) ? 'checked="checked"' : '');?> type="radio" value="2" name="<?php echo self::$optionParameters;?>[auth_type]" />
                                        <label><?php echo self::trans('settings_new_user');?></label>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><span class="label"><?php echo self::trans('settings_auth_email');?>:</span></th>
                            <td><input class="auth_email_field" style="float:left;" <?php echo (!empty($options['auth_email']) ? 'readonly="readonly"' : '');?> type="text" name="<?php echo self::$optionParameters;?>[auth_email]" value="<?php echo @esc_attr($options['auth_email']);?>" /></td>
                        </tr>
                        <tr>
                            <th scope="row"><span class="label"><?php echo self::trans('settings_auth_password1');?>:</span></th>
                            <td><input class="auth_password1_field" style="float:left;" type="password" name="<?php echo self::$optionParameters;?>[auth_password1]" value="<?php echo esc_attr('');?>" /></td>
                        </tr>
                        <tr id="tr_confirm_password" style="<?php echo ((!empty($personalKey) or $auth_type == 1) ? 'display:none;' : '');?>">
                            <th scope="row"><span class="label"><?php echo self::trans('settings_auth_password2');?>:</span></th>
                            <td><input class="auth_password2_field" style="float:left;" type="password" name="<?php echo self::$optionParameters;?>[auth_password2]" value="<?php echo esc_attr('');?>" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;">
                                <input onclick="processAuth(this,2);" style="<?php echo ((empty($personalKey) or $auth_type == 2) ? 'display:none;' : '');?>" class="button button-primary" id="auth_but_login" type="button" value="<?php echo self::trans('settings_auth_login_btn');?>" />
                                <input onclick="processAuth(this,1);" style="<?php echo ((!empty($personalKey) or $auth_type == 1) ? 'display:none;' : '');?>" class="button button-primary" id="auth_but_register" type="button" value="<?php echo self::trans('settings_auth_register_btn');?>" />
                            </td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="<?php echo self::$optionParameters;?>[auth_process]" value="1" />
            </form>
            <?php
            $html = ob_get_contents();
            ob_clean();

            echo $html;
        }

        public static function register_settings(){
            register_setting( self::$optionParameters, self::$optionParameters, array('Chats', 'validate_settings') );
            register_setting( 'chats_access', 'chats_access', array('Chats', 'validate_settings_access') );
        }
        
        public static function validate_settings_access($inputs)
        {
            //$inputs = array_map('trim',$inputs);
            $options = array();
            if (isset($inputs['access'])) {
                $options = self::plugin_options('get', array(), 'chats_access');
                if (!$options) {
                    $options = array();
                }
                if (isset($inputs['show_main'])) {
                    $options['show_main'] = (int)$inputs['show_main'];
                }
                switch((int)$inputs['access']) {
                    case 1: // category access
                        $options['access'] = 1;
                        if (isset($inputs['category'])) {
                            $options['category']['deny'] = array();
                            $options['category']['allow'] = array();
                            foreach($inputs['category'] as $cat_id => $access) {
                                if ($access == 'deny') {
                                    $options['category']['deny'][$cat_id] = true;
                                }
                                if ($access == 'allow') {
                                    $options['category']['allow'][$cat_id] = true;
                                }
                            }
                        }
                        break;
                    case 2: // post or page access
                        $options['access'] = 2; 
                        if (isset($inputs['post_page'])) {
                            $options['post_page']['deny'] = array();
                            $options['post_page']['allow'] = array();
                            foreach($inputs['post_page'] as $post_id => $access) {
                                if ($access == 'deny') {
                                    $options['post_page']['deny'][$post_id] = true;
                                }
                                if ($access == 'allow') {
                                    $options['post_page']['allow'][$post_id] = true;
                                }
                            }
                        }
                        break;
                    default:
                        $options['access'] = -1;
                        break;
                }
            }
            if (isset($inputs['show_main'])) {
                $options['show_main'] = (int)$inputs['show_main'];
            }
            return $options;
        }

        public static function validate_settings($input, $sendOptions = 1){
            
            
            $input = array_map('trim',$input);
            //filter
            $input['panel_background']          = self::validate_settings_color($input['panel_background']);
            $input['panel_border_color']        = self::validate_settings_color($input['panel_border_color']);
            $input['body_background']           = self::validate_settings_color($input['body_background']);
            $input['btn_finish_background']     = self::validate_settings_color($input['btn_finish_background']);
            $input['btn_finish_color']          = self::validate_settings_color($input['btn_finish_color']);
            $input['btn_finish_border_color']   = self::validate_settings_color($input['btn_finish_border_color']);
            $input['btn_expand_background']     = self::validate_settings_color($input['btn_expand_background']);
            $input['admin_signature_color']     = self::validate_settings_color($input['admin_signature_color']);
            $input['admin_text_color']          = self::validate_settings_color($input['admin_text_color']);
            $input['user_signature_color']      = self::validate_settings_color($input['user_signature_color']);
            $input['user_text_color']           = self::validate_settings_color($input['user_text_color']);
            $input['time_color']                = self::validate_settings_color($input['time_color']);
            $input['message_border_color']      = self::validate_settings_color($input['message_border_color']);
            $input['write_panel_background']    = self::validate_settings_color($input['write_panel_background']);
            $input['write_area_background']     = self::validate_settings_color($input['write_area_background']);
            $input['write_area_color']          = self::validate_settings_color($input['write_area_color']);

            $input['width']     = (int)$input['width'];
            $input['position']  = (!in_array($input['position'], array('right', 'left')) ? '' : $input['position']);
            $input['status']    = (!in_array((int)$input['status'], array(1, 2, 3)) ? 0 : (int)$input['status']);

            $input['admin_signature']           = self::validate_settings_text($input['admin_signature']);
            $input['user_signature']            = self::validate_settings_text($input['user_signature']);
            $input['hello_message']             = self::validate_settings_text($input['hello_message']);
            $input['panel_title']               = self::validate_settings_text($input['panel_title']);
            $input['enter_text_placeholder']    = self::validate_settings_text($input['enter_text_placeholder']);
            $input['btn_finish_text']           = self::validate_settings_text($input['btn_finish_text']);
            $input['thank_message']             = self::validate_settings_text($input['thank_message']);
            $input['offline_message']           = self::validate_settings_text($input['offline_message']);
            $input['email_label']               = self::validate_settings_text($input['email_label']);
            $input['name_label']                = self::validate_settings_text($input['name_label']);
            $input['message_label']             = self::validate_settings_text($input['message_label']);
            $input['send_email']                = self::validate_settings_text($input['send_email']);
            $input['offline_thank_message']     = self::validate_settings_text($input['offline_thank_message']);

            //values
            $input['panel_background']          = (empty($input['panel_background'])            ? self::$defaultOptions['panel_background']             : $input['panel_background']);
            $input['panel_border_color']        = (empty($input['panel_border_color'])          ? self::$defaultOptions['panel_border_color']           : $input['panel_border_color']);
            $input['body_background']           = (empty($input['body_background'])             ? self::$defaultOptions['body_background']              : $input['body_background']);
            $input['btn_finish_background']     = (empty($input['btn_finish_background'])       ? self::$defaultOptions['btn_finish_background']        : $input['btn_finish_background']);
            $input['btn_finish_color']          = (empty($input['btn_finish_color'])            ? self::$defaultOptions['btn_finish_color']             : $input['btn_finish_color']);
            $input['btn_finish_border_color']   = (empty($input['btn_finish_border_color'])     ? self::$defaultOptions['btn_finish_border_color']      : $input['btn_finish_border_color']);
            $input['btn_expand_background']     = (empty($input['btn_expand_background'])       ? self::$defaultOptions['btn_expand_background']        : $input['btn_expand_background']);
            $input['admin_signature_color']     = (empty($input['admin_signature_color'])       ? self::$defaultOptions['admin_signature_color']        : $input['admin_signature_color']);
            $input['admin_text_color']          = (empty($input['admin_text_color'])            ? self::$defaultOptions['admin_text_color']             : $input['admin_text_color']);
            $input['user_signature_color']      = (empty($input['user_signature_color'])        ? self::$defaultOptions['user_signature_color']         : $input['user_signature_color']);
            $input['user_text_color']           = (empty($input['user_text_color'])             ? self::$defaultOptions['user_text_color']              : $input['user_text_color']);
            $input['time_color']                = (empty($input['time_color'])                  ? self::$defaultOptions['time_color']                   : $input['time_color']);
            $input['message_border_color']      = (empty($input['message_border_color'])        ? self::$defaultOptions['message_border_color']         : $input['message_border_color']);
            $input['write_panel_background']    = (empty($input['write_panel_background'])      ? self::$defaultOptions['write_panel_background']       : $input['write_panel_background']);
            $input['write_area_background']     = (empty($input['write_area_background'])       ? self::$defaultOptions['write_area_background']        : $input['write_area_background']);
            $input['write_area_color']          = (empty($input['write_area_color'])            ? self::$defaultOptions['write_area_color']             : $input['write_area_color']);

            $input['width']     = (empty($input['width'])       ? self::$defaultOptions['width']    : $input['width']);
            $input['position']  = (empty($input['position'])    ? self::$defaultOptions['position'] : $input['position']);
            $input['status']    = (empty($input['status'])      ? self::$defaultOptions['status']   : $input['status']);

            $input['admin_signature']           = (empty($input['admin_signature'])         ? self::$defaultOptions['admin_signature']          : $input['admin_signature']);
            $input['user_signature']            = (empty($input['user_signature'])          ? self::$defaultOptions['user_signature']           : $input['user_signature']);
            $input['hello_message']             = (empty($input['hello_message'])           ? self::$defaultOptions['hello_message']            : $input['hello_message']);
            $input['panel_title']               = (empty($input['panel_title'])             ? self::$defaultOptions['panel_title']              : $input['panel_title']);
            $input['enter_text_placeholder']    = (empty($input['enter_text_placeholder'])  ? self::$defaultOptions['enter_text_placeholder']   : $input['enter_text_placeholder']);
            $input['btn_finish_text']           = (empty($input['btn_finish_text'])         ? self::$defaultOptions['btn_finish_text']          : $input['btn_finish_text']);
            $input['thank_message']             = (empty($input['thank_message'])           ? self::$defaultOptions['thank_message']            : $input['thank_message']);
            $input['offline_message']           = (empty($input['offline_message'])         ? self::$defaultOptions['offline_message']          : $input['offline_message']);
            $input['email_label']               = (empty($input['email_label'])             ? self::$defaultOptions['email_label']              : $input['email_label']);
            $input['name_label']                = (empty($input['name_label'])              ? self::$defaultOptions['name_label']               : $input['name_label']);
            $input['message_label']             = (empty($input['message_label'])           ? self::$defaultOptions['message_label']            : $input['message_label']);
            $input['send_email']                = (empty($input['send_email'])              ? self::$defaultOptions['send_email']               : $input['send_email']);
            $input['offline_thank_message']     = (empty($input['offline_thank_message'])   ? self::$defaultOptions['offline_thank_message']    : $input['offline_thank_message']);

            $input['show_avatars']  = (empty($input['show_avatars'])    ? self::$defaultOptions['show_avatars'] : $input['show_avatars']);
            $input['admin_avatar']  = (empty($input['admin_avatar'])    ? self::$defaultOptions['admin_avatar'] : $input['admin_avatar']);
            $input['user_avatar']   = (empty($input['user_avatar'])     ? self::$defaultOptions['user_avatar']  : $input['user_avatar']);

            $input['email_for_first_msg']       = self::validate_settings_text($input['email_for_first_msg']);
            $input['email_for_offline_msg']     = self::validate_settings_text($input['email_for_offline_msg']);
			
			
			// smtp settings
			$input['smtp_host']                 = self::validate_settings_text(@$input['smtp_host']);
			$input['smtp_user']                 = self::validate_settings_text(@$input['smtp_user']);
			$input['smtp_port']                 = self::validate_settings_text(@$input['smtp_port']);
			$input['smtp_pass']                 = self::validate_settings_text(@$input['smtp_pass']);
			
			
			$input['show_copyright']            =  @$input['show_copyright'];
			$input['show_copyright_time']       =  @$input['show_copyright_time'];
			
			

            //do not change login of user here
            if( empty($input['auth_email']) ){
                $options = self::plugin_options('get');
                $input['auth_email'] = $options['auth_email'];
            }
			if ( empty($input['show_copyright_time']) && empty($input['show_copyright']) && ($input['show_copyright'] == 0 || $input['show_copyright'] == 1) ) {
				$options                            = self::plugin_options('get');
				$input['show_copyright']            =  isset($options['show_copyright'] ) ? $options['show_copyright'] : 1;
			    $input['show_copyright_time']       =  isset($options['show_copyright_time'] ) ? $options['show_copyright_time'] : 0;
			}
			
			if (empty($input['smtp_host']) && empty($input['smtp_user']) && empty( $input['mail_with'] )  )  {
				$options                       = self::plugin_options('get');
				$input['smtp_user']            =  isset($options['smtp_user'] ) ? $options['smtp_user'] : '';
			    $input['smtp_host']            =  isset($options['smtp_host'] ) ? $options['smtp_host'] : '';
			    $input['smtp_pass']            =  isset($options['smtp_pass'] ) ? $options['smtp_pass'] : '';
			    $input['smtp_port']            =  isset($options['smtp_port'] ) ? $options['smtp_port'] : '';
			    $input['mail_with']            =  isset($options['mail_with'] ) ? $options['mail_with'] : '';
			}
			

            //send options to server
            if($sendOptions == 1){
                self::send_options($input);
            }

            return $input;
        }

        public static function validate_settings_color($color = ''){
            $color = trim($color);
            if( empty($color) or !preg_match('/^#[a-f0-9]{6}$/i', $color) ){
                return '';
            }
            return $color;
        }

        public static function validate_settings_text($text){
            $text = trim($text);
            $text = strip_tags($text);

            return $text;
        }

        /**
         * Activation hook
         *
         * Create tables if they don't exist and add plugin options
         *
         * @global    object $wpdb
         */
        public static function install(){
            global $wpdb;

            // Get the correct character collate
            $charset_collate = 'DEFAULT CHARACTER SET=utf8';
            if ( ! empty( $wpdb->charset ) ) {$charset_collate = 'DEFAULT CHARACTER SET='.$wpdb->charset;}
            if ( ! empty( $wpdb->collate ) ) {$charset_collate .= ' COLLATE='.$wpdb->collate;}

            // Setup chat message table
            $sql = '
                CREATE TABLE IF NOT EXISTS `'.$wpdb->base_prefix.self::$table_prefix.'messages` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_hash` varchar(10) NOT NULL DEFAULT "",
                    `user_ip` varchar(40) NOT NULL DEFAULT "",
                    `user_browser` varchar(255) NOT NULL DEFAULT "",
                    `user_name` varchar(50) NOT NULL DEFAULT "",
                    `message` varchar(1000) NOT NULL DEFAULT "",
                    `message_page` varchar(255) NOT NULL DEFAULT "",
                    `created` datetime DEFAULT NULL,
                    `message_type` tinyint(1) NOT NULL DEFAULT "0" COMMENT "1 - admin, 0 - user",
                    PRIMARY KEY (`id`)
                ) ENGINE=MyISAM '.$charset_collate.' AUTO_INCREMENT=1
            ;';
            $wpdb->query( $sql );

            $sql = 'TRUNCATE TABLE `'.$wpdb->base_prefix.self::$table_prefix.'messages`';
            $wpdb->query( $sql );

            //Adjust default options
            $options = get_option(self::$optionParameters, array());
            if( empty($options) ){
                self::plugin_options('add');
            }

            add_option(self::$table_prefix . 'notice', array('stars5' => array('time' => time() ) ) );
            
            //Prepare redirect to settings page
            add_option(self::$optionParameters.'_redirect', true);
            
            
        }

        /**
         * Deactivation hook
         *
         * @see        http://codex.wordpress.org/Function_Reference/register_deactivation_hook
         *
         * @global    object $wpdb
         */
        public static function deactivation(){
            global $wpdb;

            $sql = 'TRUNCATE TABLE `'.$wpdb->base_prefix.self::$table_prefix.'messages`';
            $wpdb->query($sql);
        }

        /**
         * Uninstall hook
         *
         * Remove tables and plugin options
         *
         * @global    object $wpdb
         */
        public static function uninstall(){
            global $wpdb;

            //remove table
            $sql = 'DROP TABLE IF EXISTS `'.$wpdb->base_prefix.self::$table_prefix.'messages`';
            $wpdb->query($sql);

            //remove options
            self::plugin_options('remove');
            self::plugin_options('remove', array(), 'chats_access');
            
            delete_option(self::$table_prefix . 'notice');
        }
    }

    class ChatsAction{

        public static $site             = 'http://www.wp-chat.com';
        public static $adminRequestSite = 'http://secure.wp-chat.com';
        public static $adminRequestUrl  = '/from-plugin';

        public static $optionKey        = 'chats_key';
        public static $personalKey      = '';

        public static $requestAnswers = array(
            0 => array('status' => 1, 'msg' => 'request success'),
            1 => array('status' => 1, 'msg' => 'connection success'),
            2 => array('status' => 0, 'msg' => 'connection failed'),
            3 => array('status' => 1, 'msg' => 'adding messages success'),
            4 => array('status' => 0, 'msg' => 'adding messages failed'),
            5 => array('status' => 1, 'msg' => 'ping success'),
            6 => array('status' => 0, 'msg' => 'incorrect request'),
            7 => array('status' => 0, 'msg' => 'plugin should be connected'),
            8 => array('status' => 0, 'msg' => 'wrong key'),
            9 => array('status' => 1, 'msg' => 'update options success'),
            10 => array('status' => 0, 'msg' => 'update options failed'),
        );

        public static $tagAnswer = 'chats_tag';

        /**
         * Listens incoming request
         *
         * Constructor for other methods
         */
        public static function init(){
            //check request
            if( !isset($_POST['chats_request']) or empty($_POST['chats_request']) ){
                return true;    //nothing necessary
            }

            self::$personalKey  = (string)get_option(self::$optionKey, '');
            $allowedHost        = @str_replace(array('http://','https://'),'',trim(self::$adminRequestSite,'/'));
            $allowedIP          = @gethostbyname( $allowedHost );

            $refererHost        = @str_replace(array('http://','https://'),'',trim($_SERVER["HTTP_REFERER"],'/'));
            $requestIP          = Chats::getUserIP();

            //check request ip
            $requestIP = Chats::getUserIP();
            if( empty($requestIP) or $requestIP != $allowedIP ){
                return true;    //wrong request ip
            }

            //check referer
            if( empty($refererHost) or $refererHost != $allowedHost ){
                return true;    //wrong referer
            }

            //check data, action, key
            $requestData    = self::convertString($_POST['chats_request'], 'decode');
            if( empty($requestData) or empty($requestData['action']) or !method_exists('ChatsAction','action_'.$requestData['action']) ){
                self::printAnswer(6);
            }

            //check key for actions
            $action     = (string)'action_'.$requestData['action'];
            $requestKey = @(string)$requestData['key'];
            if( $action == 'action_connect' ){
                if( !empty(self::$personalKey) ){
                    self::printAnswer( (self::$personalKey == $requestKey ? 1 : 2) );
                }
            }else{
                if( empty(self::$personalKey) ){
                    self::printAnswer(7);
                }
                if( empty($requestKey) or self::$personalKey != $requestKey ){
                    self::printAnswer(8);
                }
            }

            //run action
            self::$action($requestData);

            exit;
        }

        /**
         * Send message from user to admin
         */
        public static function requestServer($args, $fullAnswer = 0){
            $url        = trim(self::$adminRequestSite,'/') . self::$adminRequestUrl;
            $postRes    = wp_remote_post( $url, $args );

            if ( is_wp_error( $postRes ) ) {
                $error = array( 'wp_error' => $postRes->get_error_message() );

                return ($fullAnswer ? $postRes : false);
            }

            return ($fullAnswer ? $postRes : true);
        }

        /**
         * Encode and decode array for hiding parameters from server
         *
         * @param $data
         * @param string $mode
         * @return array|mixed|string|void
         */
        public static function convertString($data, $mode = ''){
            $dataAnswer = array();

            if( empty($data) ){
                return $dataAnswer;
            }

            if($mode == 'decode'){
                $dataAnswer = @urldecode($data);
                $dataAnswer = @base64_decode($dataAnswer);
                $dataAnswer = @strrev($dataAnswer);
                $dataAnswer = @base64_decode($dataAnswer);
                $dataAnswer = @json_decode($dataAnswer,true);
            }

            if($mode == 'encode'){
                $dataAnswer = @json_encode($data);
                $dataAnswer = @base64_encode($dataAnswer);
                $dataAnswer = @strrev($dataAnswer);
                $dataAnswer = @base64_encode($dataAnswer);
                $dataAnswer = @urldecode($dataAnswer);
            }

            return $dataAnswer;
        }

        /**
         * Print answer of action
         * exit at the end
         * @param int $readyAnswer
         * @param array $data
         */
        public static function printAnswer($readyAnswer = 0, $data = array()){
            $answer = array(
                'msg_code'  => $readyAnswer,
                'msg'       => self::$requestAnswers[$readyAnswer]['msg'],
                'status'    => self::$requestAnswers[$readyAnswer]['status'],
                'data'      => $data,
            );
            $answer = self::convertString($answer, 'encode');
            print '<'.self::$tagAnswer.'>'.$answer.'</'.self::$tagAnswer.'>';

            exit;
        }

        /**
         * Connect plugin
         */
        protected static function action_connect(){
            $personalKey = md5(time().rand(1,10000).microtime().rand(1,10000));
            if( !update_option(self::$optionKey, $personalKey) ){
                self::printAnswer(2);
            }

            //send personal key to admin
            $requestVars = array(
                'body' => array(
                    self::$tagAnswer => self::convertString(array(
                        'action'    => 'connect',
                        'key'       => $personalKey,
                        'site'      => site_url(),
                        'pl'        => Chats::$plugin_name,
                        'pl_v'      => Chats::$plugin_version
                    ),'encode')
                )
            );
            $requestRes = self::requestServer($requestVars);

            if($requestRes){
                self::printAnswer(1);
            }else{
                self::printAnswer(2);
            }
        }

        /**
         * Read message of user by hash
         */
        protected static function action_read($data){
            $user_hash  = @$data['hash'];
            $user_ip    = @$data['ip'];
            $messages = Chats::read_messages($user_hash, $user_ip, '', 0, array('queryMode' => 'api_read'));
            self::printAnswer(0, array('messages' => $messages));
        }

        /**
         * Add message for user by hash
         */
        protected static function action_write($data){
            $user_hash      = @(string)$data['hash'];
            $user_ip        = @(string)$data['ip'];
            $message        = @(string)$data['message'];
            $created        = @(string)$data['created'];
            if( Chats::add_messages($user_hash, $user_ip, '', $message, $created, 1) ){
                self::printAnswer(3);
            }else{
                self::printAnswer(4);
            }
        }

        /**
         * Update ping plugin
         */
        protected static function action_ping(){
            self::printAnswer(5, array('plugin_version' => Chats::$plugin_version));
        }

        /**
         * Update settings plugin
         */
        protected static function action_update_options($data){
            $options = @(array)json_decode($data['options'],1);
            unset($options['personal_key']);
            if( !empty($options) ){
                $options = Chats::validate_settings($options, 0);
            }
            if( !empty($options) ){
                Chats::plugin_options('add', $options);
                self::printAnswer(9);
            }

            self::printAnswer(10);
        }

        /**
         * Read settings plugin
         */
        protected static function action_read_options(){

        }

        /**
         * Finish chat with user. Chat will be closed and hash changed
         */
        protected static function action_finish_chat(){

        }
    }
}

//run always for checking incoming request
add_action( 'init', array('ChatsAction', 'init') );

//init plugin on frontend
add_action( 'wp_head', array('Chats', 'wp_head') );
add_action( 'wp_footer', array('Chats', 'wp_footer') );

//init plugin on admin
add_action('admin_init', array( 'Chats', 'admin_init') );
add_action('admin_menu', array('Chats', 'admin_menu') );
add_action('admin_post_chats_auth_page', array( 'Chats', 'chats_auth_page') );

add_action( 'admin_bar_menu', array('Chats', 'admin_bar') ,211 );

add_action( 'admin_head', array('Chats', 'includeCss' ) );
add_action( 'wp_head', array('Chats', 'includeCss' ) );

// notice
add_action('admin_notices', array('Chats', 'notice5') );
add_action('admin_post_chats_hide_notice', array( 'Chats', 'hide_notice') );

//listen incoming request from js
add_action( 'wp_ajax_jsChatsProcess', array( 'Chats', 'js_process' ) );
add_action( 'wp_ajax_nopriv_jsChatsProcess', array( 'Chats', 'js_process' ) );

//manipulation by environments for plugin
register_activation_hook( __FILE__, array( 'Chats', 'install' ) );
register_deactivation_hook( __FILE__, array( 'Chats', 'deactivation' ) );
register_uninstall_hook( __FILE__, array( 'Chats', 'uninstall' ) );

// form to access post/page
add_action( 'wp_ajax_getFormPostPageCategory', array( 'Chats', 'get_access_post_page_category' ) );

add_action( 'phpmailer_init', 'chats_phpmailer' );

function chats_phpmailer( $phpmailer ) {
    $options        = Chats::plugin_options( 'get' );
    if (isset($options['mail_with']) && $options['mail_with'] == 2) {
        if (!empty($options['smtp_host']) && !empty($options['smtp_port']) && !empty($options['smtp_user']) && !empty($options['smtp_password'])) {
            $phpmailer->isSMTP();
            $phpmailer->Host = isset($options['smtp_host']) ? $options['smtp_host'] : ''; ;
            $phpmailer->SMTPAuth = true; // Force it to use Username and Password to authenticate
            $phpmailer->Port = isset($options['smtp_port']) ? $options['smtp_port'] : '';;
            $phpmailer->Username = isset($options['smtp_user']) ? $options['smtp_user'] : '';
            $phpmailer->Password = isset($options['smtp_password']) ? $options['smtp_password'] : '';;
            $phpmailer->SMTPSecure = 'ssl'; 
        }
    }
    // for debug
    //$phpmailer->SMTPDebug = 2;
    //$phpmailer->debug     = 1;
    
}