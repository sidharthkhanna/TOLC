<?php

/** Hooks */
add_action( 'admin_init', 'cpm_admin_init' );
add_action( 'wp_head', 'cpm_head' );
add_action( 'admin_menu', 'cpm_admin' );
add_action( 'admin_enqueue_scripts', 'cpm_admin_register_scripts' );
add_action( 'wp_enqueue_scripts', 'cpm_enqueue_styles' );

/** Admin init */
function cpm_admin_init() {
	cpm_version_check();

	if ( isset( $_POST['submit'] ) && $_POST['submit'] == 'Export' ) {
		cpm_csv_export();
	}
}

/** WP head */
function cpm_head() {
	?>
	<script>
		var ajaxurl  = '<?php echo home_url() ?>/wp-admin/admin-ajax.php';
		var fb_point = '<?php echo get_option("pm_facebook_points"); ?>';
		var tw_point = '<?php echo get_option("pm_twitter_points"); ?>';
		var uid      = '<?php echo cpm_currentUser() ?>';
	</script>
	<?php
}

/** Admin pages */
function cpm_admin() {
	add_menu_page('Points Manager', 'Points Manager', 'manage_options', 'cpm_admin_manage', 'cpm_admin_manage');
	add_submenu_page('cpm_admin_manage', 'Cryptopoints Manager - ' .__('Manage','pm'), __('Manage','pm'), 'manage_options', 'cpm_admin_manage', 'cpm_admin_manage');
	add_submenu_page('cpm_admin_manage', 'Cryptopoints Manager - ' .__('Payout','pm'), __('Payout','pm'), 'manage_options', 'cpm_admin_payout', 'cpm_admin_payout');
	add_submenu_page('cpm_admin_manage', 'Cryptopoints Manager - ' .__('Automated Reward','pm'), __('Automated Reward','pm'), 'manage_options', 'cpm_admin_auto_reward', 'cpm_admin_auto_reward');
	add_submenu_page('cpm_admin_manage', 'Cryptopoints Manager - ' .__('Configure','pm'), __('Configure','pm'), 'manage_options', 'cpm_admin_config', 'cpm_admin_config');
	add_submenu_page('cpm_admin_manage', 'Cryptopoints Manager - ' .__('Export','pm'), __('Export','pm'), 'manage_options', 'cpm_admin_export', 'cpm_admin_export');
	do_action('cpm_admin_pages');
}

/** Load scripts, styles */
function cpm_admin_register_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('datatables', PM_URL . 'assets/js//jquery.dataTables.min.js', array('jquery'), '1.7.4' );
	wp_enqueue_script('autocomplete', PM_URL . 'assets/js/jquery.autocomplete.js', array('jquery'), '3.2.2' );
	wp_enqueue_script('datetimepicker', PM_URL . 'assets/js/jquery.datetimepicker.js', array('jquery'), '3.2.2' );
	wp_enqueue_script('main', PM_URL . 'assets/js/main.js', array('jquery'), '1.0.0' );

	wp_enqueue_style('autocomplete', PM_URL . 'assets/css/jquery.autocomplete.css');
	wp_enqueue_style('datetimepicker', PM_URL . 'assets/css/jquery.datetimepicker.css');
	wp_enqueue_style('style', PM_URL . 'assets/css/admin.css');
}

function cpm_enqueue_styles() {
	wp_enqueue_script('twitterbutton', PM_URL . 'assets/js/jquery.twitterbutton.1.1.js', array('jquery'), '1.0.0' );
	wp_enqueue_script('main-fe', PM_URL . 'assets/js/main-fe.js', array('jquery'), '1.0.1' );
	wp_enqueue_style('custom', PM_URL . 'assets/css/style.css', array(), '1.0.1');
}

/** Manage */
function cpm_admin_manage() {
    if (isset($_POST['submit_add_points_users'])) {
        $points = (int) $_POST['pm_add_points_users'];
        if ($points < 0) {$points = 0;}
        $users = get_users();
        foreach ($users as $user) {
            cpm_alterPoints($user->id, $points);
        }
    }
	?>
	<div class="wrap">
		<h2>Cryptopoints Manager - <?php _e('Manage', 'pm'); ?></h2>
		<?php _e('Manage the points of your users.', 'pm'); ?><br /><br />
		<?php if (!function_exists("curl_init")) :?>
		<?php _e('Please enable curl for this plugin to work properly.', 'pm'); ?><br />
		<?php endif; ?>

        <form method="post" name="pm_manage_form_add_points" id="pm_manage_form_add_points">
            <label for="pm_add_points_users">Add points to all users: </label>
            <input type="text" name="pm_add_points_users" value="" />
            <input type="submit" name="submit_add_points_users" value="<?php _e('Add', 'pm'); ?>" />
        </form>
        <br>

		<div class="updated" id="pm_manage_updated" style="display: none;"></div>
		<?php
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM `".$wpdb->users."` ORDER BY user_login ASC");
		?>

		<table id="pm_manage_table" class="widefat datatables">
			<thead><tr><th scope="col" width="35"></th><th scope="col"><?php _e('User','pm'); ?></th><th scope="col" width="120"><?php _e('Points','pm'); ?></th><th scope="col" width="180"><?php _e('Update','pm'); ?></th></tr></thead>
			<tfoot><tr><th scope="col"></th><th scope="col"><?php _e('User','pm'); ?></th><th scope="col"><?php _e('Points','pm'); ?></th><th scope="col"><?php _e('Update','pm'); ?></th></tr></tfoot>

			<?php
			foreach($results as $result){
				$user = get_userdata($result->ID);
				$username = $user->user_login;
				$user_nicename = $user->display_name;
				$gravatar = get_avatar( $result->ID , $size = '32' );
				?>
				<tr>
					<td>
						<?php echo $gravatar; ?>
					</td>
					<td title="<?php echo $user_nicename ?>">
						<strong><?php echo $username; ?></strong><br /><i><?php echo $user->user_email; ?></i>
					</td>
					<td class="pm_manage_form_points">
						<span id="pm_manage_form_points_<?php echo $result->ID; ?>"><?php cpm_displayPoints($result->ID); ?></span>
					</td>
					<td class="pm_manage_form_update">
						<form method="post" name="pm_manage_form_<?php echo $result->ID; ?>" id="pm_manage_form_<?php echo $result->ID; ?>">
							<input type="hidden" name="pm_manage_form_id" value="<?php echo $result->ID; ?>" />
							<input type="text" name="pm_manage_form_points" value="<?php echo cpm_getPoints($result->ID); ?>" />
							<input type="submit" value="<?php _e('Update', 'pm'); ?>" />
							<img src="<?php echo WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__)). 'assets/images/load.gif'; ?>" style="display: none;" />
						</form>
					</td>
				</tr>
			<?php
			}
			?>
		</table>

	</div>

	<script type="text/javascript">
		jQuery(document).ready(function() {

			jQuery(".pm_manage_form_update form").submit(function() {
				user_id = jQuery(this).children('input[name=pm_manage_form_id]').val();
				points = jQuery(this).children('input[name=pm_manage_form_points]').val();
				submit = jQuery(this).children('input[type=submit]');
				loadImg = jQuery(this).children('img');

				jQuery(".pm_manage_form_update form").children('input').attr('disabled', true);
				submit.hide();
				loadImg.css('display', 'inline-block');
				jQuery(this).children('input[name=pm_manage_form_points]').attr('readonly', true);
				jQuery('#pm_manage_form_points_'+user_id).hide(100);

				jQuery.post(
					ajaxurl,
					{
						action: 'pm_manage_form_submit',
						user_id: user_id,
						points: points
					},
					function(data,status){
						if(status!='success'){
							message = '<?php _e('Connection problem. Please check that you are connected to the internet.', 'pm'); ?>';
						} else if(data.error!='ok') {
							message = data.error;
						} else {
							jQuery("#pm_manage_form_points_"+user_id).html(data.points_formatted);
							jQuery("#pm_manage_form_points_"+user_id).show(100);
							jQuery('#pm_manage_form_'+data.user_id).children('input[name=pm_manage_form_points]').val(data.points);
							jQuery('#pm_manage_form_'+data.user_id).children('input[name=pm_manage_form_points]').removeAttr('readonly');
							message = '<?php _e("Points updated for", 'pm'); ?>' + ' "' + data.username + '"';
						}
						jQuery("#pm_manage_updated").html('<p><strong>'+message+'</strong></p>');
						jQuery("#pm_manage_updated").show(100);
						loadImg.hide();
						submit.show();
						jQuery(".pm_manage_form_update form").children('input').removeAttr('disabled');
					},
					"json"
				);
				return false;
			});

			jQuery('#pm_manage_table').dataTable({
				"bStateSave": true,
				"bSort": false,
				"aoColumns": [  { "bSearchable": false },{},{},{ "bSearchable": false } ]
			});

		});

	</script>

	<?php do_action('cpm_admin_manage');
}

/** Configure */
function cpm_admin_config() {
	// handles form submissions
	if ( isset($_POST['pm_admin_form_submit']) && $_POST['pm_admin_form_submit'] == 'Y' ) {
		$pm_topfilter = explode(',',str_replace(array("\n","\r"),'',$_POST['pm_topfilter']));
		if(pm_topfilter==''){
			$pm_topfilter=array();
		}
		else{
			foreach($pm_topfilter as $x=>$y){
				$pm_topfilter[$x]=trim($y);
			}
			$pm_topfilter=array_unique($pm_topfilter);
			$pm_topfilter=array_filter($pm_topfilter, 'strlen');
		}
		$pm_twitter_points = (int)$_POST['pm_twitter_points'];
		$pm_facebook_points = (int)$_POST['pm_facebook_points'];
		$pm_login_points = (int)$_POST['pm_login_points'];
		$pm_comment_points = (int)$_POST['pm_comment_points'];
		$pm_del_comment_points = (int)$_POST['pm_del_comment_points'];
		$pm_post_points = (int)$_POST['pm_post_points'];
		$pm_reg_points = (int)$_POST['pm_reg_points'];
		$pm_prefix = $_POST['pm_prefix'];
		$pm_suffix = $_POST['pm_suffix'];
		update_option('pm_twitter_points', $pm_twitter_points);
		update_option('pm_facebook_points', $pm_facebook_points);
		update_option('pm_login_points', $pm_login_points);
		update_option('pm_comment_points', $pm_comment_points);
		update_option('pm_del_comment_points', $pm_del_comment_points);
		update_option('pm_post_points', $pm_post_points);
		update_option('pm_reg_points', $pm_reg_points);
		update_option('pm_prefix', $pm_prefix);
		update_option('pm_suffix', $pm_suffix);
		update_option('pm_topfilter', $pm_topfilter);

		// hook for modules to process submitted data
		do_action('pm_config_process');

		echo '<div class="updated"><p><strong>'.__('Settings Updated','pm').'</strong></p></div>';
	}

	// prepares data for use in form
	if(count(get_option('pm_topfilter'))>0){
		$pm_topfilter_text = implode(", ",(array)get_option('pm_topfilter'));
	} else {
		$pm_topfilter_text = '';
	}
	if (get_option('pm_mypoints')) {
		$pm_mypoints_checked = " checked='checked'";
	} else {
		$pm_mypoints_checked = "";
	}

	?>

	<div class="wrap">
		<h2>Cryptopoints Manager - <?php _e('Configure', 'pm'); ?></h2>
		<?php _e('Configure Points Manager to your liking!', 'pm'); ?><br /><br />

		<form name="pm_admin_form" method="post">
			<input type="hidden" name="pm_admin_form_submit" value="Y" />

			<h3><?php _e('General Settings','pm'); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="pm_prefix"><?php _e('Prefix for display of points', 'pm'); ?>:</label></th>
					<td valign="middle"><input type="text" id="pm_prefix" name="pm_prefix" value="<?php echo get_option('pm_prefix'); ?>" size="30" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="pm_suffix"><?php _e('Suffix for display of points', 'pm'); ?>:</label></th>
					<td valign="middle"><input type="text" id="pm_suffix" name="pm_suffix" value="<?php echo get_option('pm_suffix'); ?>" size="30" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label title="<?php _e('Separate usernames using &quot;,&quot;', 'pm'); ?>" for="pm_topfilter"><?php _e('Hide the following users from list of top users','pm'); ?>:</label></th>
					<td valign="middle"><textarea id="pm_topfilter" name="pm_topfilter" cols="30" rows="5"><?php echo $pm_topfilter_text; ?></textarea></td>
				</tr>
				<?php do_action('pm_config_form_general'); ?>
			</table>
			<br />
			<h3><?php _e('Point Settings','pm'); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="pm_twitter_points"><?php _e('Points for each tweet', 'pm'); ?>:</label>
					</th>
					<td valign="middle" width="190"><input type="text" id="pm_twitter_points" name="pm_twitter_points" value="<?php echo get_option('pm_twitter_points'); ?>" size="30" /></td>
					<td><input type="button" onclick="document.getElementById('pm_twitter_points').value='0'" value="<?php _e('Do not add points for tweets', 'pm'); ?>" class="button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="pm_facebook_points"><?php _e('Points for each facebook share', 'pm'); ?>:</label>
					</th>
					<td valign="middle" width="190"><input type="text" id="pm_facebook_points" name="pm_facebook_points" value="<?php echo get_option('pm_facebook_points'); ?>" size="30" /></td>
					<td><input type="button" onclick="document.getElementById('pm_facebook_points').value='0'" value="<?php _e('Do not add points for Facebook', 'pm'); ?>" class="button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="pm_login_points"><?php _e('Points for each visit site (once a day)', 'pm'); ?>:</label>
					</th>
					<td valign="middle" width="190"><input type="text" id="pm_login_points" name="pm_login_points" value="<?php echo get_option('pm_login_points'); ?>" size="30" /></td>
					<td><input type="button" onclick="document.getElementById('pm_login_points').value='0'" value="<?php _e('Do not add points for login', 'pm'); ?>" class="button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="pm_comment_points"><?php _e('Points for each comment', 'pm'); ?>:</label>
					</th>
					<td valign="middle" width="190"><input type="text" id="pm_comment_points" name="pm_comment_points" value="<?php echo get_option('pm_comment_points'); ?>" size="30" /></td>
					<td><input type="button" onclick="document.getElementById('pm_comment_points').value='0'" value="<?php _e('Do not add points for comments', 'pm'); ?>" class="button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="pm_del_comment_points"><?php _e('Points subtracted for each comment deleted','pm'); ?>:</label>
					</th>
					<td valign="middle"><input type="text" id="pm_del_comment_points" name="pm_del_comment_points" value="<?php echo get_option('pm_del_comment_points'); ?>" size="30" /></td>
					<td><input type="button" onclick="document.getElementById('pm_del_comment_points').value='0'" value="<?php _e('Do not subtract points on comment deletion','pm'); ?>" class="button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="pm_post_points"><?php _e('Points for each post','pm'); ?>:</label>
					</th>
					<td valign="middle"><input type="text" id="pm_post_points" name="pm_post_points" value="<?php echo get_option('pm_post_points'); ?>" size="30" /></td>
					<td><input type="button" onclick="document.getElementById('pm_post_points').value='0'" value="<?php _e('Do not add points for new posts','pm'); ?>" class="button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="pm_reg_points"><?php _e('Points for new members','pm'); ?>:</label>
					</th>
					<td valign="middle"><input type="text" id="pm_reg_points" name="pm_reg_points" value="<?php echo get_option('pm_reg_points'); ?>" size="30" /></td>
					<td><input type="button" onclick="document.getElementById('pm_reg_points').value='0'" value="<?php _e('Do not add points for new registrations','pm'); ?>" class="button" /></td>
				</tr>
				<?php do_action('pm_config_form_points'); ?>
			</table>

			<?php do_action('pm_config_form'); ?>

			<p class="submit">
				<input type="submit" name="Submit" value="<?php _e('Update Options','pm'); ?>" class="button button-primary" />
			</p>

		</form>
	</div>

	<?php do_action('cpm_admin_config'); ?>

<?php
}

/** Payout */
function cpm_admin_payout() {
	// handles form submissions
	if ($_POST['pm_payout_form'] == 'Y') {
		echo '<div class="updated"><p><strong>'.__('Payout Settings Updated','pm').'</strong></p></div>';
		update_option('number_coins', $_POST['number_coins']);
		update_option('number_thresholds', $_POST['number_thresholds']);
		update_option('account', $_POST['account']);
		update_option('frequency', $_POST['frequency']);
		update_option('threshold', $_POST['threshold']);
		update_option('method_level', $_POST['method_level']);
	}
	// handles form submissions
	if ($_POST['pm_payout_manual_form'] == 'Y') {
		echo '<div class="updated"><p><strong>'.__('Payout was successfully sent','pm').'</strong></p></div>';
		$manual = $_POST['manual'];
		cpm_manual_sent_all_users($manual);
	}
	?>

	<div class="wrap pm_payout">
		<h2>Cryptopoints Manager - <?php _e('Payout', 'pm'); ?></h2><br /><br />
		<?php if (!function_exists("curl_init")) :?>
		<?php _e('Please enable curl for this plugin to work properly.', 'pm'); ?><br />
		<?php endif; ?>

		<form name="pm_payout_form" class="pm_payout_form" method="post">
			<?php
			$account = get_option('account');
			$threshold = get_option('threshold');
			$method_level = get_option('method_level');
			$number_coins = get_option('number_coins');
			$number_thresholds = get_option('number_thresholds');
			?>
			<h3>Local Payout Features</h3>
			<input type="hidden" name="pm_payout_form" value="Y">
			<label for="number_coins"><?php _e('Number of coins', 'pm'); ?>:</label>
			<input type="text" id="number_coins" name="number_coins" value="<?php echo get_option('number_coins'); ?>" size="30" />
			<table class="local_payout_features widefat">
				<tr>
					<th class="center">Enabled</th>
					<th>Coin Name</th>
					<th>Description</th>
					<th>Wallet Address</th>
					<th>Server IP Address</th>
					<th>Username</th>
					<th>Password</th>
					<th>RPC Port</th>
					<th>Balance</th>
				</tr>
				<?php
				for ($i = 1; $i <= $number_coins; $i++) { ?>
					<?php
						if (!is_array($account)) {
							$account[$i]['enabled'] = '';
							$account[$i]['name'] = '';
							$account[$i]['desc'] = '';
							$account[$i]['wallet'] = '';
							$account[$i]['username'] = '';
							$account[$i]['password'] = '';
							$account[$i]['rpc_port'] = '';
						}
					?>
					<tr>
						<td class="center"><input type="checkbox" name="account[<?php echo $i; ?>][enabled]" value="1" <?php if (!empty($account[$i]['enabled'])) checked($account[$i]['enabled'], 1); ?>></td>
						<td><input type="text" name="account[<?php echo $i; ?>][name]" value="<?php if (!empty($account[$i]['name'])) echo $account[$i]['name']; ?>"></td>
						<td><input type="text" name="account[<?php echo $i; ?>][desc]" value="<?php if (!empty($account[$i]['desc'])) echo $account[$i]['desc']; ?>"></td>
						<td><input type="text" name="account[<?php echo $i; ?>][wallet]" value="<?php if (!empty($account[$i]['wallet'])) echo $account[$i]['wallet']; ?>"></td>
						<td><input type="text" name="account[<?php echo $i; ?>][ip]" value="<?php if (!empty($account[$i]['ip'])) echo $account[$i]['ip']; ?>"></td>
						<td><input type="text" name="account[<?php echo $i; ?>][username]" value="<?php if (!empty($account[$i]['username'])) echo $account[$i]['username']; ?>"></td>
						<td><input type="text" name="account[<?php echo $i; ?>][password]" value="<?php if (!empty($account[$i]['password'])) echo $account[$i]['password']; ?>"></td>
						<td><input type="text" name="account[<?php echo $i; ?>][rpc_port]" value="<?php if (!empty($account[$i]['rpc_port'])) echo $account[$i]['rpc_port']; ?>"></td>
						<td>
							<?php
							if (!empty($account[$i]['username']) && !empty($account[$i]['password']) && !empty($account[$i]['ip']) && !empty($account[$i]['rpc_port'])) {
								require_once dirname(__FILE__) . '/payout/RPCcoin.php';
								$username = $account[$i]['username'];
								$password = $account[$i]['password'];
								$ip = $account[$i]['ip'];
								$rpc_port = $account[$i]['rpc_port'];
								if ($username != "") {
									$rpccoin = new RPCcoin($username, $password, $ip, $rpc_port);
									echo $rpccoin->getbalance();
								}
							}
							?>
						</td>
					</tr>
				<?php
				}
				?>
			</table>

			<h3>Leaderboard Payout Events</h3>
			<label for="method_level">Enable? </label>
			<input type="checkbox" name="method_level" value="1" <?php if (!empty($method_level)) checked($method_level); ?>>
			<br>
			<label for="number_thresholds"><?php _e('Number of thresholds', 'pm'); ?>:</label>
			<input type="text" id="number_thresholds" name="number_thresholds" value="<?php echo get_option('number_thresholds'); ?>" size="30" />
			<table class="auto_reward widefat">
				<tr>
					<th width="5%" class="center">Threshold</th>
					<th>Threshold Level (points)</th>
					<th>Threshold Name</th>
					<?php
					for ($j = 1; $j <= $number_coins; $j++) : ?>
						<th><?php echo $account[$j]['name'] . ' Reward' ?></th>
					<?php endfor; ?>
				</tr>
				<?php
				for ($i = 1; $i <= $number_thresholds; $i++) { ?>
					<?php
						if (!is_array($threshold)) {
							$threshold[$i]['id'] = '';
							$threshold[$i]['level'] = '';
							$threshold[$i]['name'] = '';
							$threshold[$i]['lycancoin'] = '';
							$threshold[$i]['vampirecoin'] = '';
							$threshold[$i]['password'] = '';
							$threshold[$i]['zombiecoin'] = '';
						}
					?>
					<tr>
						<td width="5%" class="center"><input type="text" name="threshold[<?php echo $i; ?>][id]" value="<?php echo $i; ?>"></td>
						<td><input type="text" name="threshold[<?php echo $i; ?>][level]" value="<?php echo $threshold[$i]['level']; ?>"></td>
						<td class="center"><input type="text" name="threshold[<?php echo $i; ?>][name]" value="<?php echo $threshold[$i]['name']; ?>"></td>
						<?php for ($j = 1; $j <= $number_coins; $j++) : ?>
							<?php $acc_slug = to_slug($account[$j]['name']); ?>
							<td><input type="text" name="threshold[<?php echo $i; ?>][<?php echo $acc_slug ?>]" value="<?php echo $threshold[$i][$acc_slug]; ?>"></td>
						<?php endfor; ?>
					</tr>
				<?php
				}
				?>
			</table>

			<?php submit_button( 'Save Options' ); ?>
		</form>

		<hr>

		<form name="pm_payout_manual_form" method="post">
			<h3>One-Time Manual Payout Events</h3>
			<input type="hidden" name="pm_payout_manual_form" value="Y">
			<table class="widefat">
				<tr>
					<th class="center">Enabled</th>
					<th>Coin Name</th>
					<th>Fixed Payout Amount</th>
					<th></th>
					<th>Random Payout Range</th>
					<th>Calculation Type</th>
				</tr>
				<?php
				for ($i = 1; $i <= $number_coins; $i++) { ?>
					<tr>
						<td class="center"><input type="checkbox" name="manual[<?php echo $i; ?>][enabled]" value="1" <?php checked($manual[$i]['enabled'], 1); ?>></td>
						<td><input type="text" name="manual[<?php echo $i; ?>][name]" value="<?php if (!empty($account[$i]['name'])) echo $account[$i]['name'] ?>"></td>
						<td><input type="text" name="manual[<?php echo $i; ?>][fixed_payout]" class="pm_fixed_payout" value="<?php echo $manual[$i]['fixed_payout']; ?>"></td>
						<td class="center">or</td>
						<td><input type="text" name="manual[<?php echo $i; ?>][random_payout]" class="pm_random_payout" value="<?php echo $manual[$i]['random_payout']; ?>"></td>
						<td>
							<select name="manual[<?php echo $i; ?>][type]">
								<option value="fixed" <?php selected( $manual[$i]['type'], 'fixed' ); ?>>Fixed</option>
								<option value="ratio" <?php selected( $manual[$i]['type'], 'ratio' ); ?>>Ratio</option>
							</select>
						</td>
					</tr>
				<?php
				}
				?>
			</table>

			<?php submit_button( 'Send Now' ); ?>
		</form>
	</div>
<?php
}

/** Automated Reward Distribution Settings */
function cpm_admin_auto_reward() {
	// handles form submissions
	if ($_POST['pm_auto_reward_form'] == 'Y') {
		echo '<div class="updated"><p><strong>'.__('Automated Reward Distribution Settings Updated','pm').'</strong></p></div>';
		update_option('frequency', $_POST['frequency']);
		update_option('method_time', $_POST['method_time']);
		$method_time = get_option('method_time');
		cpm_clear_all_crons('cpm_create_frequency_payout');
		if ($method_time) {
			$frequency = get_option('frequency');
			if (is_array($frequency)) {
				foreach ($frequency as $frequency_item) {
					cpm_create_frequency_payout_schedule($frequency_item['frequency'], $frequency_item);
				}
			}
		}
	}
	?>

	<div class="wrap pm_payout">
		<h2>Automated Reward Distribution Settings</h2><br /><br />
		<?php if (!function_exists("curl_init")) :?>
			<?php _e('Please enable curl for this plugin to work properly.', 'pm'); ?><br />
		<?php endif; ?>

		<form name="pm_auto_reward_form" class="pm_payout_form" method="post">
		<?php
		$account = get_option('account');
		$frequency = get_option('frequency');
		$method_time = get_option('method_time');
		$number_coins = get_option('number_coins');
		?>
		<input type="hidden" name="pm_auto_reward_form" value="Y">

		<label for="method_time]">Enable? </label>
		<input type="checkbox" name="method_time" value="1" <?php if (!empty($method_time)) checked($method_time); ?>>
		<table class="auto_reward widefat">
			<tr>
				<th>Enabled</th>
				<th>Coin Name</th>
				<th>Fixed Payout Amount</th>
				<th></th>
				<th>Random Payout Range</th>
				<th>Starting Date/Time</th>
				<th>Next Payout</th>
				<th>Frequency</th>
				<th>Calculation Type</th>
			</tr>
			<?php
			for ($i = 1; $i <= $number_coins; $i++) {
				if (!is_array($frequency)) {
					$frequency[$i]['fixed_payout'] = '';
					$frequency[$i]['random_payout'] = '';
					$frequency[$i]['frequency'] = 'hourly';
					$frequency[$i]['type'] = 'fixed';
				}
				?>
				<tr>
					<td class="center"><input type="checkbox" name="account[<?php echo $i; ?>][enabled]" value="1" <?php if (!empty($account[$i]['enabled'])) checked($account[$i]['enabled'], 1); ?> disabled="disabled"></td>
					<td><input type="text" name="frequency[<?php echo $i; ?>][name]" value="<?php if (!empty($account[$i]['name'])) echo $account[$i]['name']; ?>"></td>
					<td><input type="text" name="frequency[<?php echo $i; ?>][fixed_payout]" class="pm_fixed_payout" value="<?php echo $frequency[$i]['fixed_payout']; ?>"></td>
					<td class="center">or</td>
					<td><input type="text" name="frequency[<?php echo $i; ?>][random_payout]" class="pm_random_payout" value="<?php echo $frequency[$i]['random_payout']; ?>"></td>
					<td><input type="text" name="frequency[<?php echo $i; ?>][start_date]" class="datetimepicker" value="<?php echo date("Y/m/d H:i"); ?>"></td>
					<td><input type="text" value="<?php if (wp_next_scheduled( 'cpm_create_frequency_payout', array($frequency[$i]))) echo cpm_relativeTime(wp_next_scheduled( 'cpm_create_frequency_payout', array($frequency[$i])) ); ?>"></td>
					<td>
						<select name="frequency[<?php echo $i; ?>][frequency]">
							<option value="hourly" <?php selected( $frequency[$i]['frequency'], 'hourly' ); ?>>Hourly</option>
							<option value="daily" <?php selected( $frequency[$i]['frequency'] == 'daily' ); ?>>Daily</option>
							<option value="weekly" <?php selected( $frequency[$i]['frequency'] , 'weekly' ); ?>>Weekly</option>
							<option value="monthly" <?php selected( $frequency[$i]['frequency'] , 'monthly' ); ?>>Monthly</option>
						</select>
					</td>
					<td>
						<select name="frequency[<?php echo $i; ?>][type]">
							<option value="fixed" <?php selected( $frequency[$i]['type'], 'fixed' ); ?>>Fixed</option>
							<option value="ratio" <?php selected( $frequency[$i]['type'], 'ratio' ); ?>>Ratio</option>
						</select>
					</td>
				</tr>
			<?php
			}
			?>
		</table>
		<?php submit_button( 'Save Options' ); ?>
	</form>
	<?php
}

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

function extra_user_profile_fields( $user ) { ?>
	<h3><?php _e("Extra profile information", "blank"); ?></h3>
	<?php
	$number_coins = get_option('number_coins');
	$account = get_option('account');
	if (is_array($account)) {
		for ($i = 1; $i <= $number_coins; $i++) {
			$uaccount = preg_replace('/\s*/', '', $account[$i]['name']);
			$uaccount = strtolower($uaccount);
			?>
				<table class="form-table">
					<tr>
						<th><label for="<?php echo $uaccount; ?>"><?php echo $account[$i]['name']; ?></label></th>
						<td>
							<input type="text" name="<?php echo $uaccount; ?>" id="<?php echo $uaccount; ?>" value="<?php echo esc_attr( get_the_author_meta( $uaccount, $user->ID ) ); ?>" class="regular-text" /><br />
						</td>
					</tr>
				</table>
			<?php
		}
	}
}

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

	$number_coins = get_option('number_coins');
	$account = get_option('account');
	for ($i = 1; $i <= $number_coins; $i++) {
		$uaccount = preg_replace('/\s*/', '', $account[$i]['name']);
		$uaccount = strtolower($uaccount);
		update_user_meta( $user_id, $uaccount, $_POST[$uaccount] );
	}
}

/** Export to CSV */
function cpm_admin_export() {
	?>
	<div class="wrap">
		<h2>Cryptopoints Manager - Export to CSV</h2>

		<form method="post" name="pm_csv_exporter_form" action="">
			<h3>Press the button below to export all payout information.</h3>
			<p class="submit"><input type="submit" name="submit" value="Export"/></p>
		</form>
	</div>
<?php
}
