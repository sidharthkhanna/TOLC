<?php
class Front_Utility
{
    public static $textdomain = 'custom-registration-form-builder-with-submission-manager';
    public static function set_otp()
    {
        global $wpdb;
        $basic_options= new crf_basic_options();
        $response= new stdClass();
        $response->error = false;
        $response->show = "#crf_otp_kcontact";
        $response->hide = "#crf_otp_kcontact";
        $response->reload = false;
        if(isset($_POST['crf_otp_email']))
            $email = $_POST['crf_otp_email'];
        if(isset($_POST['crf_otp_key']))
            $key = $_POST['crf_otp_key'];
        // Validate request parameters
        if(!isset($_POST['security_key']) ) {
            // Validate key
            if(isset($key)) {
                $sql = $wpdb->prepare("select * from " . $wpdb->prefix . "crf_users where otp_code=%s", array($key));
                $crf_user = $wpdb->get_row($sql);
                if(empty($crf_user))
                {
                    $response->error = true;
                    $response->msg = __('The OTP you entered is invalid. Please enter correct OTP code from the email we sent you, or you can generate a new OTP.', self::$textdomain);
                }else{
                   
                    self::set_auth_params($key,$crf_user->email);
                    $response->error= false;
                    $response->msg= __('You have successfully logged in using OTP.', self::$textdomain);
                    $response->reload= true;
                }
            }else{
                // Validate email
                if(is_email( $email)){
                    if(self::is_user($email)){
                        $basic_options->crf_generate_otp($_POST['crf_otp_email']);
                        $response->msg= __('Success! an email with one time password (OTP) was sent to your email address.', self::$textdomain);
                    }else{
                        $response->error= true;
                        $response->msg= __('Oops! We could not find this email address in our submissions database.', self::$textdomain);
                    }
                }
                else{
                    $response->error= true;
                    $response->msg= __('Invalid email format. Please correct and try again.', self::$textdomain);
                }
            }
        }
        echo json_encode($response);
        exit;
    }
    // do something every hour
    public static function  cron() {
        global $wpdb;
        // Delete OTP codes after an hour
        $wpdb->query("DELETE FROM ".$wpdb->prefix."crf_users WHERE last_activity_time < DATE_SUB(NOW(), INTERVAL 1 HOUR)");
    }
    // Check if user exists
    public static function is_user($email) {
        global $wpdb;
        $submissions = self::get_submissions_by_email($email);
        if(count($submissions))
            return true;
        else
            return false;
    }
    public static function get_all_forms($columns=array("*"))
    {
        global $wpdb;
        $crf_forms =$wpdb->prefix."crf_forms";
        $c_names= implode(',',$columns);
        $qry = "select $c_names from $crf_forms";
        $forms = $wpdb->get_results($qry);
        return $forms;
    }
    public static function get_submissions_by_email($email='')
    {
        global $wpdb;
        global $current_user;
        $submission_rows = array();
         if(empty($email)){
            if(isset($_COOKIE['crf_autorized_email']))
                $email = $_COOKIE['crf_autorized_email'];
            get_currentuserinfo(); 
              
            if(!empty($current_user->user_email))
            {
               $email= $current_user->user_email;
            }
            
        }
        $forms = self::get_all_forms(array('id'));
        $crf_submissions =$wpdb->prefix."crf_submissions";
        $submission_ids= array();
        foreach($forms as $form){
           $fields= self::get_fields_by_type(array("id","form_id","name"),array('email'),$form->id);
           if(count($fields) === 0){
               $qry = "select * from $crf_submissions where form_id =".$form->id." and field = 'user_email' and TRIM(value)='".$email."'";
               $submissions = $wpdb->get_results($qry);
               if(!empty($submissions)){
                   foreach($submissions as $submission){
                       $submissionObj= new stdClass();
                       $submissionObj->submission_id= $submission->submission_id;
                       $submissionObj->form_id= $submission->form_id;
                       $submission_rows[]= $submissionObj;
                   }
               }
           }
           else{
               foreach($fields as $field){
                   $qry = "select * from $crf_submissions where form_id =".$form->id." and (field='".sanitize_key($field->name."_".$field->id)."' OR field= 'user_email') and TRIM(value)='".$email."'";
                   $submissions = $wpdb->get_results($qry);
                   $submission_check = array();
                   $i=0;
                   if(!empty($submissions)){
                       foreach($submissions as $submission){
                            if(!in_array($submission->submission_id, $submission_check)){
                               $submissionObj= new stdClass();
                               $submissionObj->submission_id= $submission->submission_id;
                               $submissionObj->form_id= $submission->form_id;
                               $submission_rows[]= $submissionObj;
                               $submission_check[$i++] = $submission->submission_id;
                           }
                       }
                   }
               }
            }
        }
        return ($submission_rows);
    }
    // Get all visible fields
    public static function get_fields_by_type($columns= array("*"),$types= array(),$form_id,$cast="%s")
    {
        global $wpdb;
        $crf_fields =$wpdb->prefix."crf_fields";
        $c_names= implode(',',$columns);
        $type_names=implode(",",$types);
        $qry = "select $c_names from $crf_fields where form_id ='%d' and type in($cast)";
        $sql = $wpdb->prepare($qry, array($form_id,$type_names));
        $fields = $wpdb->get_results($sql);
        return $fields;
    }
    public static function set_auth_params($key,$email)
    {     
       
        setcookie("crf_secure_otp", $key, time() + (3600), "/");
        setcookie("crf_autorized_otp", "true", time() + (3600), "/");
        setcookie("crf_autorized_email", $email, time() + (3600), "/");
       
    }
    public static function is_authorized()
    {
        global $wpdb;
        if(!is_user_logged_in() && isset($_COOKIE['crf_secure_otp'])){
            $wpdb->query("DELETE FROM ".$wpdb->prefix."crf_users WHERE last_activity_time < DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
            $sql = $wpdb->prepare("select * from " . $wpdb->prefix . "crf_users where otp_code=%s", array($_COOKIE['crf_secure_otp']));
            $crf_user = $wpdb->get_row($sql);
            if(empty($crf_user)) return false;
            else {
                $wpdb->query("UPDATE ".$wpdb->prefix."crf_users set `last_activity_time`= CURRENT_TIMESTAMP");
                    return true;
                }
            }
        elseif(is_user_logged_in()){
            global $current_user;
            get_currentuserinfo();
            //$email = $current_user->user_email;           
          //  setcookie("crf_autorized_email", $email, time() + (3600), "/");
            return true;
        }
        return false;
        
    }
    public static function get_form_by_submission($submission)
    {
        global $wpdb;
        $crf_forms =$wpdb->prefix."crf_forms";
        $crf_submissions =$wpdb->prefix."crf_submissions";
        $sql= "select forms.id as form_id,forms.form_name from $crf_forms forms inner join $crf_submissions submissions on forms.id=submissions.form_id".
              " and submissions.submission_id=$submission";
        $form= $wpdb->get_row($sql);
        $form->creation_date = '';
        $sql = "select `value` as creation_date from $crf_submissions where `form_id` = ".$form->form_id." and `submission_id`=".$submission." and `field`='entry_time'";
        $date = $wpdb->get_row($sql);
        if($date){
            $form->creation_date = $date->creation_date;
        }
        return $form;
    }
    public static function timestamp_to_date($stamp)
    {   
        if(empty($stamp)){
            return NULL;
        }
        return date('m/d/Y',$stamp);
    }
    public static function submission_details($submission_id)
    {
        global $wpdb;
        $crf_fields =$wpdb->prefix."crf_fields";
        $qry1 = "select * from $crf_fields where Form_Id= '".$entry->form_id."' and Type not in('heading','paragraph','file') order by ordering asc";
        $reg1 = $wpdb->get_results($qry1);
    }
    

/********************functions added later ******************************/


/**
 * function to store comments on view_submissions_details.php in crf_comments table
 *   
 * ajax reponse function to the action wp_ajax_nopriv_crf_store_comment
 *  
 * @return submitted comment as response 
 */
    public static function crf_store_comment(){
        global $wpdb;
        global $current_user; 
        if(isset($_COOKIE['crf_autorized_email']))
            $email = $_COOKIE['crf_autorized_email'];
        get_currentuserinfo();   
        if(!empty($current_user->user_email))
        {
           $email= $current_user->user_email;
        }
       
        $admin_email = get_option('admin_email');
        $f_id = $_POST['f_id'];
        $s_id = $_POST['s_id'];
        $results = false;
        $comment_entered = $_POST['comment'];
        $attatchment_id = 0;
        $user_type = 'front';
        $extra_option = array('attatchment_id' => intval($attatchment_id));
        if(!empty($comment_entered))
            $results = $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."crf_notes (`useremail`,`submission_id`,`notes`,`extra_option`,`publish_date`,`type`) value (%s,%d,%s,%s,NOW(),%s)",$email,$s_id,$comment_entered,maybe_serialize($extra_option),$user_type));
            if(!$results){
                $wpdb->show_errors();
            
            }
        
        else{
            if(!empty($comment_entered)){
                $message = "User added a Message to the form. \n' ".$comment_entered."'.";
                $headers = 'From: '.get_bloginfo().' <'.$email.'>' . "\r\n"; 
                wp_mail( $admin_email, 'Message on submission',$message , $headers);
                $comments = $wpdb->get_row($wpdb->prepare("SELECT `publish_date`,`id` FROM ".$wpdb->prefix."crf_notes WHERE `useremail` = %s and `submission_id` = %d and `notes` = %s",$email,$s_id,$comment_entered));
    
                    $publish_date = substr($comments->publish_date, 0,10);
                    $publish_date = str_replace('-', '/', $publish_date);
                    //echo $wpdb->prepare("INSERT INTO ".$wpdb->prefix."crf_notes (`useremail`,`submission_id`,`notes`,`extra_option`,`publish_date`,`type`) value (%s,%d,%s,%s,NOW(),%s)",$email,$s_id,$comment,maybe_serialize($extra_option),$front);die;?>
      <div class="crf_f_row" id="crf_f_comment_<?php echo $comments->id;?>"><div class="crf_f_column crf_f_comment" > <?php echo $comment_entered;?> </div>
                        <div class="crf_f_column4"><?php echo $publish_date;?></div>
                        <div class="crf_f_column4">
                            <div class="crf_f_delete" onclick="crf_f_delete_comment(<?php echo $comments->id;?>)"><a title="<?php _e('Delete Message', self::$textdomain);?>" href="javascript:void(0)"><img class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'../images/crf-f-delete.png';?>" alt="<?php _e('Delete Message', self::$textdomain);?>"></a></div>
                        </div>
                    </div>
    <?php } 
        }
        die;
    }
/**
 * function to upload attatchment in wordpress
 *
 * ajax response function wp_ajax_nopriv_upload_crf_image
 *  
 * @return Id of the attatchment uploaded 
 */
    public static function upload_crf_image(){       
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $file_name = $_POST['file_name'];
        $extension = explode('.', $file_name);
        $extension = $extension[count($extension)-1];
        $extension = strtolower($extension);
        $accepted = array('jpg','jpeg','png','pdf','doc','docx','gif','bmp','xml','xslx','csv','numbers','rtf','txt','xls');
        if(in_array($extension,$accepted)){
            if(basename($_POST['file_name']))
            $attatchment_id = media_handle_upload( 'file', 0 );
        }
        else{
            $attatchment_id = false;
        }
        echo $attatchment_id;die;
    }
/**
 * function to delete comments on view_submissions_details.php in crf_comments table
 *   
 * ajax reponse function to the action wp_ajax_nopriv_delete_comment_f
 *  
 * @return null 
 */
    public static function delete_comment_f(){
        global $wpdb;
        $id = $_POST['id'];
        $option = $wpdb->get_var("SELECT `extra_option` FROM ".$wpdb->prefix."crf_notes WHERE `id` = ".$id);
        $option = maybe_unserialize($option);
        $att_id = $option['attatchment_id'];
        
        $wpdb->query("DELETE FROM ".$wpdb->prefix."crf_notes WHERE `id` = ".$id);
        //Delete attchement as well
        if($att_id!=0)
        wp_delete_attachment( $att_id, true );
        ///////////////////////////
        die;
    }
/**
 * function to get attatchment by the attatchment id
 *
 * @param $id   int     id of the attatchment
 *
 * @param $get_image    bool    if set false only returns the link to the attatchment 
 *  
 * @return link to the attatchment if not an image or second parameter is set to false else return the attatched image 
 */
    public static function get_attatchment($id,$get_image = true){
        if($get_image){
        $temp = wp_get_attachment_image($id, 'thumb_crf');
        if($temp === ""){
          $attatchment =  "<a href='".wp_get_attachment_url( $id )."'>".basename(wp_get_attachment_url( $id ))."</a>";
       }
        else{
            $attatchment = "<a href='".wp_get_attachment_url( $id )."'><img alt='' class='crf_f_img' src='".wp_get_attachment_url( $id )."'></a>";
       }
    }
    else {
        $attatchment =  "<a href='".wp_get_attachment_url( $id )."'>".basename(wp_get_attachment_url( $id ))."</a>";
        }
        return $attatchment;
    }
/**
 * function to store attatchments on view_submissions_details.php in crf_comments table
 *   
 * ajax reponse function to the action wp_ajax_nopriv_store_crf_attatchment
 *  
 * @return attatchment
 */
    public static function store_crf_attatchment(){
        global $wpdb;
        global $current_user; 
        if(isset($_COOKIE['crf_autorized_email']))
            $email = $_COOKIE['crf_autorized_email'];
        get_currentuserinfo();   
        if(!empty($current_user->user_email))
        {
           $email= $current_user->user_email;
        }
        $admin_email = get_option('admin_email');
        $f_id = $_POST['f_id'];
        $s_id = $_POST['s_id'];
        $comment = '';
        $user_type = 'front';
        $attatchment_id = $_POST['attatchment_id'];
        $option = array('attatchment_id' => intval($attatchment_id));
        $results = $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."crf_notes (`useremail`,`submission_id`,`notes`,`extra_option`,`publish_date`,`type`) value (%s,%d,%s,%s,NOW(),%s)",$email,$s_id,$comment,maybe_serialize($option),$user_type));
        if(!$results){
            $wpdb->show_errors();
        
        }
        else{
        $attatchment_url = array(get_attached_file(intval($attatchment_id)));
        $message = "User added an attatchment to the form.";
        $headers = 'From: '.get_bloginfo().' <'.$email.'>' . "\r\n";
        wp_mail( $admin_email, 'Attatchment',$message , $headers, $attatchment_url);
        $attatchment = $wpdb->get_row($wpdb->prepare("SELECT `extra_option`,`publish_date`,`id` FROM ".$wpdb->prefix."crf_notes WHERE `useremail` = %s and `submission_id` = %d and `extra_option` = %s and `type` = %s ",$email,$s_id,maybe_serialize($option),$user_type));
        
        $publish_date = substr($attatchment->publish_date, 0,10);
        $publish_date = str_replace('-', '/', $publish_date);
       ?>
    <div class="crf_f_row" id="crf_f_comment_<?php echo $attatchment->id;?>"><div class="crf_f_column crf_f_attatchment" > <?php echo self::get_attatchment(intval($attatchment_id));?> </div>
                        <div class="crf_f_column4"><?php echo $publish_date;?></div>
                        <div class="crf_f_column4">
                            <div class="crf_f_delete"><a href="javascript:void(0)" title="<?php _e('Delete Attchement', self::$textdomain);?>" onclick="crf_f_delete_comment(<?php echo $attatchment->id;?>)"><img class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'../images/crf-f-delete.png';?>" alt="<?php _e('Delete Attchement', self::$textdomain);?>"></a></div>
                        </div>
                    </div>
    <?php 
        }
        die;
    }
/**
 * function to logout from the front end interface of the plugin
 *   
 * ajax reponse function to the action wp_ajax_nopriv_crf_f_logout 
 *  
 * @return null
 */
    public static function  logout() {
        
        global $wpdb;
        // Delete OTP codes after an hour
        $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."crf_users WHERE `email` = %s",$_COOKIE['crf_autorized_email']));
    }
/**
 * function to lcreate a new page to view submissions on front end 
 *   
 * hooked to plugin activation 
 *  
 * @return null
 */
    public static function create_page(){
        global $wpdb;
        $submission_page = array(
            'post_type' => 'page',
            'post_title' => 'Submissions',
            'post_status' => 'publish',
            'post_name' =>  'crf_submissions',
            'post_content' => '[CRF_Submissions]'
        );
        $page_id = get_option('crf_f_sub_page_id');
        if($page_id){
            $post = $wpdb->get_var("SELECT `ID` FROM  `".$wpdb->prefix."posts` WHERE  `post_content` LIKE  \"%[CRF_Submissions]%\" AND `post_status`='publish' AND `ID` = ".$page_id);
        }
        else{
            $post = $wpdb->get_var("SELECT `ID` FROM  `".$wpdb->prefix."posts` WHERE  `post_content` LIKE  \"%[CRF_Submissions]%\" AND `post_status`='publish'");
        }
        //var_dump($post);die;
        if(!$post){
            $page_id = wp_insert_post($submission_page);
            update_option( 'crf_f_sub_page_id', $page_id);
        }
        else{
            update_option( 'crf_f_sub_page_id', $post);
        }
        
    }
        
}
