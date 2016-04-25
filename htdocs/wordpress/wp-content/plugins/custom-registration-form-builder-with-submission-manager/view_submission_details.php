    <?php
/**
 * To output submission details on the html page this file is used 
 * 
 * provides the submission details of the logged in user for a perticular form on the front end.
 * 
 * also provides comment system.
 */
global $crf_f_notification;
global $current_user;
if(isset($_POST['form_id']) && isset($_POST['submission_id'])){
        global $wpdb;
        $field_obj = array();
        $user_type = 'front';
        $user_type_admin = 'user';
        $f_id = $_POST['form_id'];
        $s_id = $_POST['submission_id'];
        /*array containing the names of the fields to exclude*/
        $remove = array('entry_time','form_type','user_approval','User_IP','Browser','token','user_pass','pass_encrypt','user_id');
        $nochange = array('invoice','payment_status');
        /*array of the database details of individual submission*/
        $results = $wpdb->get_results("select `field`,`value` FROM `".$wpdb->prefix."crf_submissions` where form_id = '".$f_id."' and submission_id = '".$s_id."'");
        $post = $wpdb->get_row("SELECT `ID` FROM  `".$wpdb->prefix."posts` WHERE  `post_content` LIKE  \"%[CRF_Form id='".$f_id."']%\" AND `post_status`='publish'");
        $form = Front_Utility::get_form_by_submission($s_id);
        $form_name = ucwords($form->form_name);
        if(isset($_COOKIE['crf_autorized_email']))
            $email = $_COOKIE['crf_autorized_email'];
        get_currentuserinfo(); 
        if(!empty($current_user->user_email))
        {
           $email= $current_user->user_email;
        }
        if(!empty($crf_f_notification)){
           echo'<div class="crf_f_notification" id="crf_f_mail_notification">'.$crf_f_notification.'</div>';
     }
    ?>
        <div id="crf_submission_container" class="crf_containers"><!--container of the entire submission details-->
        <div class="crf_f_row"><a href = "javascript:void(0)" onclick="window.location.href='<?php echo get_permalink(get_option('crf_f_sub_page_id'));?>'"><?php _e('Back', Front_Utility::$textdomain);?></a></div>
        <div id="crf_submission_top"><h2 class = "crf_f_title"><?php echo $form_name; ?><br/></h2>
                <div id="crf_f_pdf_response"><!--print as pdf button-->
                    <form id="crf_f_form" action=""  method="post">
                        <input type="hidden" id="form_id" name="form_id" value="<?php echo $f_id?>" />
                        <input type="hidden" id="submission_id" name="submission_id" value="<?php echo $s_id; ?>"/>
                        <input type="hidden" id="print_pdf" name="print_pdf" value="download"/>
                        <input title="<?php _e('Download as PDF', Front_Utility::$textdomain);?>" type="image" class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf-f-print.png';?>" alt="<?php _e('Download as PDF', Front_Utility::$textdomain);?>" /> 
                    </form>  
                </div>
                 <div id="crf_f_pdf_response"><!--email pdf button-->
                    <form id="crf_f_form" action="" method="post">
                        <input type="hidden" id="form_id" name="form_id" value="<?php echo $f_id?>" />
                        <input type="hidden" id="submission_id" name="submission_id" value="<?php echo $s_id; ?>"/>
                        <input type="hidden" id="print_pdf" name="print_pdf" value="email"/> 
                        <input title="<?php _e('Email Submission', Front_Utility::$textdomain);?>" type="image" class="crf_f_icon"  src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf-f-email.png';?>" alt="<?php _e('Email Submission', Front_Utility::$textdomain);?>" onclick="emailpdf()"/>
                    </form>  
                </div>               
                
                <div class="crf_f_hr_div"><hr class="crf_f_hr"></hr></div>
            </div>
                
                <?php
        
        $i = 0;
        foreach($results as $result){
           $field = $result->field;
           $value = $result->value;
           $field_name = '';
           if(!in_array($field, $remove)){
                if(!empty($field) && !empty($value)){
                    $field_obj[$i] = new stdClass();
                    $field_obj[$i]->field = $field;
                    $field_obj[$i]->value = $value;
                    $i++;
                }
                
                $a = explode("_", $field);
                if((is_numeric($a[count($a)-1])) && (!in_array($field, $nochange))){
                    $field_detail = $wpdb->get_results("select `Name`,`Type` FROM `".$wpdb->prefix."crf_fields` where form_id = '".$f_id."' and `id` = '".$a[count($a)-1]."'");
                if($field_detail){
                        $field_name = $field_detail[0]->Name;
                    if($field_detail[0]->Type === "file"){
                        if(strstr($value,',')){
                            $ids = explode(',', $value);
                            $value = array();
                            foreach ($ids as $key => $id){
                                
                                $value[$key] = Front_Utility::get_attatchment($id);
                            }
                        }
                        else
                            
                            $value = Front_Utility::get_attatchment($value);
                     }
                 }
            }
            elseif(in_array($field, $nochange))
                $field_name = $field;
            else{
                $field_detail = $wpdb->get_row("select `Name` FROM `".$wpdb->prefix."crf_fields` where form_id = '".$f_id."' and `Type` = '".$field."'");
                if($field_detail)
                    $field_name = $field_detail->Name;
                else
                    $field_name = $field;
            }
            /*row of individual field */
        if(!empty($field_name))
            if(is_array($value)){
                echo'<div class="crf_f_row"><div class="crf_f_label">'.$field_name.' : </div><div class="crf_f_column">';
                
                foreach($value as $value_){
                    echo $value_.'<br/>';
                }
                echo'</div></div>';
            }
            else
                echo'<div class="crf_f_row"><div class="crf_f_label">'.$field_name.' : </div><div class="crf_f_column">'.$value.'</div></div>';
            $field_name = '';
     
        
        }
    }
        $field_data = json_encode($field_obj);
        $comments = $wpdb->get_results($wpdb->prepare("SELECT `notes`,`publish_date`,`id` FROM ".$wpdb->prefix."crf_notes WHERE `useremail` = %s and `submission_id` = %d and `type` = %s",$email,$s_id,$user_type));
        $admin_notes = $wpdb->get_results($wpdb->prepare("SELECT `notes`,`publish_date`,`id` FROM ".$wpdb->prefix."crf_notes WHERE `submission_id` = %d and `type` = %s",$s_id,$user_type_admin));?>   
        <!--Additional details on the form-->
        <!--css class for active tab 'crf_f_tab_active'-->
        <!--css class for inactive and hovering tab 'crf_f_tab_inactive' and 'crf_f_tab_inactive'-->
        <div id="crf_f_row_title">
            <div id="crf_f_tabs">
                <div id="tab_titles"><!--tabs to toggle between submission and payment-->
                    <!--div id="crf_f_head1" class="crf_f_tab"><!--Tabs-->
                        <!--div class="crf_tabHeadTitle">
                          <?php _e('My Messages', Front_Utility::$textdomain);?>
                        </div>      
                    </div-->
                    <div id="crf_f_head1" class="crf_f_tab">
                        <div class="crf_tabHeadTitle">
                          <?php _e('Admin Messages', Front_Utility::$textdomain);?>
                        </div>      
                    </div>
                    <!--div id="crf_f_head3" class="crf_f_tab">
                        <div class="crf_tabHeadTitle">
                          <?php _e(/*'Attachments', Front_Utility::$textdomain*/);?>
                        </div>      
                    </div-->
                </div>
            <!--div id="crf_f_resubmit"><!--resubmit button-->
                <!--form id="crf_f_form" action="<?php echo get_permalink($post->ID)?>"  method="post">
                    <input type="hidden" id="form_id" name="form_id" value="<?php echo $f_id?>" />
                    <input type="hidden" id="submission_id" name="submission_id" value="<?php echo $s_id; ?>"/>
                    <input type="hidden" id="resubmit" name="resubmit" value="true"/>
                    <input type="hidden" id="data" name="field_front_data" value='<?php echo $field_data;?>'/>
                    <input title="<?php _e('Resubmit', Front_Utility::$textdomain);?>" type="image" class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf_f_resubmit.png';?>" alt="<?php _e('Resubmit', Front_Utility::$textdomain);?>" /> 
                </form>  
            </div-->
    </div>
    </div>
                
       <?php /*****      <div class="crf_f_box" id="crf_f_content1"><!--My Notes-->
                <div id="crf_f_my_messages" class="crf_f_notes">
                <?php
 		         if(count($comments)!== 0){
                   foreach($comments as $comment){
                        if(!empty($comment->notes)){
                            $publish_date = substr($comment->publish_date, 0,10);
                            $publish_date = str_replace('-', '/', $publish_date);?>
                 <?php ****           <div class="crf_f_row" id="crf_f_comment_<?php echo $comment->id;?>"><div class="crf_f_column crf_f_comment" > <?php echo $comment->notes;?> </div>
                 <?php ****          <div class="crf_f_column4"><?php echo $publish_date;?></div>
                 <?php ****          <div class="crf_f_column4">
                                <div class="crf_f_delete"><a href="javascript:void(0)" title="<?php _e('Delete Message', Front_Utility::$textdomain);?>" onclick="crf_f_delete_comment(<?php echo $comment->id;?>)"><img class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf-f-delete.png';?>" alt="<?php _e('Delete Message', Front_Utility::$textdomain);?>"></a></div>
                            </div>
                        </div>
                    <?php }
                        }
                    }?>
     <?php ****           </div>
        <div class="crf_f_row">
            <div class="crf_f_message crf_f_column3"><?php _e('ADD NEW NOTE', Front_Utility::$textdomain);?></div><!--Add new note-->
    <?php ****        <div class="crf_f_column3 crf_f_text_center"><img id="crf_f_loading" style="display:none" src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf_f_ajax_loader_wide.gif'; ?>" alt="Loading" ></img></div>
            <!--div class="crf_f_message crf_f_column3"><a href="javascript:void(0)" class="crf_f_upload" onclick="performClick('crf_f_browse','<?php echo $s_id."','".$f_id?>')"><?php _e('ADD FILE', Front_Utility::$textdomain);?></a></div-->
    <?php ****    </div>
        <div class="crf_f_row crf_f_comment_wrapper"><!--comment textarea-->
            <div class="crf_f_row">
            <form id="crf_f_comment" action="javascript:void(0)" method="post">
                <textarea class="crf_comment_text" name="comment" placeholder="<?php _e('Comment', Front_Utility::$textdomain);?>"></textarea>
            </form>
            <form enctype="multipart/form-data" action="javascript:void(0)" method="post"><!--file upload form-->
                    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
                    <input name="uploaded_file" type="file" id="crf_f_browse" onchange="store_crf_attatchment('<?php echo $s_id."','".$f_id?>')"/>
                    
            </form>
            </div>
            <div id="submit_crf_comment"><a href="javascript:void(0)" onclick="store_crf_comment('<?php echo $s_id."','".$f_id?>')"><?php _e('Save', Front_Utility::$textdomain);?></a></div><!--Save Comment-->
    <?php ****    </div>
 		
       </div>*/?>
    <div class="crf_f_box" id="crf_f_content1"><!--Admin Notes-->
<?php            
                if(count($admin_notes)!== 0){?>
                    <?php foreach($admin_notes as $admin_note){
                        if(!empty($admin_note->notes)){
                            $publish_date = substr($admin_note->publish_date, 0,10);
                            $publish_date = str_replace('-', '/', $publish_date);?>
                            <div class="crf_f_row" id="crf_f_comment_<?php echo $admin_note->id;?>"><div class="crf_f_column crf_f_comment" > <?php echo $admin_note->notes;?> </div>
                            <div class="crf_f_column4"><?php echo $publish_date;?></div>
                            <div class="crf_f_column4">
                                <div class="crf_f_delete"><a href="javascript:void(0)" title="<?php _e('Delete Message', Front_Utility::$textdomain);?>" onclick="crf_f_delete_comment(<?php echo $admin_note->id;?>)"><img class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf-f-delete.png';?>" alt="<?php _e('Delete Message', Front_Utility::$textdomain);?>"></a></div>
                            </div>
                        </div>
                    <?php }
                        }
                    }
       ?></div>
<?php  /* $attatchments = $wpdb->get_results($wpdb->prepare("SELECT `extra_option`,`publish_date`,`id` FROM ".$wpdb->prefix."crf_notes WHERE `useremail` = %s and `submission_id` = %d and `notes` = %s and `type` = %s",$email,$s_id,'',$user_type));
        
        ?>
                      <div class="crf_f_box" id="crf_f_content3"><!--Attatchments-->
                <?php
        if(count($attatchments)!== 0){               
                   foreach ($attatchments as $attatchment) {
                        $a = maybe_unserialize($attatchment->extra_option);
                        $attatchment_id = intval($a['attatchment_id']);
                        if($attatchment_id !== 0){
                            $publish_date = substr($attatchment->publish_date, 0,10);
                            $publish_date = str_replace('-', '/', $publish_date);
                    ?>
                        <div class="crf_f_row" id="crf_f_comment_<?php echo $attatchment->id;?>"><div class="crf_f_column crf_f_attatchment" > <?php echo Front_Utility::get_attatchment($attatchment_id);?> </div>
                        <div class="crf_f_column4"><?php echo $publish_date;?></div>
                        <div class="crf_f_column4">
                            <div class="crf_f_delete"><a  title="<?php _e('Delete Attachment', Front_Utility::$textdomain);?>" href="javascript:void(0)" onclick="crf_f_delete_comment(<?php echo $attatchment->id;?>)"><img class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf-f-delete.png';?>" alt="<?php _e('Delete Attachment', Front_Utility::$textdomain);?>"></a></div>
                        </div>
                    </div>
                    <?php 
                        }
                    }
                }
        
       </div>*/?>	
        <script type="text/javascript">var ajax_url = "<?php echo admin_url( 'admin-ajax.php' );?>"</script> 
  </div>
   <?php     
}	
