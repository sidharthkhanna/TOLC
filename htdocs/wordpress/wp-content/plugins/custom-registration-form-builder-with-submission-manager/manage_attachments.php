<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_submissions =$wpdb->prefix."crf_submissions";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
$form_fields = new crf_basic_fields;
if(isset($_REQUEST['file']))
{
ob_clean(); 
	$file = get_attached_file($_REQUEST['file']);
	$form_fields->crf_download_file($file);		
}
if(isset($_REQUEST['form_id']))
{
	$form_id = $_REQUEST['form_id'];		
}
else
{
	$qry = "select id from $crf_forms order by id asc limit 1";
    $reg = $wpdb->get_var($qry);
	$form_id = $_REQUEST['form_id']=$reg;
}
if(!empty($_POST['selected']) && isset($_POST['download_selected']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_entries' ) ) die( 'Failed security check' );
	$ids = implode(',',$_POST['selected']);
	$file =  $form_fields->crf_create_attachment_zip($_POST['selected']);
	$form_fields->crf_download_file($file);
	//echo $file;
	
	
}
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$i = $offset;
if($_REQUEST['form_id']!="")
{
 	$attchmentids = $form_fields->crf_get_all_attachments_ids($_REQUEST['form_id']);
	//print_r($attchmentids);die;
	$total = count($attchmentids);
  	$num_of_pages = ceil( $total / $limit );
	if(($total-$offset)<=$limit) 
	{
		$total_records =($total-$offset) + $offset;
	}
	else
	{
		$total_records = $offset +$limit;
	}
	//echo $total_records;die;
	$max_record = $total_records;
	//echo $max_record;die;
}
//print_r($attchmentids);die;
?>
<form name="field_list" id="field_list" method="post">
  <?php wp_nonce_field('manage_crf_entries'); ?>
  <div class="crf-main-form">
    <div class="crf-form-heading">
      <h1>
        <?php _e('Attachments', $textdomain ); ?>
        <?php /*?><div class="crf-filter"><img src="<?php echo $path;?>images/filter.png"></div><?php */?>
      </h1>
    </div>
    <div  class="crf-add-remove-field-submissions crf-new-buttons">
      <?php if(!empty($attchmentids[0])):?>
      <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Download All</a>
      </div>
        <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Download Selected</a>
      </div>
      <?php endif;?>
      <select name="form_id" id="form_id" onChange="redirectform(this.value,'crf_manage_attachments')">
        <?php
   $form_fields->crf_get_all_form_list_option();
    ?>
      </select>
    </div>
  </div>
  <div class="crf-row-result-main">
    <?php
if(empty($attchmentids[0]))
{
?>
    <ul id="sortable" class="crf_entries">
      <li class="rows">
        <div class="cols">
          <?php _e('There are no attachments in this form submissions.', $textdomain ); ?>
        </div>
      </li>
    </ul>
    <?php
}
else
{
	while($i<$max_record)
	{
		$fileid = $attchmentids[$i];
		//echo $fileid;die;
		$title = get_the_title( $fileid );
		
		$length = strlen($title);
		if($length>12)$title = substr($title, 0, 12);
		?>
    <div class="crf-row-result crf-row-result-attachment">
      <div class="crf-form-name"><?php echo $title;?></div>
      <div class="crf-form-check">
        <input type="checkbox" name="selected[]" value="<?php echo $fileid;?>">
      </div>
      <div class="crf_attachment_details"><?php echo wp_get_attachment_link($fileid,'full',false,true,false);?></div>
      <div class="crf-row-result-button-area">
        <div class="crf-row-result-edit-button"><a href="admin.php?page=crf_manage_attachments&form_id=<?php echo $formid;?>&file=<?php echo $fileid;?>">
          <?php _e('Download',$textdomain); ?>
          </a></div>
      </div>
    </div>
    <?php
							$i++;
		
	}
}
?>
    <?php
$args = array(
	'base'               => add_query_arg( 'pagenum', '%#%' ),
	'format'             => '',
	'total'              => $num_of_pages,
	'current'            => $pagenum,
	'show_all'           => False,
	'end_size'           => 1,
	'mid_size'           => 2,
	'prev_next'          => True,
	'prev_text'          => __('Prev'),
	'next_text'          => __('Next'),
	'type'               => 'array',
	'add_args'           => False,
	'add_fragment'       => '',
	'before_page_number' => '',
	'after_page_number'  => ''
);
$page_links = paginate_links( $args );
$count	= count($page_links);
$prev = strpos($page_links[0],__('Prev'));
$next = strpos($page_links[$count-1],__('Next'));
if($prev!=false)
{
?>
    <div class="crf-row-result pagination prev"> <?php echo $page_links[0]; ?> </div>
    <?php
}
if($next!=false)
{
?>
    <div class="crf-row-result pagination next"> <?php echo $page_links[$count-1]; ?> </div>
    <?php
}	
?>
  </div>
</form>
<div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner" style="width:670px;">Attachments browser allows you to easily view and download attachments sent by the users through forms. <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Upgrade to Silver Edition</a>, to add file upload field to your forms.</div></div>
<script>
    function redirectform(id) {
        window.location = 'admin.php?page=crf_manage_attachments&form_id=' + id;
    }
	
	jQuery('.crf-filter').click(function () {
		jQuery('.crf-search-submissions').toggle("blind",{},1000);
    });
	
</script>