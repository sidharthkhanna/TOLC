<?php
if(isset($_POST['form_id']) && isset($_POST['submission_id']) && isset($_POST['print_pdf'])){
global $wpdb;
global $current_user;
$s_id = $_POST['submission_id'];
$f_id = $_POST['form_id'];
include(ABSPATH.'wp-includes/pluggable.php');
include_once(plugin_dir_path(__FILE__)."classes/class_front_utility.php");
include_once(plugin_dir_path(__FILE__)."classes/class_basic_options.php");
$basic_options = new crf_basic_options;
$from_email = $basic_options->crf_get_from_email();
$form = Front_Utility::get_form_by_submission($s_id);
$form_name = strtolower(str_replace(' ', '_', $form->form_name));
$pdf_name = $form_name.".pdf";
$results = $wpdb->get_results("select `field`,`value` FROM `".$wpdb->prefix."crf_submissions` where form_id = '".$f_id."' and submission_id = '".$s_id."'");
$content = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
<link rel="stylesheet" type="text/css" href="'.plugin_dir_url(__FILE__).'css/crf_front.css">
<style>
  body { font-family:dejavusans;}
  .crf_f_msg{text-align: center;width:50%}
</style>
</head>
<body>';
require_once("dompdf/dompdf_config.inc.php");
$content .= '<div id="crf_submission_container" class="crf_containers">';
$content .= '<h2 class = "crf_f_title">'.ucwords($form->form_name).'<br/></h2><hr/>';
 
        foreach($results as $result){
           $field = $result->field;
           $value = $result->value;
           $field_name = '';
           $remove = array('entry_time','form_type','user_approval','User_IP','Browser','token','user_pass');
           $nochange = array('invoice','payment_status');
           if(!in_array($field, $remove)){
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
            if(!empty($field_name)){
              if(is_array($value)){
                $content .= '<div class="crf_f_row"><div class="crf_f_label">'.$field_name.' : </div><div class="crf_f_column">';
                
                foreach($value as $value_){
                    $content .= $value_.'<br/>';
                }
                $content .= '</div></div>';
            }
            else
              $content .='<div class="crf_f_row"><div class="crf_f_label">'.$field_name.' : </div><div class="crf_f_column">'.$value.'</div></div>';
            $field_name = '';
          } 
        }
    }
        $content .= '</div>';
$content .='</body></html>';
//echo $content;die;
//$content = ' <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
  
  //$string = mb_convert_encoding('', 'HTML-ENTITIES', 'UTF-8');
  $dompdf = new DOMPDF();
  $dompdf->load_html($content);
  //	$dompdf->set_paper('a4', 'portrait');
  $dompdf->render();
  if($_POST['print_pdf'] === 'download'){
    $dompdf->stream($pdf_name);
    die;
  }
  else{
    $output = $dompdf->output();
    if(isset($_COOKIE['crf_autorized_email']))
        $email = $_COOKIE['crf_autorized_email'];
    get_currentuserinfo();   
    if(!empty($current_user->user_email))
    {
       $email= $current_user->user_email;
    }
    $headers = 'From: '.$from_email. "\r\n";
    $message = __('As requested, a PDF copy of your submission is attached',Front_Utility::$textdomain);
    $filename = wp_generate_password( 15, false);
    file_put_contents(plugin_dir_path(__FILE__).'temp_pdf/'.$filename.'.pdf', $output);
    $attatchment= array(plugin_dir_path(__FILE__).'temp_pdf/'.$filename.'.pdf');
	$subject = __('Submission PDF',Front_Utility::$textdomain);
    if(wp_mail( $email,$subject,$message , $headers, $attatchment)){
      $crf_f_notification= __('Email sent successfully.', Front_Utility::$textdomain);
      unlink(plugin_dir_path(__FILE__).'temp_pdf/'.$filename.'.pdf');
    }
    else
      $crf_f_notification= __('Email could not be sent.', Front_Utility::$textdomain);
    
  }
  
}
?>