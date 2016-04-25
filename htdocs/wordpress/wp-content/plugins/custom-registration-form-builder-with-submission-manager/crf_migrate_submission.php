<?php
$path =  plugin_dir_url(__FILE__);
$textdomain = 'custom-registration-form-builder-with-submission-manager';
?>
<div class="crf_message">
  <div class="crf-form-heading">
  <div class="crf_message2">
  <h2 class="crf_message_heading"><?php _e('This will upgrade your old version content to new version',$textdomain);?></h2>
  <p class="crf_message_paragraph"><?php _e('It may take few minutes to complete. Please do not interrupt the
process once started.',$textdomain);?></p>
	</div>
  <div class="progressbar"></div>
  <div class="crf-form-button">
  <input type="button" name="migration" id="migration" onClick="start_migration()" value="Migrate" />
  </div>
  </div>
</div>
<script>
function start_migration()
{
	jQuery('.progressbar').html('<img src="<?php echo $path;?>images/ajax-loader.gif" />');
	jQuery('.crf_message2 h2.crf_message_heading').html('<?php _e('Upgrading in progress. Please wait.',$textdomain);?>');
	jQuery('.crf_message2 p.crf_message_paragraph').html('<?php _e('Do not close or refresh the window.',$textdomain);?>');
	jQuery('.crf_message .crf-form-button #migration').hide();
	
	jQuery.ajax({
                type: "POST",
                url: '<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=crf_start_migrate_submission&cookie=encodeURIComponent(document.cookie)',
                success: function (serverResponse) {
                    if (serverResponse.trim() == "submission migrated successful") {
						jQuery('.crf_message2 h2.crf_message_heading').html('<div class="dashicons dashicons-yes"></div>');
						jQuery('.crf_message2 p.crf_message_paragraph').addClass('crf_black');
                        jQuery('.crf_message2 p.crf_message_paragraph').html('<?php _e('All Done! Thank you.',$textdomain);?>');
						jQuery('.progressbar').html('');
						jQuery('.crf_message .crf-form-button').html('<a href="admin.php?page=crf_manage_forms">Continue</a>');
                    } else {
						jQuery('.crf_message2 p.crf_message_paragraph').addClass('crf_black');
						jQuery('.crf_message2 h2.crf_message_heading').html('<div class="dashicons dashicons-no-alt"></div>');
                        jQuery('.crf_message2 p.crf_message_paragraph').html('<?php _e('Sorry, the migration failed! Please try again.',$textdomain);?>');
						jQuery('.progressbar').html('');
						jQuery('.crf_message .crf-form-button').html('<a href="admin.php?page=crf_migrate_submission">Try Again</a>');
						
                  }
                }
            })
}
</script>