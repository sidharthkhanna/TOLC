<?php
global $current_user;
$submissions = Front_Utility::get_submissions_by_email();
$basic_options= new crf_basic_options();
if(isset($_COOKIE['crf_autorized_email']))
$email = $_COOKIE['crf_autorized_email'];
get_currentuserinfo();   
if(!empty($current_user->user_email))
{
   $email= $current_user->user_email;
}
    if(empty($submissions)){
?>
     <div id='crf_f_empty'><?php _e('We have no submission records from your email address.',Front_Utility::$textdomain);?></div>
        <?php
    }
    else{ ?>
<div id="crf_f_submissions_container" class="crf_containers">
    <div id="crf_f_tabs">
        <div id="tab_titles"><!--tabs to toggle between submission and payment--><!---->
            <div id="crf_f_head1" class="crf_f_tab">
                <div class="crf_tabHeadTitle">
                  <?php _e('My Submissions', Front_Utility::$textdomain);?>
                </div>      
            </div>
            <div id="crf_f_head2" class="crf_f_tab">
                <div class="crf_tabHeadTitle">
                  <?php _e('Payment History', Front_Utility::$textdomain);?>
                </div>      
            </div>
        </div>
        <!--button to logout-->
        <div id="crf_f_logout"><a href="javascript:void(0)" onclick="crf_f_logout()"><?php _e('LOG OFF', Front_Utility::$textdomain);?></a></div>
    </div>
    <div id="crf_f_content1"><!--all submissions by the user-->
    <div class="crf_f_row_heading">
        <div class="crf_f_column3">
            <b><?php _e('Title', Front_Utility::$textdomain);?></b>
        </div>
        <div class="crf_f_column3">
            <b><?php _e('Date', Front_Utility::$textdomain);?></b>
        </div>
    </div>
       <?php
        
        foreach ($submissions as $submission) {
            $form = Front_Utility::get_form_by_submission($submission->submission_id);
            $form_name = ucwords($form->form_name);
            $pass = wp_generate_password(10,false);
            ?>  
<!--form with submission id and form id -->             
                <form id="crf_f_form_<?php echo $pass; ?>" action="#" method="post" name="<?php echo $pass;?>">
                <input type="hidden" id="form_id" name="form_id" value="<?php echo $submission->form_id?>" />
                <input type="hidden" id="submission_id" name="submission_id" value="<?php echo $submission->submission_id; ?>"/> 
                </form>
<!--individual form block-->
                <div class="crf_f_row">
                    <div class="crf_f_column3">
                        <a href="javascript:void(0)" onclick = "javascript:jQuery('#crf_f_form_<?php echo $pass; ?>').submit()"><?php echo $form_name; ?></a>
                    </div>
                    <div class="crf_f_column3">
                        <?php echo Front_Utility::timestamp_to_date($form->creation_date); ?>
                    </div>
        
                    <div id="crf_f_pdf_response"><!--print pdf button-->
                    <form id="crf_f_form" action="#" method="post">
                        <input type="hidden" id="form_id" name="form_id" value="<?php echo $submission->form_id?>" />
                        <input type="hidden" id="submission_id" name="submission_id" value="<?php echo $submission->submission_id; ?>"/>
                        <input type="hidden" id="print_pdf" name="print_pdf" value="download"/>
                        <input title="<?php _e('Download as PDF', Front_Utility::$textdomain);?>" type="image" class="crf_f_icon" src="<?php echo plugin_dir_url( __FILE__ ) .'images/crf-f-print.png';?>" alt="<?php _e('Download as PDF', Front_Utility::$textdomain);?>" /> 
                    </form>  
                </div>
                </div>
                <?php }
            
            ?>
            <script type="text/javascript">var ajax_url = "<?php echo admin_url( 'admin-ajax.php' );?>"</script>
            
           </div>
            <div class="crf_f_box" id="crf_f_content2"><?php
            foreach ($submissions as $submission){
                $form = Front_Utility::get_form_by_submission($submission->submission_id);
                $form_name = ucwords($form->form_name);
                $payment_details = $basic_options->crf_get_entry_payment_info($submission->submission_id);
                if(!empty($payment_details)){
                    echo '<div id="crf_f_form_name"><b>'.$form_name.'</b></div><hr/>';
                    echo '<div id="payment__detail">'.$payment_details.'</div>';
                }
        }
    
    ?></div>
</div>
    <?php }