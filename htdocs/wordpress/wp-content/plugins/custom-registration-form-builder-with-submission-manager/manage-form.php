<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
?>
<div class="updated">
	<p><?php _e( 'To show login box on a page, you can use Shortcode [CRF_Login]', $textdomain ); ?></p>
</div>
<?php
$crf_forms =$wpdb->prefix."crf_forms";
$crf_submissions =$wpdb->prefix."crf_submissions";
$path =  plugin_dir_url(__FILE__); 
if(!empty($_POST['selected']) && isset($_POST['copy']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_form' ) ) die( 'Failed security check' );
	$ids = implode(',',$_POST['selected']);
	$query = "select * from $crf_forms where id in($ids)";
	$results = $wpdb->get_results($query);
	if(!empty($results))
	{
	foreach($results as $entry)
	{
		$qry = "insert into $crf_forms values('','".$entry->form_name."','".$entry->form_desc."','".$entry->form_type."','".$entry->custom_text."','".$entry->crf_welcome_email_subject."','".$entry->success_message."','".$entry->crf_welcome_email_message."','".$entry->redirect_option."','".$entry->redirect_page_id."','".$entry->redirect_url_url."','".$entry->send_email."','".$entry->form_option."')";	
		$wpdb->query($qry);	
	}
	}
}
if(!empty($_POST['selected']) && isset($_POST['delete']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_form' ) ) die( 'Failed security check' );
	$ids = implode(',',$_POST['selected']);
	$query = "delete from $crf_forms where id in($ids)";
	$wpdb->get_results($query);
}
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$qry = "SELECT * FROM $crf_forms";
$total = $wpdb->get_var( "SELECT count(*) FROM $crf_forms" );
$num_of_pages = ceil( $total / $limit );
$entries = $wpdb->get_results( "SELECT * FROM $crf_forms order by id desc LIMIT $offset, $limit" );
?>
<form name="forms" id="forms" method="post" action="admin.php?page=crf_manage_forms" >
<?php wp_nonce_field('manage_crf_form'); ?>
<div class="ucf_pro_banner" style="margin-bottom:0 !important; overflow:visible;">
<div class="" id="bannerclose">
      <img src="<?php echo $path;?>images/rm_banner.png" />
     </div>
</div>
  <div class="crf-main-form" style=" margin-top:15px;">
    <div class="crf-main-form-top-area">
      <div class="crf-form-name-heading">
        <h1><?php _e( 'All Forms', $textdomain ); ?></h1>
      </div>
      <div class="crf-form-name-buttons">
        <div class="crf-setting"><a href="admin.php?page=crf_settings"><img src="<?php echo $path; ?>images/global_settings.png"></a></div>
      </div>
    </div>
    <div class="crf-new-buttons grayout_buttons"><span class="crf-add-new-button"><a href="admin.php?page=crf_add_form"><?php _e( 'Add New', $textdomain ); ?></a></span> <span class="crf-duplicate-button">
      <input type="submit" name="copy" id="copy" value="Duplicate" disabled >
      </span> <span class="crf-remove-button">
      <input type="submit" name="delete" id="delete" value="Remove" onClick="return popup()" disabled>
      </span>
      
      
      </div>
  </div>
  <div class="crf-row-result-main">
   <!--HTML when there is not create any form--> 
      <div class="crf-row-result" style="height:172px !important; overflow:hidden;">
      <div class="add-new-form">
        <a href="" onClick="return add_form_box()">
          <div class="theme-screenshot">
            <span></span>
           
          </div>
           <h2 class="theme-name"><?php _e('Add A',$textdomain);?> <br> <?php _e('Quick Form',$textdomain);?></h2>
          
        </a>
      </div>
      
      <div class="crf-form-setting add-new-form-box" style="padding:0px;margin-bottom: 0px; display:none;">
      <div class="crf-form-name"><?php _e('New Form Name',$textdomain);?></div>
     <div class="crf-form-right-area" style="width:100%; margin-bottom:57px;" align="center">
         <input type="text" name="form_name" id="form_name" class="" style="width:90%" />
      </div>
  
      <div class="crf-row-result-button-area">
        <div class="crf-row-result-edit-button"><a style="cursor:pointer;" onClick="add_form_ajax()">Add</a></div>
        <div class="crf-row-result-preview-button"><a href="#" onClick="return cancel_form()">Cancel</a></div>
      </div>
    </div>
      
      
      
      
      </div>
   
    <!--HTML when there is not create any form--> 
  
  
    <?php
if(empty($entries))
{
?>
    <!--HTML when there is not create any form-->  
    <ul id="sortable" class="crf_entries">
      <li class="rows" style="margin-top:20px !important;">
        <div class="cols"><?php _e( 'You have not created any forms yet. Once you have created a new form, it will appear here.', $textdomain ); ?></div>
      </li>
    </ul>
    <?php 
}
else
{
$i = 1;	
foreach($entries as $row)
{
	$qry = "select count(distinct(submission_id)) from $crf_submissions where form_id=".$row->id;
	$submission = $wpdb->get_var($qry);
	if($submission>100)
	{
		$submission='99+';
	}
?>
    <!--HTML when there are already custom fields associated with selected user role-->  
    <div class="crf-row-result">
      <div class="crf-form-name">
        <?php 
$length = strlen($row->form_name);
if($length<=12){echo $row->form_name;}
else
{
$Valuehalf = substr($row->form_name, 0, 12);
echo $Valuehalf.'...';
}
?>
      </div>
      <div class="crf-form-check">
        <input type="checkbox" name="selected[]" value="<?php echo $row->id;?>">
      </div>
      <div class="crf-form-submissions"><?php _e( 'Submissions', $textdomain ); ?></div>
      <div class="crf-form-rest"><a href="admin.php?page=crf_entries&form_id=<?php echo $row->id;?>"><?php echo $submission;?></a></div>
      <div class="crf-form-shortcode"><?php _e( 'Shortcode', $textdomain ); ?></div>
      <div class="crf-form-shortcode-name crf-copy" title="Click to copy to clipboard." data-clipboard-text="[CRF_Form id='<?php echo $row->id;?>']">[CRF_Form id='<?php echo $row->id;?>']</div>
      <div class="crf-form-shortcode-type"><?php _e( 'WP Registration', $textdomain ); ?></div>
      <div class="crf-form-shortcode-type-name">
        <?php if($row->form_type=='reg_form')_e( 'On', $textdomain ); else _e( 'Off', $textdomain );?>
      </div>
      <div  class="crf-row-result-button-area">
        <div class="crf-row-result-edit-button"><a href="admin.php?page=crf_add_form&id=<?php echo $row->id;?>"><?php _e( 'Edit', $textdomain ); ?></a></div>
        <div class="crf-row-result-preview-button"><a href="admin.php?page=crf_manage_form_fields&form_id=<?php echo $row->id;?>"><?php _e( 'Fields', $textdomain ); ?></a></div>
      </div>
    </div>
    <?php
		$i++;
}
}
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
<script>
    jQuery(document).ready(function () {
        var a = jQuery('.crf-row-result-main .crf-row-result');
        for (var i = 0; i < a.length; i += 4) {
            a.slice(i, i + 4).wrapAll('<div class="crf-form-cutom-row"></div>');
        }
    });
    
	jQuery('.crf-row-result-main a').mouseover(function() {
	jQuery(this).children('h2').css('color','#fff');
}).mouseleave(function() {
	jQuery(this).children('h2').css('color','#ff6c6c');
});
jQuery('.add-new-form').mouseover(function() {
	jQuery(this).children('h2').css('color','#fff');
}).mouseleave(function() {
	jQuery(this).children('h2').css('color','#ff6c6c');
});
	
	
	jQuery(".add-new-form span").mouseover(function() {
	jQuery(this).children('h2').css('color','#fff');
}).mouseleave(function() {
	jQuery(this).children('h2').css('color','#ff6c6c');
});
	
</script>
<script>
function add_form_ajax()
{
		name = jQuery("#form_name").val();
		if(jQuery.trim(name)=='')
		{
			alert('Please Enter the Form Name');
			return false;
		}
		else
		{
		
        jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=crf_add_ajax_form&cookie=encodeURIComponent(document.cookie)', {
                'name': name
            },
            function (data) {
                //make ajax call to check_username.php
				//alert(data)
                if (jQuery.trim(data) == "form created") {
					/*jQuery('.add-new-form').show(500);
					jQuery('.add-new-form-box').hide(500);	
					return false;*/
					location.reload(true);
                } else {
					jQuery('.add-new-form').hide(500);
					jQuery('.add-new-form-box').show(500);	
          
                }
                //dump the data received from PHP page
            });	
			
		}
}
function cancel_form()
{
		jQuery('.add-new-form').show("blind",{},1000);
		jQuery('.add-new-form-box').hide("blind",{},1000);	
		return false;
}
function add_form_box()
{
	jQuery('.add-new-form').hide("blind",{},1000);
	jQuery('.add-new-form-box').show("blind",{},1000);	
	return false;
}
    function popup() {
        a = confirm("<?php _e('This will delete the form(s) permanently. Please confirm.', $textdomain ); ?>");
        if (a == true) {
            return true;
        } else {
            return false;
        }
    }
	
function bannertoggle()
{
	a = jQuery('#bannerclose .close a').text();
	if(a=='Close'){jQuery('#bannerclose .close a').text('Open');}else{jQuery('#bannerclose .close a').text('Close');}
	jQuery('.bottem-shap').toggle(500);
	return false;
}
</script>
<script>
var swfPath = ZeroClipboard.config("swfPath");
var client = new ZeroClipboard( jQuery(".crf-copy") );
</script>
<script>
function crftooltip1(a)
{
	jQuery(a).tooltip({
		content:'Copy to Clipboard'		
		});	
}
function crftooltip2(a)
{
	jQuery(a).tooltip({
		content:'Copied!'
		});	
}
</script>
<style>
.crf-copy {
	cursor:pointer; 
  }
  .ui-tooltip {
    padding: 5px 10px;
    color: white;
	background:#000;
	border:none;
	box-shadow:none;
  }
</style>