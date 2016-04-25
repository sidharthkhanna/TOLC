<?php
/**
 * Adds OTP widget.
 */
class OTP_Widget extends WP_Widget
{
    /**
     * Register widget with WordPress.
     */
    public $basic_options;
    function __construct()
    {
        parent::__construct(
            'crf_otp_widget', // Base ID
            __('RegistrationMagic OTP Login', Front_Utility::$textdomain), // Name
            array('description' => __('One Time Password login system for RegistrationMagic form submissions', Front_Utility::$textdomain),) // Args
        );
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        // Show if user is not logged in
        if(!Front_Utility::is_authorized() && !is_user_logged_in()){
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        ?>
       <form method="post" action="" onsubmit="return false">
           <div id="crf_otp_login">
               <input type="text" placeholder="<?php _e('Email:',Front_Utility::$textdomain); ?>" value="" id="crf_otp_econtact" name="<?php echo wp_generate_password(5, false,false); ?>"
                      onkeypress="return crf_call_otp(event)" maxlength="50"/>
               <input type="text" value="" placeholder="<?php _e('OTP:',Front_Utility::$textdomain); ?>" maxlength="50" name="<?php echo wp_generate_password(5, false,false); ?>" id="crf_otp_kcontact" class="crf_otp_key" style="display:none" onkeypress="return crf_call_otp(event)"/>
               <input type="hidden" value="<?php echo wp_generate_password(8, false); ?>" name="security_key"/>
               <div class="crf_f_notifications">
                   <span class="crf_f_error"></span>
                   <span class="crf_f_success"></span>
               </div>
           </div>
            
       </form>
       
        <img id="crf_f_loading" style="display:none" src="<?php echo plugin_dir_url( __FILE__ ) .'../images/crf_f_ajax_loader_wide.gif'; ?>" alt="Loading" ></img>
    
        <script>var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";</script>
        <?php }else{ 
		
		if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
		?>
            <div id="crf_f_sub_page">
            
            
                <a href="<?php echo get_permalink(get_option('crf_f_sub_page_id'));?>"><?php _e('View Submissions', Front_Utility::$textdomain);?></a>
            </div>
        <?php }
        echo $args['after_widget'];
    }
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('RegistrationMagic OTP Login', Front_Utility::$textdomain);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:',Front_Utility::$textdomain); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
} // class Foo_Widget