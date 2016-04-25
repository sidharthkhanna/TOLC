<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_option=$wpdb->prefix."crf_option";
$path =  plugin_dir_url(__FILE__); 
$general_options = new crf_basic_options;
if(isset($_REQUEST['saveoption']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_paypal_setting' ) ) die( 'Failed security check' );
	update_option( 'crf_gateway',$_POST['crf_gateway'] );	
	update_option( 'crf_test_mode',$_POST['crf_test_mode']);	
	update_option( 'crf_paypal_email',$_POST['crf_paypal_email']);	
	update_option( 'crf_currency',$_POST['crf_currency']);
	update_option( 'crf_paypal_page_style',$_POST['crf_paypal_page_style']);	
	update_option( 'crf_currency_position',$_POST['crf_currency_position']);	
	wp_redirect('admin.php?page=crf_settings');exit;	
}
$crf_currency = get_option('crf_currency');
?>
<div class="crf-main-form">
  <div class="crf-form-heading">
    <h1>
      <?php _e( 'Payments', $textdomain ); ?>
    </h1>
  </div>
  <form method="post">
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Payment Processor', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <select name="crf_gateway" id="crf_gateway">
          <option value="paypal" <?php selected( get_option('crf_gateway'),'paypal');?>><?php _e('PayPal',$textdomain);?></option>
        </select>
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Test Mode:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="crf_test_mode" id="crf_test_mode" type="checkbox" class="upb_toggle" value="yes" <?php checked(get_option('crf_test_mode','no'), 'yes' );?> style="display:none;" />
        <label for="crf_test_mode"></label>
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'PayPal Email', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input type="text" name="crf_paypal_email" id="crf_paypal_email" value="<?php echo get_option('crf_paypal_email'); ?>" />
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Currency', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <select name="crf_currency" id="crf_currency">
          <option value="USD" <?php selected( $crf_currency,'USD');?>><?php _e('US Dollars',$textdomain);?> ($)</option>
          <option value="EUR" <?php selected( $crf_currency,'EUR');?>><?php _e('Euros',$textdomain);?> (&euro;)</option>
          <option value="GBP" <?php selected( $crf_currency,'GBP');?>><?php _e('Pounds Sterling',$textdomain);?> (&pound;)</option>
          <option value="AUD" <?php selected( $crf_currency,'AUD');?>><?php _e('Australian Dollars',$textdomain);?> ($)</option>
          <option value="BRL" <?php selected( $crf_currency,'BRL');?>><?php _e('Brazilian Real',$textdomain);?> (R$)</option>
          <option value="CAD" <?php selected( $crf_currency,'CAD');?>><?php _e('Canadian Dollars',$textdomain);?> ($)</option>
          <option value="CZK" <?php selected( $crf_currency,'CZK');?>><?php _e('Czech Koruna',$textdomain);?></option>
          <option value="DKK" <?php selected( $crf_currency,'DKK');?>><?php _e('Danish Krone',$textdomain);?></option>
          <option value="HKD" <?php selected( $crf_currency,'HKD');?>><?php _e('Hong Kong Dollar',$textdomain);?> ($)</option>
          <option value="HUF" <?php selected( $crf_currency,'HUF');?>><?php _e('Hungarian Forint',$textdomain);?></option>
          <option value="ILS" <?php selected( $crf_currency,'ILS');?>><?php _e('Israeli Shekel',$textdomain);?> (&#x20aa;)</option>
          <option value="JPY" <?php selected( $crf_currency,'JPY');?>><?php _e('Japanese Yen',$textdomain);?> (&yen;)</option>
          <option value="MYR" <?php selected( $crf_currency,'MYR');?>><?php _e('Malaysian Ringgits',$textdomain);?></option>
          <option value="MXN" <?php selected( $crf_currency,'MXN');?>><?php _e('Mexican Peso',$textdomain);?> ($)</option>
          <option value="NZD" <?php selected( $crf_currency,'NZD');?>><?php _e('New Zealand Dollar',$textdomain);?> ($)</option>
          <option value="NOK" <?php selected( $crf_currency,'NOK');?>><?php _e('Norwegian Krone',$textdomain);?></option>
          <option value="PHP" <?php selected( $crf_currency,'PHP');?>><?php _e('Philippine Pesos',$textdomain);?></option>
          <option value="PLN" <?php selected( $crf_currency,'PLN');?>><?php _e('Polish Zloty',$textdomain);?></option>
          <option value="SGD" <?php selected( $crf_currency,'SGD');?>><?php _e('Singapore Dollar',$textdomain);?> ($)</option>
          <option value="SEK" <?php selected( $crf_currency,'SEK');?>><?php _e('Swedish Krona',$textdomain);?></option>
          <option value="CHF" <?php selected( $crf_currency,'CHF');?>><?php _e('Swiss Franc',$textdomain);?></option>
          <option value="TWD" <?php selected( $crf_currency,'TWD');?>><?php _e('Taiwan New Dollars',$textdomain);?></option>
          <option value="THB" <?php selected( $crf_currency,'THB');?>><?php _e('Thai Baht',$textdomain);?> (&#3647;)</option>
          <option value="INR" <?php selected( $crf_currency,'INR');?>><?php _e('Indian Rupee',$textdomain);?> (&#x20B9;)</option>
          <option value="TRY" <?php selected( $crf_currency,'TRY');?>><?php _e('Turkish Lira',$textdomain);?> (&#8378;)</option>
          <option value="RIAL" <?php selected( $crf_currency,'RIAL');?>><?php _e('Iranian Rial',$textdomain);?></option>
          <option value="RUB" <?php selected( $crf_currency,'RUB');?>><?php _e('Russian Rubles',$textdomain);?></option>
        </select>
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'PayPal Page Style', $textdomain ); ?>
          <small>(<?php _e('Enter the name of the page style to use, or leave blank for default');?>)</small>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
         <input type="text" name="crf_paypal_page_style" id="crf_paypal_page_style" value="<?php echo get_option('crf_paypal_page_style'); ?>" />
      </div>
    </div>
    
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Currency Symbol Position', $textdomain ); ?>
          <small>(<?php _e('Choose the location of the currency sign.',$textdomain);?>)</small>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
         <select id="crf_currency_position" name="crf_currency_position">
         <option value="before" <?php selected(get_option('crf_currency_position'),'before');?>><?php _e('Before - $10',$textdomain);?></option>
         <option value="after" <?php selected(get_option('crf_currency_position'),'after');?>><?php _e('After - 10$',$textdomain);?></option>
         </select>
      </div>
    </div>
    
    <br>
    <br>
    <div class="crf-form-footer">
      <div class="crf-form-button">
        <?php wp_nonce_field('save_crf_paypal_setting'); ?>
        <input type="submit"  class="button-primary" value="<?php _e('Save',$textdomain);?>" name="saveoption" id="saveoption" />
        <a href="admin.php?page=crf_settings" class="cancel_button">
        <?php _e('Cancel',$textdomain);?>
        </a> </div>
    </div>
  </form>
</div>
