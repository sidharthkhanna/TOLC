<?php
	$userid = 0;
	if($form_type=='reg_form' && $autoapproval=='yes')
	{
		$userid = $form_fields->crf_create_user($_POST,$role);
		$form_fields->crf_insert_user_data($content['id'],$_POST,$userid);
	}
	//Fetches Auto Responder enable or disalbe.
	  $send_email = $form_fields->crf_get_form_option_value('send_email',$content['id']);
	  if($send_email==1)
	  {
		  $form_fields->crf_send_user_email($content['id'],$_POST,$userid,$entry_id);
	  }
	  
	  $enable_mailchimp = $form_fields->crf_get_global_option_value('enable_mailchimp');
	  if($enable_mailchimp=='yes')
	  {
		  /*mailchimp start */
		  $subscriber_email = $form_fields->crf_get_subscriber_email($content['id'],$_POST);
		  $firstname = $form_fields->crf_get_subscriber_other_field($content['id'],$_POST,'mailchimp_firstfield');
		  $lastname = $form_fields->crf_get_subscriber_other_field($content['id'],$_POST,'mailchimp_lastfield');
		  $form_options = $form_fields->crf_get_form_option_value('form_option',$content['id']);
		   $form_option = maybe_unserialize($form_options);
		   $optin_box = $form_option['optin_box'];
		  // echo $optin_box;die;
		   if($optin_box==1)
		   {
			   if(isset($_POST['crf_optin_box']) && $_POST['crf_optin_box']=='yes')
			   {
				 $result = $form_fields->crf_insert_mailchimp($content['id'],$subscriber_email,$firstname,$lastname);
				 //print_r($result);
			   }
		   }
		   else
		   {
			   
			    $result = $form_fields->crf_insert_mailchimp($content['id'],$subscriber_email,$firstname,$lastname);
				//print_r( $result);
		   }
	  }
		//print_r($result);die;
	  
	  /*mailchip end */
	   /*admin notification start */
	   
	   
	  $admin_notification = $form_fields->crf_get_global_option_value('adminnotification');
	  if($admin_notification=='yes')
	  {
		$form_fields->crf_send_admin_notification($entry_id,$content['id']);
	   }
	  /*admin notification end */
	  
	 $form_fields->crf_get_success_message($content['id']);

	$redirect_option = $form_fields->crf_get_form_option_value('redirect_option',$content['id']);
	$url = $form_fields->crf_get_redirect_url($content['id'],$redirect_option);
	
	if($redirect_option!='none')
	{	
		header('refresh: 5; url='.$url);
	}
	
	
	
	  
	 


?>