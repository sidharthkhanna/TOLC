<?php

/**
 * Plugin Name: Collapsible User Profile Display
 * Plugin URI: http://#
 * Description: This Plugin will help you to display all subsribed users profile in a collapsible format using shoetcode.
 * Version:1.0
 * Author:Subrata Sarker Bappa
 * Author URI:http://bappa.me/
 * License: A "Slug" license name e.g. GPL2
 */
// prevent the direct access
if (!defined('ABSPATH')) {
    exit;
}
require_once dirname( __FILE__ ) . '/class.settings-api.php';
require_once dirname( __FILE__ ) . '/settings_api.php';

  /**
         * Get the value of a settings field
         *
         * @param string $option settings field name
         * @param string $section the section name this field belongs to
         * @param string $default default text if it's not found
         * @return mixed
         */
         function my_get_option( $option, $section, $default = '' ) {
            $options = get_option( $section );
            if ( isset( $options[$option] ) ) {
                return $options[$option];
            }
            return $default;
           }
         $languase =  my_get_option( 'radio', 'cp_basics', $default = '' );


if (!class_exists('CP_User_Profile')) {

    class CP_User_Profile {

        private $db_version = 1.0;

        function __construct() {
 
         // Short Code for subscribers
         add_shortcode('subscribers', array($this, 'cp_subscribers_shortcode'));
         // Short Code for contributor
         add_action('wp_enqueue_scripts', array($this, 'cp_post_embedar_include_css_js'));
         //For For include js/css in backend
         add_action( 'admin_enqueue_scripts', array($this,'cp_admin_include_css_js') );
         // Additional User Field Twitter
         add_action( 'show_user_profile', array($this,'additional_user_fields') );
         add_action( 'edit_user_profile', array($this,'additional_user_fields' ));
         //Update User field for Twitter
         add_action ( 'personal_options_update', array($this,'my_save_extra_profile_fields' ));
         add_action ( 'edit_user_profile_update', array($this,'my_save_extra_profile_fields' ));
         // For adding Profil image uploader 
         add_action( 'show_user_profile', array($this,'cp_profile_img_fields' ));
         add_action( 'edit_user_profile', array($this,'cp_profile_img_fields' ));
         // Save the new user image url.
         add_action( 'personal_options_update', array($this,'cp_save_img_meta' ));
         add_action( 'edit_user_profile_update', array($this,'cp_save_img_meta' ));
         
         // 
//         if ( current_user_can('subscriber') && !current_user_can('upload_files') )
         add_action('admin_init',  array($this,'allow_subscriber_uploads'));
       }
      
            /**
             * Allow subscriber to upload image
             */
         public function allow_subscriber_uploads() {
            $subscriber = get_role('subscriber');
            $subscriber->add_cap('upload_files');
            $subscriber->remove_cap('view_galleries');
            }
            
            /**
             * Include  Javascript and Css in front end.
             */
            
          public function cp_post_embedar_include_css_js(){
                wp_enqueue_style( 'cp_user_profile_style', plugins_url('/assets/accordine_style.css', __FILE__) );
                wp_enqueue_style( 'cp_user_profile_style1', plugins_url('/assets/bootstrap/css/bootstrap-theme.css', __FILE__) );
                wp_enqueue_style( 'cp_user_profile_style2', plugins_url('/assets/bootstrap/css/bootstrap.css', __FILE__) );
                
                wp_enqueue_script('jquery');
//                wp_enqueue_script('cp_user_profile', plugins_url('/assets/accordine.js', __FILE__));
                wp_enqueue_script('cp_user_profile1', plugins_url('/assets/bootstrap/js/bootstrap.js', __FILE__));
                wp_enqueue_media(); // Enqueue the WordPress MEdia Uploader 
            }
            /**
             * Include  Javascript and Css in Back end.
             */
          public function cp_admin_include_css_js(){
                wp_enqueue_script('jquery');
                wp_enqueue_script('cp_user_profile_img_upload', plugins_url('/assets/imgupload.js', __FILE__));
            }
         /*
          * For adding Twitter Field
          */
          public function additional_user_fields( $user ) {
                   require_once (dirname(__FILE__) . '/html/custom_user_field.php');
           } // additional_user_fields
            
            /**
             * Save Extra fields
             * @param type $user_id
             * @return boolean
             */
            public function my_save_extra_profile_fields( $user_id )
                {
                    if ( !current_user_can( 'edit_user', $user_id ) )
                        return false;
                    /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
                    update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
                    //Twitter UserName
                    update_usermeta( $user_id, 'twitter_name', $_POST['twitter_name'] );
                    //Facebook
                    update_usermeta( $user_id, 'facebook', $_POST['facebook'] );
                    //Facebook User Name
                    update_usermeta( $user_id, 'facebook_name', $_POST['facebook_name'] );
                    //LinkIn
                    update_usermeta( $user_id, 'linkIn', $_POST['linkIn'] );
                    //LinkIn User Name
                    update_usermeta( $user_id, 'linkIn_name', $_POST['linkIn_name'] );
                    //occupation
                    update_usermeta( $user_id, 'occupation', $_POST['occupation'] );
                    //company name
                    update_usermeta( $user_id, 'company_name', $_POST['company_name'] );
                    //Education
                    update_usermeta( $user_id, 'education', $_POST['education'] );
                }
               
              /**
               * Image Uploading Field
               * @param type $user
               * @return boolean
               */  
             public function cp_profile_img_fields( $user ) {?>
                   
                <?php if(!current_user_can('upload_files'))
                    return FALSE;

                // vars
                $cp_url             = get_the_author_meta( 'cp_meta', $user->ID );
                $cp_upload_url      = get_the_author_meta( 'cp_upload_meta', $user->ID );
                $cp_upload_edit_url = get_the_author_meta( 'cp_upload_edit_meta', $user->ID );

                if(!$cp_upload_url){
                    $btn_text = 'Upload New Image';
                } else {
                    $cp_upload_edit_url = get_home_url().get_the_author_meta( 'cp_upload_edit_meta', $user->ID );
                    $btn_text = 'Change Current Image';
                }
                require_once (dirname(__FILE__) . '/html/image_uploading_field.php');
               
                }
            /**
             * Save Uploaded Image
             * @param type $user_id
             * @return boolean
             */
            public function cp_save_img_meta( $user_id ) {

                if ( !current_user_can( 'edit_user', $user_id ) )
                    return FALSE;

                // If the current user can edit Users, allow this.
                update_usermeta( $user_id, 'cp_meta',$_POST['cp_meta']);
                update_usermeta( $user_id, 'cp_upload_meta',$_POST['cp_upload_meta'] );
                update_usermeta( $user_id, 'cp_upload_edit_meta', $_POST['cp_upload_edit_meta'] );
            }
         /**
         * Subscriber Shortcode
         */
        public function cp_subscribers_shortcode(){
             $languase = my_get_option( 'radio', 'cp_basics', $default = 'eng' );
             if ($languase == 'eng') {
                 require_once (dirname(__FILE__) . '/template/subscribers_template_2.php');
             }
            else {
                 require_once (dirname(__FILE__) . '/template/subscribers_template_1.php');
             }     
        }
    }      
 }
 new CP_User_Profile();
 ?>

                
    
<?php     