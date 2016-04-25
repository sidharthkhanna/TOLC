<?php
class crf_basic_options
{
	
		public function crf_null_field_notice() 
		{
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			?>
<div class="notice" style="border-left:4px solid #ffd802">
  <p>
    <?php _e( 'Some of the options below require selecting fields from your form. Since you are creating new form from scratch, there are no fields in this form yet. You can come back later and modify these field specific options. You can safely ignore them for now and save the settings.', $textdomain ); ?>
  </p>
</div>
<?php
		}
		
		public function crf_count_fields($id)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$select = "select count(*) from $crf_fields where Form_Id=".$id;
			$result = $wpdb->get_var($select);
			return $result;	
		}
		
		public function crf_add_option($fieldname,$value)
		{
		  global $wpdb;
		  $crf_option=$wpdb->prefix."crf_option";
		  $update="update $crf_option set `value`='".$value."' where fieldname='".$fieldname."'";
		  $wpdb->query($update);
		}
		
		public function crf_fields_dropdown_options($form_id,$selected)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$select = "select * from $crf_fields where Form_Id=".$form_id;
			$reg = $wpdb->get_results($select);
			echo '<option value="">Insert a Field Value:</option>';
			foreach($reg as $row)
			{
				$key = $this->crf_get_field_key($row);
				?>
<option value="<?php echo $key;?>" <?php selected( $selected, $key ); ?>><?php echo $row->Name?></option>
<?php	
			}
			
		}
		
		public function crf_fields_dropdown_options_for_autoresponder($form_id,$selected)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$select = "select * from $crf_fields where Type not in('heading','paragraph','file') and Form_Id=".$form_id;
			$reg = $wpdb->get_results($select);
			echo '<option value="">Insert a Field Value:</option>';
			foreach($reg as $row)
			{
				$key = $this->crf_get_field_key($row);
				?>
<option value="<?php echo $key;?>" <?php selected( $selected, $key ); ?>><?php echo $row->Name?></option>
<?php	
			}
			
		}
		
		public function crf_paypal_fields_dropdown_options($selected)
		{
			global $wpdb;
			$crf_paypal_fields =$wpdb->prefix."crf_paypal_fields";
			$select = "select * from $crf_paypal_fields";
			$reg = $wpdb->get_results($select);
			echo '<option value="">Insert a Field Value:</option>';
			foreach($reg as $row)
			{
				?>
<option value="<?php echo $row->Id;?>" <?php selected($selected,$row->Id); ?>><?php echo $row->Name;?></option>
<?php	
			}
			
		}
		
		
		public function getInbetweenStrings($start, $end, $str)
		{
				$matches = array();
				$regex = "/$start([a-zA-Z0-9_]*)$end/";
				preg_match_all($regex, $str, $matches);
				return $matches;
		}
		
		public function crf_email_message_html($form_id,$entryid)
		{
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$form_type = $this->crf_get_form_option_value('form_type',$form_id);
			$message = $this->crf_get_form_option_value('crf_welcome_email_message',$form_id);
			$matches = $this->getInbetweenStrings('{{','}}',$message);
			$result = $matches[1];
			foreach($result as $field)
			{
				$search = '{{'.$field.'}}';
				$value = $this->crf_submision_field_value($entryid,$field);
				$message = str_replace($search,$value,$message);
			}
			
			if($message == "" && $form_type=='reg_form')
			{
				$message = __('Thank you for registration.',$textdomain);//Auto inserts this text as email body if it is not defined in dashboard settings
			}
			
			if($message == "" && $form_type=='contact_form')
			{
				$message = __('Thank you for your submission.',$textdomain);//Auto inserts this text as email body if it is not defined in dashboard settings
			}
			
			return $message;
		}
		
		public function add_crf_fields_list() 
		{
			$id = $_REQUEST['id'];
			?>
<select id="autoresponder_fields" onChange="add_field_autoresponder(this.value)">
  <?php $this->crf_fields_dropdown_options_for_autoresponder($id,'');?>
</select>
<?php
		}
		
		public function checkfieldname($fieldname,$value)
		{
			global $wpdb;
			$crf_option=$wpdb->prefix."crf_option";
			$select="select `value` from $crf_option where fieldname='".$fieldname."' and `value`='".$value."'";
			$data = $wpdb->get_var($select);
			
			if($data==$value)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function check_crf_form_expiration($form_option,$id)
		{
			global $wpdb;
			$message = "";
			$crf_submissions=$wpdb->prefix."crf_submissions";
			$auto_expires = @$form_option['auto_expires'];
			$expiry_type = @$form_option['expiry_type'];
			$submission_limit = @$form_option['submission_limit'];
			$expiry_date = @$form_option['expiry_date'];
			$expiry_message = @$form_option['expiry_message'];
			if(isset($auto_expires) && $auto_expires==1) 
			{
				
				if($expiry_type=='submission' || $expiry_type=='both' )
				{
					  $total = $wpdb->get_var( "SELECT count(distinct(submission_id)) FROM $crf_submissions where form_id ='".$id."'" );	
					  if($submission_limit<=$total)
					  {
							$form_expired = 1;  
					  }
				}
				
				if($expiry_type=='date' || $expiry_type=='both')
				{
					$today = date("Y-m-d");	
					$today_time = strtotime($today);
					$expire_time = strtotime($expiry_date);
					
					if ($expire_time < $today_time) 
					{  
						$form_expired = 1;
					}	
				}
				
				if(isset($form_expired) && $form_expired==1)
				{
					$message = '<div id="crf-form">
				  <div id="main-crf-form">
					<div class="main-edit-profile">'. $expiry_message . '<br />
					  <br />
					</div>
				  </div>
				</div>';
				return $message;
				}
				else
				{
					$message ="";
					return $message;
						
				}
			}else{return $message;}
			
		}
		
		public function crf_get_global_option_value($fieldname)
		{
			global $wpdb;
			$crf_option=$wpdb->prefix."crf_option";
			$select="select `value` from $crf_option where fieldname='".$fieldname."'";
			$data = $wpdb->get_var($select);
			return $data;
			
		}
		
		public function crf_get_form_option_value($fieldname,$id)
		{
			global $wpdb;
			$crf_forms=$wpdb->prefix."crf_forms";
			$qry="SELECT $fieldname FROM $crf_forms WHERE id=".$id;
			$value = $wpdb->get_var($qry);
			return $value;
			
		}
		
		public function crf_get_country_name($ip)
		{
			  $location = file_get_contents('http://freegeoip.net/json/'.$ip);
			  $jsondetails    = json_decode($location);
			  $countryname = $jsondetails->country_name;
			  unset($jsondetails);
			  return $countryname;
			  
		}
		
		public function crf_get_browser_name($ExactBrowserNameUA)
		{
			if(strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")) {
					// OPERA
					$ExactBrowserNameBR="Opera";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "chrome/")) {
					// CHROME
					$ExactBrowserNameBR="Chrome";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "msie") || strpos(strtolower($ExactBrowserNameUA), 'trident')) {
					// INTERNET EXPLORER
					$ExactBrowserNameBR="Internet Explorer";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "firefox/" )) {
					// FIREFOX
					$ExactBrowserNameBR="Firefox";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")==false and strpos(strtolower($ExactBrowserNameUA), "chrome/")==false) {
					// SAFARI
					$ExactBrowserNameBR="Safari";
				} else {
					// OUT OF DATA
					$ExactBrowserNameBR="Other";
				};	
				return $ExactBrowserNameBR;
		}
		
		public function crf_get_entry_details($form_id,$id)
		{
			$html = '';
			$html .= $this->crf_get_entry_basic_info($form_id,$id);
	 		$html .=$this->crf_get_entry_custom_fields($form_id,$id);
			//$html .=$this->crf_get_entry_pricing_fields($form_id,$id);
			$html .=$this->crf_get_entry_payment_info($id);
			$html .=$this->crf_get_entry_ip_info($id);
			$html .=$this->crf_get_entry_browser_info($id);
			$html .=$this->crf_get_entry_attachment($form_id,$id);
			return $html;
		}
		
		public function crf_get_entry_notes($submission_id)
		{
			global $wpdb;
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$crf_notes=$wpdb->prefix."crf_notes";
			$path =  plugin_dir_url(__FILE__).'../';
			$qry = "select * from $crf_notes where submission_id='".$submission_id."'";	
			$reg = $wpdb->get_results($qry);
			if(!empty($reg)):
			echo '<div class="crf-signle-entry-form entry" id="crf_submission">
<div class="crf-single-entry-content">
    <div class="entry_html"><h1>Notes</h1>';
			foreach($reg as $row)
			{
				if(!empty($row->userid))
				{
					$createdby = get_user_by( 'id',$row->userid);
					
				}
				else
				{
					$createdby = get_user_by( 'email',$row->useremail);
					
				}
				if(empty($createdby)) 
				{
					$userlogin = $row->useremail;
				}
				else
				{
					$userlogin = $createdby->user_login;	
				}
				if(!empty($row->bg_color))$color = $row->bg_color;else $color = '#f1f1f1';
				?>
<div class="crf_note_wrapper">
  <p class="crf_note" style="background:<?php echo $color;?>; border-color:<?php echo $color;?>;">
    <?php if(!empty($row->notes)) echo '<span class="crf_note_text">'.$row->notes.'</span>';?>
    <?php if(!empty($row->extra_option)): 
				$fileid = maybe_unserialize($row->extra_option);
				$attachment_html = '';
				if(isset($fileid)){
				$attachment_html .= '<span class="attachment_icon crf_attachedfile">'.wp_get_attachment_link($fileid['attatchment_id'],'full',false,true,false).'</span>';
				$attachment_html .='<span class="attachment_title crf_attachedfile">'.get_the_title( $fileid['attatchment_id'] ).'</span>';
				$attachment_html .= '<span class="download_link crf_attachedfile"><a href="'.wp_get_attachment_url( $fileid['attatchment_id'] ).'">'.__('Download',$textdomain).'</a></span>';}
				echo $attachment_html;
				 endif;?>
  </p>
  <p class="crf_about_notes">
    <?php if(!empty($row->publish_date)) echo 'Created by '.$userlogin.' on '.$row->publish_date;
                if(!empty($row->last_edited_by)){
					$editedby = get_userdata($row->last_edited_by);
				echo ' (Edited by '.$editedby->user_login.' on '.$row->last_edit_date.')';
				}
				?>
    <span class="crf_note_button"><a href="admin.php?page=crf_add_notes&submission_id=<?php echo $row->submission_id;?>&id=<?php echo $row->id;?>"><img src="<?php echo $path;?>images/note-edit.png" /></a> <a href="admin.php?page=crf_add_notes&action=delete&submission_id=<?php echo $row->submission_id;?>&id=<?php echo $row->id;?>"><img src="<?php echo $path;?>images/note-delete.png" /></a></span> </p>
</div>
<?php
					
			}
			echo '</div></div></div>';
			endif;
			
			
			
		}
		
		public function crf_get_entry_payment_info($id)
		{
			global $wpdb;
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$crf_paypal_log=$wpdb->prefix."crf_paypal_log";
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "select `value` from $crf_submissions where submission_id='".$id."' and `field`='paypal_log_id'";	
			$logs = $wpdb->get_results($qry);
			$invoice = $this->crf_submision_field_value($id,'invoice');
			$html = '';
			if(!empty($invoice))
			{
				//$logid = $this->crf_submision_field_value($id,'paypal_log_id');	
				$payment_status = $this->crf_submision_field_value($id,'payment_status');	
				if(!empty($logs))
				{
					foreach($logs as $log)
					{
						$logid = $log->value;
						$qry1 = "select log from $crf_paypal_log where id=".$logid;
						$log = maybe_unserialize($wpdb->get_var($qry1));
						$html .= '
						<p><span class="entry_heading">'. __('Payer Name',$textdomain).' : </span><span class="entry_Value" >'. $log['address_name'].'</span></p>
						<p><span class="entry_heading">'. __('Payer Email',$textdomain).' : </span><span class="entry_Value" >'. $log['payer_email'].'</span></p>
						 <p><span class="entry_heading">'. __('Total Amount',$textdomain).' : </span><span class="entry_Value" >'. $log['mc_gross'].'</span></p>
						 <p><span class="entry_heading">'. __('Payment Status',$textdomain).' : </span><span class="entry_Value" >'. $log['payment_status'].'</span></p>
						 <p><span class="entry_heading">'. __('Payment Date',$textdomain).' : </span><span class="entry_Value" >'. $log['payment_date'].'</span></p>
						 <p><span class="entry_heading">'. __('Trasaction Id',$textdomain).' : </span><span class="entry_Value" >'. $log['txn_id'].'</span></p>';
					}
                   	
				}
				else
				{
				$html .= '
                    <p><span class="entry_heading">'. __('Payment Status',$textdomain).' : </span><span class="entry_Value" >'. $payment_status.'</span></p>';
				}
			}
			return $html;
			
			
		}
		public function crf_get_entry_browser_info($id)
		{
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$html = '';
			if($this->crf_submision_field_value($id,'Browser')!=""):
			$browser = $this->crf_submision_field_value($id,'Browser');
			//echo $browser;die;
			$ExactBrowserNameBR = $this->crf_get_browser_name($browser);
			$html = '
	 <p><span class="entry_heading">'. __('Browser',$textdomain).' : </span><span class="entry_Value" >'. $ExactBrowserNameBR. '</span></p>';
			endif;	
			return $html;
			
		}
		public function crf_get_entry_ip_info($id)
		{
			  $textdomain = 'custom-registration-form-builder-with-submission-manager';
			  $User_IP = $this->crf_submision_field_value($id,'User_IP');
			  $user_ip = $this->crf_submision_field_value($id,'user_ip');
			  $html = '';
			  if(isset($user_ip) || $user_ip!="")
			  {
					$ip = $user_ip;  
			  }
			  if(isset($User_IP) || $User_IP!="")
			  {
					$ip = $User_IP;  
			  }
			  
			  if(isset($ip)):
				$html = '
			  <p><span class="entry_heading">'. __('IP',$textdomain).' : </span><span class="entry_Value" >'. $ip. '</span><span class="entry_Value" style="padding-left:10px;"><a style="color:#ff6c6c;" target="_blank" href="http://www.geoiptool.com/?IP='. $ip. '">'. __('View Location',$textdomain).'</a></span></p>';
				endif;
				return $html;
		
		}
		public function crf_get_entry_basic_info($formid,$id)
		{
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$form_type =$this->crf_submision_field_value($id,'form_type');
			$user_name = $this->crf_submision_field_value($id,'user_name'); // receiving username
			$user_email = $this->crf_submision_field_value($id,'user_email'); // receiving email address
			$role = $this->crf_submision_field_value($id,'role');
			if($form_type=='reg_form'){ $formtype = 'Registration Form';}else{$formtype = 'Contact Form';}
			$html = '<p><span class="entry_heading">'. __('Entry Id',$textdomain).' : </span><span class="entry_Value">'. $id. '</span></p>
      <p><span class="entry_heading">'. __('Entry Type',$textdomain).' : </span><span class="entry_Value">'.$formtype. '</span></p>';
         
		if(isset($user_name)):
      		$html .= '<p><span class="entry_heading">'. __('User Name',$textdomain).' : </span><span class="entry_Value" >'. $user_name. '</span></p>';
		endif;
		
		if(isset($user_email)):
     		$html .= '<p><span class="entry_heading">'. __('User Email',$textdomain).' : </span><span class="entry_Value" >'. $user_email. '</span></p>';
		endif;
		if(isset($role)):
    	$html .= '<p><span class="entry_heading">'. __('User Role',$textdomain).' : </span><span class="entry_Value" >'. $role. '</span></p>';
		endif;
			
			return $html;
		}
		public function crf_get_entry_custom_fields($formid,$id)
		{
			global $wpdb;
			$html = '';
			$crf_submissions =$wpdb->prefix."crf_submissions";
			$crf_fields =$wpdb->prefix."crf_fields";
			$qry = "select * from $crf_submissions where submission_id=".$id;
			$reg = $wpdb->get_results($qry);
			
			if(!empty($reg))
			{
				foreach($reg as $row)
				{
					$label ='';
					$type = '';
					if($row->value!="")
					{
							$string = $row->field;
							
							$output = explode("_",$string);
							$field_id = $output[count($output)-1];
							if (is_numeric($field_id))
							{
								$qry1 = "select * from $crf_fields where Id=".$field_id;
								$reg1 = $wpdb->get_row($qry1);
								if ( null !== $reg1 )
								{
								$label = $reg1->Name;
								$type = $reg1->Type;
								}
								if($label=='')
								{
									for($i=0;$i<count($output)-1;$i++)
									{
										$label .=$output[$i];
									}
								}
								
								if(isset($type) && $type =='file')
								{
									continue;
								}
								
								if(isset($type) && $type =='pricing')
								{
									//pricing field html start
											
									  $value = $row->value;	
									  $html .='<p><span class="entry_heading">'. $label.' :</span>';
									  $values = explode(',',$value);
									  $html .= '<span class="entry_Value">';
									  $count = count($values);
									  $i = 1;
									  foreach($values as $val)
									  {
										  
										$optionval = explode('_',$val);
										
										if(strpos($val,'_')===false)
										 {
											if(get_option('crf_currency_position','before')=='before')
											{
												$html .=$this->crf_get_currency_symbol();	
											}
											$html .= $optionval[0];
											
											if(get_option('crf_currency_position','before')=='after')
											{
												$html .=$this->crf_get_currency_symbol();	
											}
											
										 }
										 else
										 {
											$html .= $optionval[0]; 
											$html .=' ';
											if(get_option('crf_currency_position','before')=='before')
											{
												$html .=$this->crf_get_currency_symbol();	
												$html .= $optionval[1]; 
											}
											
											
											if(get_option('crf_currency_position','before')=='after')
											{
												$html .= $optionval[1];
												$html .=$this->crf_get_currency_symbol();	
											}
										 }
										if($i<$count)
										$html .= ',';
										$i++;
									  }
									  $html .= '</span>';
									  $html .= '</p>';
								  
											
											//pricing field html end	
								}
								else
								{
									$html .='<p><span class="entry_heading">'.$label.' : </span><span class="entry_Value">'.$row->value.'</span></p>'; 
								}
							}
							elseif($string=='first_name' || $string=='last_name' || $string=='description')
							{
								$qry2 = "select Name from $crf_fields where Type='".$string."' and Form_Id=".$formid;
								$label2 = $wpdb->get_var($qry2);
								if(!isset($label2) || $label2=="")
								{
									$label2 =$string;
									
								}
								$html .='<p><span class="entry_heading">'.$label2.' : </span><span class="entry_Value">'.$row->value.'</span></p>'; 
							}
							
					}
				}
			}
			return $html;
		
		}
		
		public function crf_get_entry_pricing_fields($formid,$id)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$qry1 = "select * from $crf_fields where Form_Id= '".$formid."' and Type in('pricing') order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			//print_r($reg1);die;
			
			$price_html = "";
			 if(!empty($reg1))
				  {
				   foreach($reg1 as $row1)
				   {
					  if(!empty($row1))
					  {
						  $Customfield = $this->crf_get_field_key($row1);
						  $value = $this->crf_submision_field_value($id,$Customfield); 
						  if(trim($value)!='')
						  {
							  $price_html .='<p><span class="entry_heading">'. $row1->Name.' :</span>';
							  $values = explode(',',$value);
							  $price_html .= '<span class="entry_Value">';
							  $count = count($values);
							  $i = 1;
							  foreach($values as $val)
							  {
								  
								$optionval = explode('_',$val);
								
								if(strpos($val,'_')===false)
								 {
									if(get_option('crf_currency_position','before')=='before')
									{
										$price_html .=$this->crf_get_currency_symbol();	
									}
									$price_html .= $optionval[0];
									
									if(get_option('crf_currency_position','before')=='after')
									{
										$price_html .=$this->crf_get_currency_symbol();	
									}
									
								 }
								 else
								 {
									$price_html .= $optionval[0]; 
									$price_html .=' ';
									if(get_option('crf_currency_position','before')=='before')
									{
										$price_html .=$this->crf_get_currency_symbol();	
										$price_html .= $optionval[1]; 
									}
									
									
									if(get_option('crf_currency_position','before')=='after')
									{
										$price_html .= $optionval[1];
										$price_html .=$this->crf_get_currency_symbol();	
									}
								 }
								if($i<$count)
								$price_html .= ',';
								$i++;
							  }
							  $price_html .= '</span>';
							  $price_html .= '</p>';
						  }
					  }
				   }
				  }
				  
				  return $price_html;	
		}
		
		public function crf_get_entry_attachment($formid,$id)
		{
				/*file addon start */
				global $wpdb;
				$crf_fields=$wpdb->prefix."crf_fields";
				$textdomain = 'custom-registration-form-builder-with-submission-manager';
				  $qry1 = "select * from $crf_fields where Form_Id= '".$formid."' and Type in('file') order by ordering asc";
				  $reg1 = $wpdb->get_results($qry1);
				  $attachment_html = "";
				  if(!empty($reg1))
				  {
					  
				   foreach($reg1 as $row1)
				   {
					  if(!empty($row1))
					  {
						  $Customfield = $this->crf_get_field_key($row1);
						  $value = $this->crf_submision_field_value($id,$Customfield); 
						  if(trim($value)!='')
						  {
						  	  $values = explode(',',$value);
						  
							  $attachment_html .=  '<div class="field-labal" ><p class="entry_heading" >'.$row1->Name.':</p>';	
							  foreach($values as $fileid)
							  {
								$attachment_html .='<div class="entry_Value"><ul>';
								$attachment_html .= '<li class="attachment_link">'.wp_get_attachment_link($fileid,'full',false,true,false).'</li>';
								$attachment_html .='<li class="file_title">'.get_the_title( $fileid ).'</li>';
								$attachment_html .= '<li class="Download"><a href="'.wp_get_attachment_url( $fileid ).'">'.__('Download',$textdomain).'</a></li>';
								$attachment_html .='<div class="clear"></div></ul><div class="clear"></div></div>';
							  }
							  $attachment_html .='</div>';
						  }
					  }
				   }
				  }
				  return $attachment_html;
			/*file addon end */	
		}
		
		public function crf_create_user_notification($id)
		{
			$user_name = $this->crf_submision_field_value($id,'user_name'); // receiving username
			$user_email = $this->crf_submision_field_value($id,'user_email'); // receiving email address
			//$inputPassword = $this->crf_submision_field_value($id,'user_pass'); // receiving password
			$pass_encrypt = $this->crf_submision_field_value($id,'pass_encrypt');
			if($pass_encrypt =='1')
			{
				$pass = $this->crf_submision_field_value($id,'user_pass'); // receiving password
				$inputPassword = $this->crf_encrypt_decrypt_pass('decrypt',$pass); // receiving password
			}
			else
			{
				$inputPassword = $this->crf_submision_field_value($id,'user_pass'); // receiving password
			}
			$sitename = get_bloginfo();
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$subject = __('Your Account Details',$textdomain);
			$message = __('Your account has been successfully activated on',$textdomain);
			$message .= ' '.$sitename.'.';
			$message .="\r\n"; 
			
			$sendpassword = $this->crf_get_global_option_value('send_password');
			if($sendpassword=='yes')
			{
				$message .= __('You can now login using following credentials:',$textdomain);
				$message .="\r\n";  
				$message .= __('Username : ',$textdomain).$user_name;
				$message .="\r\n";  
				$message .= __('Password : ',$textdomain).$inputPassword;
				$message .="\r\n";  
			}
			
			$from_email_address = $this->crf_get_from_email();
			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8\r\n";
			$headers .= 'From:'.$from_email_address. "\r\n"; 
			if(isset($user_email))
			{
			  wp_mail( $user_email, $subject, $message, $headers );//Sends email to user on successful registration
			}	
		}
		
		
		
		public function crf_create_new_user($id)
		{	
			$user_name = $this->crf_submision_field_value($id,'user_name'); // receiving username
			$user_email = $this->crf_submision_field_value($id,'user_email'); // receiving email address
			$pass_encrypt = $this->crf_submision_field_value($id,'pass_encrypt');
			if($pass_encrypt =='1')
			{
				$pass = $this->crf_submision_field_value($id,'user_pass'); // receiving password
				$inputPassword = $this->crf_encrypt_decrypt_pass('decrypt',$pass); // receiving password
			}
			else
			{
				$inputPassword = $this->crf_submision_field_value($id,'user_pass'); // receiving password
			}
			$form_id = $this->crf_get_form_id_using_submission_id($id);
			$role = $this->crf_submision_field_value($id,'role'); // receiving password
			
			 // Checks if username and email is already exists.
			if (username_exists($user_name)==false && email_exists($user_email) == false )
			{
				$user_id = wp_create_user( $user_name, $inputPassword, $user_email );//Creates new WP user after successful registration
				$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $role ) );
				$this->crf_insert_submission($id,$form_id,'user_id',$user_id);
			}
			else
			{
				$user_id =  username_exists( $user_name );	
				if($user_id ==false)
				{
					$user_id =  email_exists($user_email);	
				}
				if($user_id>0 && is_multisite() && !is_user_member_of_blog($user_id))
				{
					$blog_id = get_current_blog_id();
					add_user_to_blog( $blog_id, $user_id, $role );
					$this->crf_insert_submission($id,$form_id,'user_id',$user_id);	
				}
			}
			
			return $user_id;
		}
		
		public function crf_get_all_fields_from_submission($id)
		{
			global $wpdb;
			$crf_submissions = $wpdb->prefix."crf_submissions";	
			$qry = "select * from $crf_submissions where submission_id=".$id;	
			$data = $wpdb->get_results($qry);
			return $data; 
		}
		
		public function crf_create_user($value,$role)
		{
			$user_name = $value['user_name']; // receiving username
			$user_email = $value['user_email']; // receiving email address
			$inputPassword = $value['user_pass']; // receiving password
			$user_id = username_exists( $user_name ); // Checks if username is already exists.
			
			if ( !$user_id and email_exists($user_email) == false )//Creates password if password auto-generation is turned on in the settings
			{
				$user_id = wp_create_user( $user_name, $inputPassword, $user_email );//Creates new WP user after successful registration
				$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $role ) );
			}
			return $user_id;
		}
		
		public function crf_update_stats($post,$id)
		{
			global $wpdb;
			$crf_stats=$wpdb->prefix."crf_stats";
			$stats = $wpdb->get_row( "SELECT * FROM $crf_stats where form_id ='".$id."' and stats_key='".$post['crf_key']."'");
			$stats_details = maybe_unserialize($stats->details);
			$stats_details['submitted'] = "yes";
			$stats_details['submit_time'] = time();
			$stats_details['total_time'] = $stats_details['submit_time']-$stats_details['timestamp'];
			$stats_final_details = maybe_serialize($stats_details);
			$stats_update = "update $crf_stats set details ='".$stats_final_details."' where id=".$stats->id;
			$wpdb->query($stats_update);	
		}
		
		public function set_crf_user_role($id,$post,$form_option)
		{
			if(isset($form_option['let_user_decide']))
			$let_user_decide = $form_option['let_user_decide'];
			if(isset($form_option['user_role_options']))
			$user_role_options = $form_option['user_role_options'];
			
			if(!isset($let_user_decide) || $let_user_decide=="")
			{
				
				if(isset($form_option['user_role']))
				$role = $form_option['user_role'];
				if(!isset($role)|| $role==""){ $role = 'subscriber'; } //Defines default role if there is not shortcode in registration form
			}
			else
			{
				if(isset($post['user_role']) && in_array($post['user_role'],$user_role_options))
				{
					$role = $post['user_role'];
				}
				else
				{ 
					$role = 'subscriber'; 
				} //Defines default role if there is not shortcode in registration form	
			}
			return $role;
				
		}
		
		public function crf_insert_mailchimp($form_id,$email,$firstname="",$lastname="")
		{
			$dir = plugin_dir_path( __FILE__ );
			require($dir.'../Mailchimp.php');
			$form_options = $this->crf_get_form_option_value('form_option',$form_id);
			$form_option = maybe_unserialize($form_options);
			$listid = $form_option['mailchimp_list'];
			$api_key = $this->crf_get_global_option_value('mailchimp_key');
			$MailChimp = new Mailchimp( $api_key );
			$path = 'lists/'.$listid.'/members';
			$array = array(
                'email_address'     => $email,
                'status'            => 'subscribed'
            );
			if($firstname!="")
			{
			$array['merge_fields']['FNAME'] = $firstname;
			}
			if($lastname!="")
			{
			$array['merge_fields']['LNAME'] = $lastname;
			}
			$result = $MailChimp->post($path, $array);
			return $result;	
		}
		
		public function crf_check_server_validation($post,$id,$files,$server)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$crf_paypal_fields = $wpdb->prefix."crf_paypal_fields";
			$message = '';	
			$form_type = $this->crf_get_form_option_value('form_type',$id);
			$form_options = $this->crf_get_form_option_value('form_option',$id);
			$form_option = maybe_unserialize($form_options);
			
			if(isset($form_option['let_user_decide']))
			$let_user_decide = $form_option['let_user_decide'];
			
			
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			if($form_type=='reg_form')
			{
					$user_name =  sanitize_user($post['user_name']);
					$user_email =  sanitize_email($post['user_email']);
					$user_pass =  $post['user_pass'];
					$user_email = $post['user_email'];
					
					if($user_name == '')
					{
						$message .=  __("Username is a required field",$textdomain).'<br />';		
					}
					
					if($user_email=='')
					{
						$message .=  __("E-mail is a required field",$textdomain).'<br />';	
					}
					else
					{
						if(is_email($user_email)==false)
						 {
							$message .=  __("Please enter a valid e-mail address",$textdomain).'<br />';	 
						 }	
					}
					
					if($user_pass=='')
					{
						$message .=  __("Password is a required field",$textdomain).'<br />';
					}
					if(isset($let_user_decide))
					{
						if(!isset($post['user_role']))
						{
							$message .=  __("Choose a User Role",$textdomain).'<br />';	
						}	
					}
					
					if(strlen($user_pass)<7)
					{
						$message .=  __("Password is too short. At least 7 characters please!",$textdomain).'<br />';
					}
					if($user_pass!==$post['user_confirm_password'])
					{
						$message .=  __("Password and confirm password do not match.",$textdomain).'<br />';	
					}
					
			}
			
			$qry1 = "select * from $crf_fields where Form_Id= '".$id."' and Type not in('heading','paragraph') order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			
			foreach($reg1 as $row1)
			{
				$Customfield = $this->crf_get_field_key($row1);
				if($row1->Require == 1 && $row1->Type!='file')
				{
					
					if(!isset($post[$Customfield]) || $post[$Customfield]=='')
					{
						$message .=  $row1->Name. __(" is a required field",$textdomain).'<br />';	
					}
					else
					{
						if(is_array($post[$Customfield]))
						{
							$value = implode(',',$post[$Customfield]);
							if(!isset($value) || $value=='') $message .=  $row1->Name. __(" is a required field",$textdomain).'<br />';
						}
					}
				}
				if($row1->Require == 1 && $row1->Type=='file')
				{
					$filefield = $files[$Customfield];			
					if(is_array($filefield) && empty($filefield['name'][0]))
					{
						$message .=  $row1->Name. __(" is a required field",$textdomain).'<br />';	
					}
				}
	
				if($row1->Type=='email' && isset($post[$Customfield]) && $post[$Customfield]!="")
				{
					 if(is_email($post[$Customfield])==false)
					 {
						$message .=  __("Please enter a valid e-mail address",$textdomain).'<br />';	 
					 }
				}
				
				if($row1->Type=='number' && isset($post[$Customfield]) && $post[$Customfield]!="")
				{
					 if(is_numeric($post[$Customfield])==false)
					 {
						$message .=  __("Please enter a valid number",$textdomain).'<br />';	 
					 }
				}
				
				if($row1->Type=='pricing' && isset($post[$Customfield]) && $post[$Customfield]!="")
				{
					
					 $crf_paypal_fields =$wpdb->prefix."crf_paypal_fields";
					 $type = $wpdb->get_var("select Type from $crf_paypal_fields where Id = '".$row1->Value."'");
					 if($type=='userdefine' && is_numeric($post[$Customfield])==false)
					 {
						$message .=  __("Please enter a valid amount",$textdomain).'<br />';	 
					 }
				}
				
							
				if($row1->Type=='DatePicker' && isset($post[$Customfield]) && $post[$Customfield]!="")
				{
					 if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$post[$Customfield]))
					 {
						$message .=  __("Please enter a valid date (yyyy-mm-dd format)",$textdomain).'<br />';	 
					 }
				}	
							
				
			}
			
			return $message;
			
			
				
		}
		
		public function crf_encrypt_decrypt_pass($action, $string) 
		{
			$output = false;
		
			$encrypt_method = "AES-256-CBC";
			$secret_key = 'This is my secret key';
			$secret_iv = 'This is my secret iv';
		
			// hash
			$key = hash('sha256', $secret_key);
			
			// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			$iv = substr(hash('sha256', $secret_iv), 0, 16);
		
			if( $action == 'encrypt' ) {
				$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
				$output = base64_encode($output);
			}
			else if( $action == 'decrypt' ){
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}
		
			return $output;
		}

		
		public function crf_insert_form_entry($post,$id,$files,$server)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$crf_submissions=$wpdb->prefix."crf_submissions";
			$qry1 = "select * from $crf_fields where Form_Id= '".$id."' and Type not in('heading','paragraph') order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			$entry= array();
			$form_type = $this->crf_get_form_option_value('form_type',$id);
			$last_insert_id = $wpdb->get_var("SELECT max(`submission_id`) FROM $crf_submissions");
			$submission_id = $last_insert_id +1;
			$userip = $this->crf_get_global_option_value('userip');
			$autoapproval = $this->crf_get_global_option_value('userautoapproval');
			$form_options = $this->crf_get_form_option_value('form_option',$id);
			$form_option = maybe_unserialize($form_options);
			$this->crf_insert_submission($submission_id,$id,'form_type',$form_type);
			$this->crf_insert_submission($submission_id,$id,'user_approval',$autoapproval);
			$this->crf_insert_submission($submission_id,$id,'token',$post['token'].$submission_id);
			
			if($form_type=='reg_form')
			{
					$user_name =  sanitize_user($post['user_name']);
					$user_email =  sanitize_email($post['user_email']);
					$user_pass = $this->crf_encrypt_decrypt_pass('encrypt',$post['user_pass']);
					$role= $this->set_crf_user_role($id,$post,$form_option);
					$user_email = $post['user_email'];
					$this->crf_insert_submission($submission_id,$id,'user_name',$user_name);
					$this->crf_insert_submission($submission_id,$id,'user_email',$user_email);
					$this->crf_insert_submission($submission_id,$id,'user_pass',$user_pass);
					$this->crf_insert_submission($submission_id,$id,'pass_encrypt','1');
					$this->crf_insert_submission($submission_id,$id,'role',$role);
					
			}
			
			if(!empty($reg1))
			{
			 foreach($reg1 as $row1)
			 {
				if(!empty($row1))
				{
					/*file addon start */
					$Customfield = $this->crf_get_field_key($row1);
					
					if ($row1->Type=='file') 
					{
						$allowfieldstypes ='';
						if(trim($row1->Option_Value)!="")
						{
							$allowfieldstypes = strtolower(trim($row1->Option_Value));
						}
						else
						{
							$allowfieldstypes =  strtolower(get_option('ucf_allowfiletypes','jpg|jpeg|png|gif|doc|pdf|docx|txt|psd'));	
						}
						//echo $Customfield;die;
						$filefield = $files[$Customfield];
						
						if(is_array($filefield))
						{
									
							for( $i =0; $i<count($filefield['name']); $i++ ) 
							{
								$file = array(
											  'name'     => $filefield['name'][$i],
											  'type'     => $filefield['type'][$i],
											  'tmp_name' => $filefield['tmp_name'][$i],
											  'error'    => $filefield['error'][$i],
											  'size'     => $filefield['size'][$i]
											);
											
								if ($filefield['error'][$i] === 0)
								{
												
									  if ( ! function_exists( 'wp_handle_upload' ) )
									  {
										  require_once( ABSPATH . 'wp-admin/includes/file.php' );
									  }
									  
									  $upload_overrides = array( 'test_form' => false );
									  $movefile = wp_handle_upload( $file, $upload_overrides );
									  
									  if ( $movefile )
									  {
										  // $filename should be the path to a file in the upload directory.
										  $filename = $movefile['file'];
										  // The ID of the post this attachment is for.
										  $parent_post_id = 0;
										  // Check the type of tile. We'll use this as the 'post_mime_type'.
										  $filetype = wp_check_filetype( basename( $filename ), null );
										  $current_file_type = strtolower($filetype['ext']);
										  if(strpos($allowfieldstypes,$current_file_type)===false)
										  {
											  continue;
										  }
										 // print_r($filetype);die;
										 
										  // Get the path to the upload directory.
										  $wp_upload_dir = wp_upload_dir();
										  // Prepare an array of post data for the attachment.
										  $attachment = array(
				  
											  'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
				  
											  'post_mime_type' => $filetype['type'],
				  
											  'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				  
											  'post_content'   => '',
				  
											  'post_status'    => 'inherit'
				  
										  );
										  // Insert the attachment.
										  $attach_id[] = wp_insert_attachment( $attachment, $filename, $parent_post_id );
										  
										  
									  }
								  
								  
							 }
								
									
							}
						}
						if(isset($attach_id)):
						$attach_ids = implode(',',$attach_id);
						$this->crf_insert_submission($submission_id,$id,$Customfield,$attach_ids);
						unset($attach_id);
						endif;
					}
					else
					if(isset($post[$Customfield]))
					{	
						if(is_array($post[$Customfield]))
						{
							$value = sanitize_text_field(rtrim(implode(',',$post[$Customfield]),','));	
						}
						else
						{
							if ($row1->Type=='email')
							{
								$value = sanitize_email($post[$Customfield]);
							}
							else
							{
								$value = sanitize_text_field($post[$Customfield]);	
							}
							
						}
					$this->crf_insert_submission($submission_id,$id,$Customfield,$value);
						//$entry[$Customfield] =  $post[$Customfield];
					}
					/*file addon end */
				}
			 }
			}
			
			if($userip=='yes')
			{
				$this->crf_insert_submission($submission_id,$id,'User_IP',$server['REMOTE_ADDR']);
				$this->crf_insert_submission($submission_id,$id,'Browser',$server['HTTP_USER_AGENT']);
			}
			$time = time();
			$this->crf_insert_submission($submission_id,$id,'entry_time',$time);
			return $submission_id;
		}
		
		public function crf_insert_user_meta($id,$userid)
		{
			$entries = $this->crf_get_all_fields_from_submission($id);
			foreach($entries as $entry)
			{
				update_user_meta( $userid, $entry->field, $entry->value );
			}
				
		}
		
		
		public function crf_insert_user_data($id,$post,$user_id)
		{
				/*Insert custom field values if displayed in registration form*/
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$qry1 = "select * from $crf_fields where Form_Id= '".$id."' and Type not in('heading','paragraph') order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			if(!empty($reg1))
			{
			 foreach($reg1 as $row1)
			 {
				if(!empty($row1))
				{
					$Customfield = $this->crf_get_field_key($row1);
					if(!isset($prev_value)) $prev_value='';
					if(!isset($post[$Customfield]))$post[$Customfield]='';
					add_user_meta( $user_id, $Customfield, $post[$Customfield], true );
					update_user_meta( $user_id, $Customfield, $post[$Customfield], $prev_value );
				}
			 }
			}	
		}
		
		
		public function crf_get_redirect_url($id,$redirect_option)
		{
			global $wpdb;
			$url="";
			$crf_forms=$wpdb->prefix."crf_forms";
			if($redirect_option=='url')
			{
				$qry="SELECT redirect_url_url FROM $crf_forms WHERE id=".$id;
				$url = $wpdb->get_var($qry);	
			}
			
			if($redirect_option == 'page')
			{
				$qry="SELECT redirect_page_id FROM $crf_forms WHERE id=".$id;
				$page_id = $wpdb->get_var($qry);	
				$url =  get_permalink($page_id); 
			}
			return $url;	
		}
		
		public function crf_get_admin_email()
		{
			$admin_email = $this->crf_get_global_option_value('adminemail');
			if(empty($admin_email) || $admin_email=="")
			{
				$admin_email = get_option('admin_email');	
			}
			return $admin_email;	
		}
		
		public function crf_send_admin_notification($entry_id,$id)
		{
				global $wpdb;
				$crf_fields=$wpdb->prefix."crf_fields";
				$crf_submissions=$wpdb->prefix."crf_submissions";
				$form_name = $this->crf_get_form_option_value('form_name',$id);
				$admin_email = $this->crf_get_admin_email();
				$notification_message = "";
				$from_email_address = $this->crf_get_from_email();
				$qry = "select * from $crf_submissions where field!='user_pass' and submission_id=".$entry_id;	
				$entry = $wpdb->get_results($qry);
				
				if(!empty($entry))
				{
					$notification_message .= '<html><body><table cellpadding="10">';
					foreach($entry as $val) 
					{
						$key = $val->field;
						$value = $val->value;
						if(is_array($value))
						{
							$value = implode(',',$val);	
						}
						
						$entryval = str_replace("_"," ",$key);
										
						$fields= explode("_", $key);
						$fieldid = $fields[count($fields)-1];
						if(is_numeric($fieldid))
						{
							$nameqry = "select Name from $crf_fields where id=".$fieldid;
							$entryval = $wpdb->get_var($nameqry);
						}
						
						$notification_message .= '<tr><td><strong>'.$entryval.'</strong>: </td><td>'.$value.'</td></tr>';
						
					}
					$notification_message .= '</table></body></html>';
				}
				
					/*$headers = "From: " . $user_email . "\r\n";
					$headers .= "Reply-To: ".$user_email. "\r\n";*/
					$headers2 = 'From:'.$from_email_address. "\r\n"; 
					$headers2 .= "MIME-Version: 1.0" . "\r\n";
					$headers2 .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
				wp_mail( $admin_email,$form_name.' New Submission Notification', $notification_message,$headers2 );//Sends email to user on successful registration
				 
				
		}
		
		public function crf_get_success_message($id)
		{
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$success_message = $this->crf_get_form_option_value('success_message',$id);
			
			if($success_message=="")
			{
				$success_message = __('Thank you for your submission.',$textdomain);
			}
			?>
<div id="crf-form">
  <div id="main-crf-form">
    <div class="main-edit-profile"><?php echo $success_message;?> <br />
      <br />
    </div>
  </div>
</div>
<?php	
		}
		
		public function crf_get_sumission_token_number($formid,$id)
		{
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$form_option = maybe_unserialize($this->crf_get_form_option_value('form_option',$formid));
			if(isset($form_option['showtoken']) && $form_option['showtoken']==1 )
			{
				$token = $this->crf_submision_field_value($id,'token');	
				$success_message = __('Your unique token number is ',$textdomain);
				$success_message .= $token;
			?>
<div id="crf-form">
  <div id="main-crf-form">
    <div class="main-edit-profile"><?php echo $success_message;?> <br />
      <br />
    </div>
  </div>
</div>
<?php
			}
		}
		
		public function crf_get_from_email()
		{
			$from_email_address = $this->crf_get_global_option_value('from_email');
			if($from_email_address=="")
			{
				$from_email_address = get_option('admin_email');	
			}
			return $from_email_address;
			  	
		}
		
		public function crf_send_note_notification($entry_id,$subject,$message)
		{
			$form_id = $this->crf_get_form_id_using_submission_id($entry_id);
			$form_type = $this->crf_get_form_option_value('form_type',$form_id);
			$from_email_address = $this->crf_get_from_email();
			if($form_type=='contact_form')
			  {
				  $emailfield = $this->crf_get_first_email_field_key($form_id);
				  $user_email =  $this->crf_submision_field_value($entry_id,$emailfield);
			  }
			  else
			  {
				  $user_email = $this->crf_submision_field_value($entry_id,'user_email');
			  }
			  
			   $headers = "MIME-Version: 1.0\r\n";
			  $headers .= "Content-type:text/html;charset=UTF-8\r\n";
			  $headers .= 'From:'.$from_email_address. "\r\n"; 
			wp_mail( $user_email, $subject, $message, $headers );
		}
		public function crf_send_user_email($id,$entry_id,$userid=0)
		{
			  global $wpdb;
			  $textdomain = 'custom-registration-form-builder-with-submission-manager';
			  $crf_fields=$wpdb->prefix."crf_fields";
			  $form_type = $this->crf_get_form_option_value('form_type',$id);
			  $sendpassword = $this->crf_get_global_option_value('send_password');
			  $from_email_address = $this->crf_get_from_email();
			  $subject = $this->crf_get_form_option_value('crf_welcome_email_subject',$id);
			  if($subject == "")
			  {
				$subject = get_bloginfo('name');//Auto inserts email Subject if it is not defined in dashboard settings
			  }
			  $message = $this->crf_email_message_html($id,$entry_id);
			  if($form_type=='contact_form')
			  {
				  $emailfield = $this->crf_get_first_email_field_key($id);
				  $user_email =  $this->crf_submision_field_value($entry_id,$emailfield);
			  }
			  else
			  {
				  $user_email = $this->crf_submision_field_value($entry_id,'user_email');
			  }
			  
			  $headers = "MIME-Version: 1.0\r\n";
			  $headers .= "Content-type:text/html;charset=UTF-8\r\n";
			  $headers .= 'From:'.$from_email_address. "\r\n"; 
			  if(isset($user_email))
			  {
				wp_mail( $user_email, $subject, $message, $headers );//Sends email to user on successful registration
			  }
		}
		
		public function crf_get_subscriber_other_field($id,$entry_id,$field)
		{
			$form_option = maybe_unserialize($this->crf_get_form_option_value('form_option',$id));
			$fieldvalue ="";
			if(isset($form_option[$field]) && $form_option[$field]!="")
			{
				$fieldkey = $form_option[$field];
				$fieldvalue =  $this->crf_submision_field_value($entry_id,$fieldkey);
			}
			return $fieldvalue;
		}
		
		public function crf_get_subscriber_email($id,$entry_id)
		{
				global $wpdb;
				$form_type = $this->crf_submision_field_value($entry_id,'form_type');
				$crf_fields = $wpdb->prefix."crf_fields";
				$textdomain = 'custom-registration-form-builder-with-submission-manager';
				if($form_type=='contact_form')
				{
					$form_option = maybe_unserialize($this->crf_get_form_option_value('form_option',$id));
					if(isset($form_option['mailchimp_emailfield']) && $form_option['mailchimp_emailfield']!="")
					{
						$emailfield = $form_option['mailchimp_emailfield'];
						$user_email =  $this->crf_submision_field_value($entry_id,$emailfield);
					}
					else
					{
						$emailfield = $this->crf_get_first_email_field_key($id);
						$user_email = $this->crf_submision_field_value($entry_id,$emailfield); 
					}
				}
				else
				{
						$user_email = $this->crf_submision_field_value($entry_id,'user_email');
				}
				
			    return $user_email;
		}
		
		public function crf_get_first_email_field_key($id)
		{
			global $wpdb;
			$emailfield = '';
			$crf_fields = $wpdb->prefix."crf_fields";
			 $qry1 = "select * from $crf_fields where Form_Id= '".$id."' and Type ='email' order by ordering asc limit 1";
			 $row1 = $wpdb->get_row($qry1);
			 if(isset($row1))
			 {
				$emailfield = $this->crf_get_field_key($row1);
			 }	
			 return $emailfield;
		}
		
		public function crf_get_pricing_field($id)
		{
			global $wpdb;
			$crf_fields = $wpdb->prefix."crf_fields";
			 $qry = "select * from $crf_fields where Form_Id= '".$id."' and Type ='pricing' order by ordering asc";
			 $row = $wpdb->get_results($qry);
			 foreach($row as $field)
			 {
				$fields[] = $this->crf_get_field_key($field);
			 }
			 
			 return $fields;
		}
		
		public function crf_get_base_field_name_with_key($key)
		{
			$name = '';
			$find_underscore = strpos($key,'_');
			if($find_underscore!=false)
			{
				$name = substr($key, 0, strrpos($key, '_'));
			}
			return $name;
		}
		
		public function crf_get_pricing_field_Submission_value($id,$field)
		{
			$product = array();
			$field_value = $this->crf_submision_field_value($id,$field);
			$findcomma = strpos($field_value,',');
			if($findcomma!=false)
			{
				$values[] = explode(',',$field_value);
			}
			else if(strpos($field_value,'_'))
			{
				$values[] = $field_value;
			}
			else
			{
				$key = $this->crf_get_base_field_name_with_key($field);
				$product[$key] = $field_value;
			}
			
			if(!empty($values))
			{
				foreach($values as $value)
				{	
					if(is_array($value))
					{
						foreach($value as $val)
						{
							$a = explode('_',$val);
							$lenght = count($a);
							$price = (float)$a[$lenght-1];	
							$key = $this->crf_get_base_field_name_with_key($val);	
							$product[$key] = $price;
						}
					}
					else
					{
						$a = explode('_',$value);
						$lenght = count($a);
						$price = (float)$a[$lenght-1];	
						$key = $this->crf_get_base_field_name_with_key($value);
						$product[$key] = $price;
						
					}
				}
			}
			
			return $product;
		}
		
		public function crf_integrate_facebook_login()
		{
			  $facebook_login = $this->crf_get_global_option_value('enable_facebook');
			  if($facebook_login=='yes')
			  {
				  include '../facebook/crf_facebook.php';
				  upb_fb_login_validate();
				  upb_fb_loginForm();
			  }	
		}
		
		public function crf_insert_submission($submission_id,$form_id,$field,$value)
		{
			global $wpdb;
			if(is_array($value)){ print_r($value); die;}
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			//$qry = "insert into $crf_submissions values('','".$submission_id."','".$form_id."','".$field."','".$value."')";	
			//$wpdb->query($qry);
			
			$wpdb->insert( $crf_submissions, 
				  array( 
					  'submission_id' =>$submission_id, 
					  'form_id' => $form_id,
					  'field' => $field, 
					  'value' => $value
				  ), 
				  array( 
					  '%d', 
					  '%d',
					  '%s', 
					  '%s'  
				  ) 
			  );
		}
		
		public function crf_update_submission($submission_id,$field,$value)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "update $crf_submissions set value = '".$value."' where field = '".$field."' and submission_id=".$submission_id;	
			$wpdb->query($qry);
		}
		
		public function crf_get_form_id_using_submission_id($id)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "select `form_id` from $crf_submissions where submission_id='".$id."'";	
			$data = $wpdb->get_var($qry);
			return $data;	
		}
		
		public function crf_submision_field_value($submission_id,$field)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "select `value` from $crf_submissions where submission_id='".$submission_id."' and `field`='".$field."'";	
			$data = $wpdb->get_var($qry);
			return $data;
		}
		
		public function crf_get_submissions_by_date($startdate,$enddate)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			 $qry = "SELECT distinct(submission_id),form_id FROM $crf_submissions where `field` = 'entry_time' and `value` between '".$startdate."' and '".$enddate."'";
			$qry .=" order by id desc";
			$entries = $wpdb->get_results($qry);
			return $entries;	
		}
		
		public function crf_get_submissions_latest_submission()
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			 $qry = "SELECT distinct(submission_id),form_id FROM $crf_submissions ";
			$qry .=" order by id desc limit 10";
			$entries = $wpdb->get_results($qry);
			return $entries;	
		}
		
		public function crf_get_short_submissions($submissions)
		{
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			?>
<table class="crf_user_submissions">
  <tr>
    <th class="crf_user_sr">#</th>
    <th class="crf_form_title"><?php _e('Form Title',$textdomain);?></th>
    <th class="crf_submission_date"><?php _e('Date',$textdomain);?></th>
    <th class="crf_form_payment"><?php _e('Payment',$textdomain);?></th>
    <th class="crf_view_submission"></th>
  </tr>
  <?php $i=1; foreach($submissions as $submission):?>
  <tr>
    <td class="crf_user_sr"><?php echo $i;?></td>
    <td class="crf_form_title"><?php if($this->crf_get_form_option_value('form_name',$submission->form_id)!=''){ echo $this->crf_get_form_option_value('form_name',$submission->form_id);}else{ _e('Form Deleted',$textdomain); }?></td>
    <td class="crf_submission_date"><?php echo date('M jS Y, g:i a',$this->crf_submision_field_value($submission->submission_id,'entry_time'));?></td>
    <td class="crf_form_payment"><?php if($this->crf_submision_field_value($submission->submission_id,'payment_status')!='') echo $this->crf_submision_field_value($submission->submission_id,'payment_status');else echo'N/A';?></td>
    <td class="crf_view_submission"><a href="admin.php?page=crf_view_entry&id=<?php echo $submission->submission_id;?>"><?php _e('View',$textdomain);?></a></td>
  </tr>
  <?php $i++; endforeach;?>
</table>
<?php	
		}
		
		public function crf_submision_get_id_with_field($field,$value)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "select distinct(submission_id),form_id from $crf_submissions where value='".$value."' and `field`='".$field."'";	
			$data = $wpdb->get_results($qry);
			return $data;
		}
		
		public function crf_get_all_attachments_ids($formid)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$crf_submissions=$wpdb->prefix."crf_submissions";
			 $qry1 = "select * from $crf_fields where Form_Id= '".$formid."' and Type in('file') order by ordering asc";
				  $reg1 = $wpdb->get_results($qry1);
				  $attachment_html = "";
				  $fileids = '';
				  if(!empty($reg1))
				  {
				   foreach($reg1 as $row1)
				   {
					  if(!empty($row1))
					  {
						  $Customfield = $this->crf_get_field_key($row1);
						  $qry2 = "select value from $crf_submissions where form_id = '".$formid."' and field = '".$Customfield."'";
						  $reg2 = $wpdb->get_results($qry2);
						  foreach($reg2 as $row2)
						  {
								$fileids .= $row2->value.',';
								  
						  }
					  }
				   }
				  }
				  
				  return explode(',',rtrim($fileids,','));
				  	
		}
		
		public function crf_get_entry_attachment_html($formid,$id)
		{
				/*file addon start */
				global $wpdb;
				$crf_fields=$wpdb->prefix."crf_fields";
				$textdomain = 'custom-registration-form-builder-with-submission-manager';
				  $qry1 = "select * from $crf_fields where Form_Id= '".$formid."' and Type in('file') order by ordering asc";
				  $reg1 = $wpdb->get_results($qry1);
				  $attachment_html = "";
				  if(!empty($reg1))
				  {
					 
				   foreach($reg1 as $row1)
				   {
					  if(!empty($row1))
					  {
						  $Customfield = $this->crf_get_field_key($row1);
						  $value = $this->crf_submision_field_value($id,$Customfield); 
						  if(trim($value)!='')
						  {
						  	  $values = explode(',',$value);
						  
							 // $attachment_html .=  '<div class="field-labal" ><p class="entry_heading" >'.$row1->Name.':</p>';	
							  foreach($values as $fileid)
							  {
								$attachment_html .='  <div class="crf-row-result crf-row-result-attachment">
							  <div class="crf-form-name">'.get_the_title( $fileid ).'</div>
							  <div class="crf-form-check">
								<input type="checkbox" name="selected[]" value="'.$fileid.'">
							  </div>
							  <div class="crf_attachment_details">'.wp_get_attachment_link($fileid,'full',false,true,false).'</div>
							  <div class="crf-row-result-button-area">
								<div class="crf-row-result-edit-button"><a href="admin.php?page=crf_manage_attachments&form_id='.$formid.'&file='.$fileid.'">'.__('Download',$textdomain).'</a></div>
							  </div>
							</div>';
								
							  }
							  
						  }
					  }
				   }
				
				  }
				  return $attachment_html;
			/*file addon end */	
		}
		
		public function crf_create_attachment_zip($ids)
		{
				ob_clean(); 
				$args = array(
						'post_type' => 'attachment',
						'posts_per_page' => -1,
						'post_status' =>'any',
						'include' => $ids);
					$zip = new ZipArchive();
					$filename = "../crf_attachments.zip";
					if ($zip->open($filename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)!==TRUE) {
						exit("cannot open <$filename>\n");
					}
					$attachments = get_posts( $args );
					foreach ( $attachments as $attachment ) {
						  // Get the file name
						//  $name = explode('/', get_attached_file($attachment->ID) );
						  //echo $name;
						  $name = basename( get_attached_file($attachment->ID) );
						  $zip->addFile(get_attached_file($attachment->ID), $name);
					  }
					$file = $zip->filename;
					$zip->close();
					return $file;
		}
		
		public function crf_download_file($file)
		{
			if (file_exists($file)) {
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="'.basename($file).'"');
					header('Expires: 0');
					readfile($file);
					exit;
				}	
		}
		public function crf_get_all_submission_attachment_attachments($entries,$form_id)
		{
			$html ='';
			$html .='<div class="crf-row-result-main" style="margin-left:0px;"><div class="crf-form-cutom-row">';
			foreach($entries as $entry)
			{
			 	$html .= $this->crf_get_entry_attachment_html($form_id,$entry->submission_id);
			}
			$html .= '</div></div>';
			return $html;
			
		}
		
		public function crf_get_submissions($entries,$form_id,$pagenum)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$crf_fields =$wpdb->prefix."crf_fields";	
			$qry = "select * from $crf_fields where form_id ='".$form_id."' and Type not in('heading','paragraph','file') order by ordering asc limit 4";
			$reg = $wpdb->get_results($qry);
			?>
<li class="header rows">
  <div class="cols" style="width:30px;"></div>
  <div class="cols" style="width:30px;">#</div>
  <?php
			foreach($reg as $row)
			{
				$key = $this->crf_get_field_key($row);
				?>
  <div class="cols" style="width:19%">
    <?php 
				$fieldnamelength = strlen($row->Name);
				if($fieldnamelength<=15){echo $row->Name;}
				else
				{
				$fieldnamehalf = substr($row->Name, 0, 15);
				echo $fieldnamehalf.'...';
				}?>
  </div>
  <?php
			}
			?>
</li>
<?php
			
			$i=1;
			if($pagenum>1)
			{
				$i = $i+ (($pagenum-1)*20);
			}
			
			foreach($entries as $entry)
			{
				
				if($i%2==0)
				{
					$class="";
				}
				else
				{
					$class="alternate";
				} ?>
<li class="<?php echo $class;?> rows">
  <div class="cols" style="width:30px;">
    <input type="checkbox" name="selected[]" value="<?php echo $entry->submission_id; ?>" />
  </div>
  <div class="cols" style="width:30px;"><a href="admin.php?page=crf_view_entry&id=<?php echo $entry->submission_id;?>"><?php echo $i; ?></a></div>
  <?php foreach($reg as $row){?>
  <div class="cols" style="width:19%">
    <?php 
					  $key = $this->crf_get_field_key($row);
					  $result = $this->crf_submision_field_value($entry->submission_id,$key);
				  $Valuehalf = substr($result, 0, 15);
				  if(strlen($result) < 15)
				  {
				  echo $result;
				  }
				  else
				  {
					echo $Valuehalf.'...'; 
				  }
				  ?>
  </div>
  <?php } ?>
  <div class="cols" style="width:50px"><a href="admin.php?page=crf_view_entry&id=<?php echo $entry->submission_id;?>">View</a></div>
</li>
<?php
			  $i++;
			}
			
			
		}
		
		public function crf_get_field_key($row)
		{
			
			if($row->Type=='first_name' || $row->Type=='last_name' || $row->Type=='description')
			{
				$key = $row->Type;	
			}
			else
			{
				if(isset($row->Field_Key) && $row->Field_Key!="")
				{
					$key = $row->Field_Key;	
				}
				else
				{
					$key = sanitize_key($row->Name).'_'.$row->Id;	
				}
			}
			return $key;	
		}
		
		
		
		public function crf_get_pagination($num_of_pages,$pagenum)
		{
			$args = array(
			'base'               => add_query_arg( 'pagenum', '%#%' ),
			'format'             => '',
			'total'              => $num_of_pages,
			'current'            => $pagenum,
			'show_all'           => False,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => True,
			'prev_text'          => __('&laquo;', 'text-domain' ),
			'next_text'          => __('&raquo;', 'text-domain'),
			'type'               => 'plain',
			'add_args'           => False,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => '' );
		
			$page_links = paginate_links( $args );
			if ( $page_links ) 
			{
				echo '<div class="tablenav crfpagination"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
			}
			
		}
		
		public function crf_get_all_form_list_option()
		{
			global $wpdb;
			$crf_forms =$wpdb->prefix."crf_forms";
			$qry = "select * from $crf_forms";
			$reg = $wpdb->get_results($qry);
			if(!empty($reg))
			{
				foreach($reg as $row)
				{
					?>
<option value="<?php echo $row->id;?>" <?php if($_REQUEST['form_id']==$row->id) echo 'selected';?>>
<?php 
				$formnamelength = strlen($row->form_name);
				if($formnamelength<=15){echo $row->form_name;}
				else
				{
				$formnamehalf = substr($row->form_name, 0, 15);
				echo $formnamehalf.'...';
				}?>
</option>
<?php
				}
			}	
		}
		
		
		/*users manager functions start*/
		public function crf_get_userrole_name($userid) 
		{
			global $wp_roles;
			$user_info = get_userdata($userid);
			$roles = $user_info->roles;
			$role = array_shift($roles);
			return isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role] ) : false;
		}
		
		public function crf_get_wp_users($entries,$pagenum)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";
			$path =  plugin_dir_url(__FILE__);	
			$current_user = wp_get_current_user();
			
			?>
<tr>
  <th></th>
  <th>#</tb>
  <th></th>
  <th>Username</th>
  <th>Email</th>
  <th>Name</th>
  <th></th>
  <th></th>
</tr>
<?php
			
			$i=1;
			if($pagenum>1)
			{
				$i = $i+ (($pagenum-1)*20);
			}

			
			foreach($entries as $entry)
			{
				//print_r($entry);
				$avatar = get_avatar($entry->user_email, 30 );
				$userstatus = get_user_meta($entry->ID, 'crf_user_status', true );
				if ($entry->ID == $current_user->ID )
				{
					$class="rm_current_user";				
				}
				else
				{
					$class="";
				} ?>
<tr class="rm_current_user">
  <td>
    <input type="checkbox" name="selected[]" value="<?php echo $entry->ID; ?>" />
  </td>
  <td><a href="admin.php?page=crf_view_wp_user&id=<?php echo $entry->ID;?>"><?php echo $i; ?></a></td>
  <td><a href="admin.php?page=crf_view_wp_user&id=<?php echo $entry->ID;?>"><?php echo $avatar;?></a></td>
  <td><?php echo $entry->user_login;?></td>
  <td><?php echo $entry->user_email;?></td>
  <td><?php echo $entry->display_name;?></td>
  <td class="rm_action_link">
  
  <?php if ($userstatus == 'deactivate' ): ?>
                   <a>Activate</a>
                   <?php else : ?>
				   <a>Deactivate</a>
				   <?php endif; ?>
  
  
  </td>
  <td><a href="admin.php?page=crf_view_wp_user&id=<?php echo $entry->ID;?>">View</a></td>
</tr>
<?php
			  $i++;
			}
			
			
			
		}
		
		public function crf_get_user_custom_field_data($user_id)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$qry = "select * from $crf_fields where `Visibility` = '2' order by ordering asc";
			$reg= $wpdb->get_results($qry);	
			$html = '';
			foreach($reg as $row)
			{
				$key = $this->crf_get_field_key($row);
				$value = get_user_meta($user_id, $key,true);
				$label = $row->Name;
				if(trim($value)!="" || !empty($value))
				{
					if($row->Type=='file')
					{
						  	  $values = explode(',',$value);
							  
							  
							  $html .=  '<div class="field">
							<div class="label">'.$label.'</div>';	
							  foreach($values as $fileid)
							  {  
								
								$html .= '<div class="value user_details_img">'.wp_get_attachment_link($fileid,'full',false,true,false).'</div>';
								
								
							  }
							  $html .='</div>';
						  
					}
					else
					{
							$html .= '<div class="field">
							<div class="label">'.$label.'</div>
							<div class="value">'.$value.'</div>
							</div>';	
					}
				}
					
			}
			return $html;
		}
		
		/*users manager functions end*/
		
		
		public function crf_assign_key_for_previous_field()
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$qry = "select * from $crf_fields order by ordering asc";
			$reg= $wpdb->get_results($qry);	
			foreach($reg as $row)
			{
				$key = $this->crf_get_field_key($row);
				if(empty($row->Field_Key))
				{
					 $wpdb->query($wpdb->prepare("update $crf_fields set Field_Key=%s where Id=%d",array($key,$row->Id)));	
				}
			}
		}
		
		public function crf_generate_otp($email){
			
            global $wpdb;
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
            $crf_users = $wpdb->prefix.'crf_users';
            $otp_code= wp_generate_password( 15, false);
            // Delete previous OTP
            $wpdb->delete( $wpdb->prefix.'crf_users', array( 'email' => $email ) );
            $wpdb->insert(
                $crf_users,
                array(
                    'email' => $email,
                    'otp_code' => $otp_code
                ),
                array('%s','%s')
            );
            $message = __('Your One Time Password (OTP) is ',$textdomain) .$otp_code. __(' It is valid for next 1 hour.',$textdomain);
            $wpdb->get_row("UPDATE ".$crf_users." SET `created_date` = `last_activity_time`");
            $headers = 'From: '.$this->crf_get_from_email(). "\r\n";
			$subject = __('One Time Password (OTP)',$textdomain);
            wp_mail( $email,$subject, $message, $headers );
		}
		
		
		public function crf_get_short_payment_details($submissions)
		{
			//print_r($submissions);die;
			?>
            <table class="crf_user_submissions">
              <tr>
                <th class="crf_submission_date">Date</th>
                <th class="crf_form_title">Transaction ID</th>
                <th class="crf_form_payment">Total Amount</th>
                <th class="crf_view_submission">Status</th>
              </tr>

            <?php
			foreach($submissions as $submission)
			{ 
				//print_r($submission);die;
				$this->crf_get_entry_payment_info_tabel($submission->form_id,$submission->submission_id); 
			}
        ?></table><?php
		}
		
		public function crf_get_entry_payment_info_tabel($formid,$id)
		{
			global $wpdb;
			$textdomain = 'custom-registration-form-builder-with-submission-manager';
			$crf_paypal_log=$wpdb->prefix."crf_paypal_log";
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "select `value` from $crf_submissions where submission_id='".$id."' and `field`='paypal_log_id'";	
			$logs = $wpdb->get_results($qry);
			$invoice = $this->crf_submision_field_value($id,'invoice');
			
			$html = '';
			if(!empty($invoice))
			{
				//$logid = $this->crf_submision_field_value($id,'paypal_log_id');	
				$total_amount = $this->crf_get_entry_pricing_fields_total($formid,$id);
				//print_r($total_amount);die;
				$payment_status = $this->crf_submision_field_value($id,'payment_status');
					
				if(!empty($logs))
				{
					foreach($logs as $log)
					{
						$logid = $log->value;
						$qry1 = "select log from $crf_paypal_log where id=".$logid;
						$log = maybe_unserialize($wpdb->get_var($qry1));
						?>
                        <tr>
                        <td class="crf_submission_date"><?php if(isset($log['payment_date'])) echo $log['payment_date'];?></td>
                        <td class="crf_form_title"><?php if(isset($log['txn_id'])) echo $log['txn_id'];?></td>
                        <td class="crf_form_payment"><?php if(isset($log['mc_gross'])) echo $log['mc_gross'];?></td>
                        <td class="crf_view_submission"><?php if(isset($log['payment_status'])) echo $log['payment_status']; ?></td>
                      </tr>
                        <?php
						
					}
                   	
				}
				else
				{
					?>
                        <tr>
                        <td class="crf_submission_date"><?php echo 'NA';?></td>
                        <td class="crf_form_title"><?php echo 'NA'; ?></td>
                        <td class="crf_form_payment"><?php if(isset($total_amount)) echo $total_amount;?></td>
                        <td class="crf_view_submission"><?php echo $payment_status; ?></td>
                      </tr>
                        <?php
				}
			}
			return $html;
			
			
		}
		
		public function crf_get_entry_pricing_fields_total($formid,$id)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$qry1 = "select * from $crf_fields where Form_Id= '".$formid."' and Type in('pricing') order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			//print_r($reg1);die;
			
			$total_amount = 0;
			 if(!empty($reg1))
				  {
				   foreach($reg1 as $row1)
				   {
					  if(!empty($row1))
					  {
						  $Customfield = $this->crf_get_field_key($row1);
						  $value = $this->crf_submision_field_value($id,$Customfield); 
						  //print_r($value);
						  if(trim($value)!='')
						  {
							  $values = explode(',',$value);
							  $count = count($values);
							  $i = 1;
							  foreach($values as $val)
							  {
								  
								$optionval = explode('_',$val);
								
								//print_r($optionval);
								if(strpos($val,'_')===false)
								 {
									$total_amount = $total_amount + (float)$optionval[0];
									
								 }
								 else
								 {
									$total_amount = $total_amount + (float)$optionval[1];
								 }
								$i++;
							  }
						  }
					  }
				   }
				  }
				  return $total_amount;
				  //return $total_amount;	
		}
		
}
?>
