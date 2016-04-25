<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_option=$wpdb->prefix."crf_option";
$path =  plugin_dir_url(__FILE__); 
$anti_spam_options = new crf_basic_options;
if(isset($_REQUEST['saveoption']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_antispam_setting' ) ) die( 'Failed security check' );
	if(!isset($_REQUEST['enable_captcha'])) $_REQUEST['enable_captcha']='no';
	$anti_spam_options->crf_add_option( 'enable_captcha', $_REQUEST['enable_captcha']);
	$anti_spam_options->crf_add_option( 'public_key', $_REQUEST['publickey']);
	$anti_spam_options->crf_add_option( 'private_key', $_REQUEST['privatekey']);
	update_option( 'ucf_enable_captcha_login',$_POST['enable_captcha_login']);	
	update_option('crf_recaptcha_lang',$_POST['crf_recaptcha_lang']);
	update_option('crf_recaptcha_request_method',$_POST['request_method']);
	wp_redirect('admin.php?page=crf_settings');exit;
}
$public_key = $anti_spam_options->crf_get_global_option_value('public_key');
$private_key = $anti_spam_options->crf_get_global_option_value('private_key');
?>
<div class="crf-main-form">
  <div class="crf-form-heading">
    <h1>
      <?php _e( 'Anti Spam', $textdomain ); ?>
    </h1>
  </div>
  <form method="post">
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Enable reCAPTCHA:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="enable_captcha" id="enable_captcha" type="checkbox" class="upb_toggle" value="yes" <?php if ($anti_spam_options->checkfieldname("enable_captcha","yes")==true){ echo "checked";}?> style="display:none;"/>
        <label for="enable_captcha"></label>
      </div>
    </div>
    <div class="option-main ">
      <div id="captcha_fun" <?php if ($anti_spam_options->checkfieldname("enable_captcha","yes")==true){ echo 'style="display:block"';}else{echo 'style="display:none"';}?>>
        <div class="option-main crf-form-setting">
          <div class="user-group crf-form-left-area">
            <div class="crf-label">
              <?php _e( 'reCAPTCHA Language:', $textdomain ); ?>
            </div>
          </div>
          <div class="user-group-option crf-form-right-area">
            <select name="crf_recaptcha_lang" id="crf_recaptcha_lang">
              <option value=""> Select from common languages </option>
              <option value="ar" <?php selected(get_option('crf_recaptcha_lang'),'ar'); ?>> Arabic </option>
              <option value="af" <?php selected(get_option('crf_recaptcha_lang'),'af'); ?>> Afrikaans </option>
              <option value="am" <?php selected(get_option('crf_recaptcha_lang'),'am'); ?>> Amharic </option>
              <option value="hy" <?php selected(get_option('crf_recaptcha_lang'),'hy'); ?>> Armenian </option>
              <option value="az" <?php selected(get_option('crf_recaptcha_lang'),'az'); ?>> Azerbaijani </option>
              <option value="eu" <?php selected(get_option('crf_recaptcha_lang'),'eu'); ?>> Basque </option>
              <option value="bn" <?php selected(get_option('crf_recaptcha_lang'),'bn'); ?>> Bengali </option>
              <option value="bg" <?php selected(get_option('crf_recaptcha_lang'),'bg'); ?>> Bulgarian </option>
              <option value="ca" <?php selected(get_option('crf_recaptcha_lang'),'ca'); ?>> Catalan </option>
              <option value="zh-CN" <?php selected(get_option('crf_recaptcha_lang'),'zh-CN'); ?>> Chinese (China) </option>
              <option value="zh-HK" <?php selected(get_option('crf_recaptcha_lang'),'zh-HK'); ?>> Chinese (Hong Kong) </option>
              <option value="zh-TW" <?php selected(get_option('crf_recaptcha_lang'),'zh-TW'); ?>> Chinese (Taiwan) </option>
              <option value="hr" <?php selected(get_option('crf_recaptcha_lang'),'hr'); ?>> Croatian </option>
              <option value="cs" <?php selected(get_option('crf_recaptcha_lang'),'cs'); ?>> Czech </option>
              <option value="da" <?php selected(get_option('crf_recaptcha_lang'),'da'); ?>> Danish </option>
              <option value="nl" <?php selected(get_option('crf_recaptcha_lang'),'nl'); ?>> Dutch </option>
              <option value="en" <?php selected(get_option('crf_recaptcha_lang'),'en'); ?>> English (US) </option>
              <option value="en-GB" <?php selected(get_option('crf_recaptcha_lang'),'en-GB'); ?>> English (UK) </option>
              <option value="et" <?php selected(get_option('crf_recaptcha_lang'),'et'); ?>> Estonian </option>
              <option value="fil" <?php selected(get_option('crf_recaptcha_lang'),'fil'); ?>> Filipino </option>
              <option value="fi" <?php selected(get_option('crf_recaptcha_lang'),'fi'); ?>> Finnish </option>
              <option value="fr-CA" <?php selected(get_option('crf_recaptcha_lang'),'fr-CA'); ?>> French (Canadian) </option>
              <option value="fr" <?php selected(get_option('crf_recaptcha_lang'),'fr'); ?>> French (France) </option>
              <option value="gl" <?php selected(get_option('crf_recaptcha_lang'),'gl'); ?>> Galician </option>
              <option value="ka" <?php selected(get_option('crf_recaptcha_lang'),'ka'); ?>> Georgian </option>
              <option value="de" <?php selected(get_option('crf_recaptcha_lang'),'de'); ?>> German </option>
              <option value="de-AT" <?php selected(get_option('crf_recaptcha_lang'),'de-AT'); ?>> German (Austria) </option>
              <option value="de-CH" <?php selected(get_option('crf_recaptcha_lang'),'de-CH'); ?>> German (Switzerland) </option>
              <option value="el" <?php selected(get_option('crf_recaptcha_lang'),'el'); ?>> Greek </option>
              <option value="gu" <?php selected(get_option('crf_recaptcha_lang'),'gu'); ?>> Gujarati </option>
              <option value="iw" <?php selected(get_option('crf_recaptcha_lang'),'iw'); ?>> Hebrew </option>
              <option value="hi" <?php selected(get_option('crf_recaptcha_lang'),'hi'); ?>> Hindi </option>
              <option value="hu" <?php selected(get_option('crf_recaptcha_lang'),'hu'); ?>> Hungarian </option>
              <option value="is" <?php selected(get_option('crf_recaptcha_lang'),'is'); ?>> Icelandic </option>
              <option value="id" <?php selected(get_option('crf_recaptcha_lang'),'id'); ?>> Indonesian </option>
              <option value="it" <?php selected(get_option('crf_recaptcha_lang'),'it'); ?>> Italian </option>
              <option value="ja" <?php selected(get_option('crf_recaptcha_lang'),'ja'); ?>> Japanese </option>
              <option value="kn" <?php selected(get_option('crf_recaptcha_lang'),'kn'); ?>> Kannada </option>
              <option value="ko" <?php selected(get_option('crf_recaptcha_lang'),'ko'); ?>> Korean </option>
              <option value="lo" <?php selected(get_option('crf_recaptcha_lang'),'lo'); ?>> Laothian </option>
              <option value="lv" <?php selected(get_option('crf_recaptcha_lang'),'lv'); ?>> Latvian </option>
              <option value="lt" <?php selected(get_option('crf_recaptcha_lang'),'lt'); ?>> Lithuanian </option>
              <option value="ms" <?php selected(get_option('crf_recaptcha_lang'),'ms'); ?>> Malay </option>
              <option value="ml" <?php selected(get_option('crf_recaptcha_lang'),'ml'); ?>> Malayalam </option>
              <option value="mr" <?php selected(get_option('crf_recaptcha_lang'),'mr'); ?>> Marathi </option>
              <option value="mn" <?php selected(get_option('crf_recaptcha_lang'),'mn'); ?>> Mongolian </option>
              <option value="no" <?php selected(get_option('crf_recaptcha_lang'),'no'); ?>> Norwegian </option>
              <option value="ps" <?php selected(get_option('crf_recaptcha_lang'),'ps'); ?>> Pashto </option>
              <option value="fa" <?php selected(get_option('crf_recaptcha_lang'),'fa'); ?>> Persian </option>
              <option value="pl" <?php selected(get_option('crf_recaptcha_lang'),'pl'); ?>> Polish </option>
              <option value="pt" <?php selected(get_option('crf_recaptcha_lang'),'pt'); ?>> Portuguese </option>
              <option value="pt-BR" <?php selected(get_option('crf_recaptcha_lang'),'pt-BR'); ?>> Portuguese (Brazil) </option>
              <option value="pt-PT" <?php selected(get_option('crf_recaptcha_lang'),'pt-PT'); ?>> Portuguese (Portugal) </option>
              <option value="ro" <?php selected(get_option('crf_recaptcha_lang'),'ro'); ?>> Romanian </option>
              <option value="ru" <?php selected(get_option('crf_recaptcha_lang'),'ru'); ?>> Russian </option>
              <option value="sr" <?php selected(get_option('crf_recaptcha_lang'),'sr'); ?>> Serbian </option>
              <option value="si" <?php selected(get_option('crf_recaptcha_lang'),'si'); ?>> Sinhalese </option>
              <option value="sk" <?php selected(get_option('crf_recaptcha_lang'),'sk'); ?>> Slovak </option>
              <option value="sl" <?php selected(get_option('crf_recaptcha_lang'),'sl'); ?>> Slovenian </option>
              <option value="es-419" <?php selected(get_option('crf_recaptcha_lang'),'es-419'); ?>> Spanish (Latin America) </option>
              <option value="es" <?php selected(get_option('crf_recaptcha_lang'),'es'); ?>> Spanish (Spain) </option>
              <option value="sw" <?php selected(get_option('crf_recaptcha_lang'),'sw'); ?>> Swahili </option>
              <option value="sv" <?php selected(get_option('crf_recaptcha_lang'),'sv'); ?>> Swedish </option>
              <option value="ta" <?php selected(get_option('crf_recaptcha_lang'),'ta'); ?>> Tamil </option>
              <option value="te" <?php selected(get_option('crf_recaptcha_lang'),'te'); ?>> Telugu </option>
              <option value="th" <?php selected(get_option('crf_recaptcha_lang'),'th'); ?>> Thai </option>
              <option value="tr" <?php selected(get_option('crf_recaptcha_lang'),'tr'); ?>> Turkish </option>
              <option value="uk" <?php selected(get_option('crf_recaptcha_lang'),'uk'); ?>> Ukrainian </option>
              <option value="ur" <?php selected(get_option('crf_recaptcha_lang'),'ur'); ?>> Urdu </option>
              <option value="vi" <?php selected(get_option('crf_recaptcha_lang'),'vi'); ?>> Vietnamese </option>
              <option value="zu" <?php selected(get_option('crf_recaptcha_lang'),'zu'); ?>> Zulu </option>
            </select>
          </div>
        </div>
        <div class="option-main crf-form-setting">
          <div class="user-group crf-form-left-area">
            <div class="crf-label">
              <?php _e( 'reCAPTCHA under User Login:', $textdomain ); ?>
            </div>
          </div>
          <div class="user-group-option crf-form-right-area">
            <input name="enable_captcha_login" id="enable_captcha_login" type="checkbox" class="upb_toggle" value="yes" <?php if (get_option('ucf_enable_captcha_login','no')=='yes'){ echo "checked";}?> style="display:none;"/>
            <label for="enable_captcha_login"></label>
          </div>
        </div>
        <div class="option-main crf-form-setting">
          <div class="user-group crf-form-left-area">
            <div class="crf-label">
              <?php _e( 'Site Key:', $textdomain ); ?>
            </div>
          </div>
          <div class="user-group-option crf-form-right-area">
            <input type="text" name="publickey" id="publickey" value="<?php if(isset($public_key)) echo $public_key; ?>" />
          </div>
        </div>
        <div class="option-main crf-form-setting">
          <div class="user-group crf-form-left-area">
            <div class="crf-label">
              <?php _e( 'Secret Key:', $textdomain ); ?>
            </div>
          </div>
          <div class="user-group-option crf-form-right-area">
            <input type="text" name="privatekey" id="privatekey" value="<?php if(isset($private_key)) echo $private_key; ?>" />
          </div>
        </div>
        
        <div class="option-main crf-form-setting">
          <div class="user-group crf-form-left-area">
            <div class="crf-label">
              <?php _e( 'Request Method:', $textdomain ); ?>
              <small><?php _e( '(Change this setting if your ReCaptcha is not working as expected.)', $textdomain ); ?></small>
            </div>
          </div>
          <div class="user-group-option crf-form-right-area">
            <select name="request_method" id="request_method">
            <option value="CurlPost" <?php selected(get_option('crf_recaptcha_request_method'),'CurlPost'); ?>>CurlPost</option>
            <option value="SocketPost" <?php selected(get_option('crf_recaptcha_request_method'),'SocketPost'); ?>>SocketPost</option>
            </select>
          </div>
        </div>
        
      </div>
    </div>
    <br>
    <br>
    <div class="crf-form-footer">
      <div class="crf-form-button">
        <?php wp_nonce_field('save_crf_antispam_setting'); ?>
        <input type="submit"  class="button-primary" value="<?php _e('Save',$textdomain);?>" name="saveoption" id="saveoption" />
        <a href="admin.php?page=crf_settings" class="cancel_button">
        <?php _e('Cancel',$textdomain);?>
        </a> </div>
    </div>
  </form>
</div>
