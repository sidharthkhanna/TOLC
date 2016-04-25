<?php
/*Used during custom field creation - Cross checks if the custom field being created already exists or not*/
global $wpdb;
$textdomain = 'RegistrationMagicPlatinum';
$crf_paypal_fields =$wpdb->prefix."crf_paypal_fields";
if(isset($_POST['name']) && trim($_POST['name'])=="")
{
	echo '<div style=" color:red">'.__( 'Warning! Field label is required. Please enter a unique label.', $textdomain ).'</div>';		
}
if($_POST['prev']!='new')
{
	$qry = "select count(*) from $crf_paypal_fields where Name ='".$_POST['name']."' and Name !='".$_POST['prev']."'";
	$result = $wpdb->get_var($qry);
	if($result!=0)
	{
		echo '<div style=" color:red">'.__('Warning! Field label already exists. Please choose a unique label.',$textdomain ).'</div>';	
	}
}
else
{
	$qry = "select count(*) from $crf_paypal_fields where Name ='".$_POST['name']."'";
	$result = $wpdb->get_var($qry);
	if($result!=0)
	{
		echo '<div style=" color:red">'.__('Warning! Field label already exists. Please choose a unique label.',$textdomain ).'</div>';	
	}
}
?>