<?php

require_once dirname( __FILE__ ) . '/class.settings-api.php';

/**
 * 
 *
 * 
 */
if ( !class_exists('CP_User_Profile_settings' ) ):
class CP_User_Profile_settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new CP_User_Profile_settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'CP User Profile ', 'CP User Profile ', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'cp_basics',
                'title' => __( 'Basic Settings', 'cp' )
            ),
//            array(
//                'id' => 'cp_advanced',
//                'title' => __( 'Advanced Settings', 'cp' )
//            ),
//            array(
//                'id' => 'cp_others',
//                'title' => __( 'Other Settings', 'wpuf' )
//            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'cp_basics' => array(
               
                array(
                    'name' => 'radio',
                    'label' => __( 'Languase Setting', 'cp' ),
                    'desc' => __( 'Please Select Your Languase', 'cp' ),
                    'type' => 'radio',
                    'options' => array(
                        'bng' => 'Bangla',
                        'eng' => 'English'
                    )
                )
            ),
     //==============================================================       
            
//            'cp_advanced' => array(
//                array(
//                    'name' => 'color',
//                    'label' => __( 'Color', 'cp' ),
//                    'desc' => __( 'Color description', 'cp' ),
//                    'type' => 'color',
//                    'default' => ''
//                ),
//                array(
//                    'name' => 'password',
//                    'label' => __( 'Password', 'cp' ),
//                    'desc' => __( 'Password description', 'cp' ),
//                    'type' => 'password',
//                    'default' => ''
//                ),
//                array(
//                    'name' => 'wysiwyg',
//                    'label' => __( 'Advanced Editor', 'cp' ),
//                    'desc' => __( 'WP_Editor description', 'cp' ),
//                    'type' => 'wysiwyg',
//                    'default' => ''
//                ),
//                array(
//                    'name' => 'multicheck',
//                    'label' => __( 'Multile checkbox', 'cp' ),
//                    'desc' => __( 'Multi checkbox description', 'cp' ),
//                    'type' => 'multicheck',
//                    'default' => array('one' => 'one', 'four' => 'four'),
//                    'options' => array(
//                        'one' => 'One',
//                        'two' => 'Two',
//                        'three' => 'Three',
//                        'four' => 'Four'
//                    )
//                ),
//                array(
//                    'name' => 'selectbox',
//                    'label' => __( 'A Dropdown', 'cp' ),
//                    'desc' => __( 'Dropdown description', 'cp' ),
//                    'type' => 'select',
//                    'options' => array(
//                        'yes' => 'Yes',
//                        'no' => 'No'
//                    )
//                ),
//                array(
//                    'name' => 'password',
//                    'label' => __( 'Password', 'cp' ),
//                    'desc' => __( 'Password description', 'cp' ),
//                    'type' => 'password',
//                    'default' => ''
//                ),
//                array(
//                    'name' => 'file',
//                    'label' => __( 'File', 'cp' ),
//                    'desc' => __( 'File description', 'cp' ),
//                    'type' => 'file',
//                    'default' => ''
//                )
//            ),
            //==============================================================
            
            
//            'cp_others' => array(
//                array(
//                    'name' => 'text',
//                    'label' => __( 'Text Input', 'cp' ),
//                    'desc' => __( 'Text input description', 'cp' ),
//                    'type' => 'text',
//                    'default' => 'Title'
//                ),
//                array(
//                    'name' => 'textarea',
//                    'label' => __( 'Textarea Input', 'cp' ),
//                    'desc' => __( 'Textarea description', 'cp' ),
//                    'type' => 'textarea'
//                ),
//                array(
//                    'name' => 'checkbox',
//                    'label' => __( 'Checkbox', 'cp' ),
//                    'desc' => __( 'Checkbox Label', 'cp' ),
//                    'type' => 'checkbox'
//                ),
//                array(
//                    'name' => 'radio',
//                    'label' => __( 'Radio Button', 'cp' ),
//                    'desc' => __( 'A radio button', 'cp' ),
//                    'type' => 'radio',
//                    'options' => array(
//                        'yes' => 'Yes',
//                        'no' => 'No'
//                    )
//                ),
//                array(
//                    'name' => 'multicheck',
//                    'label' => __( 'Multile checkbox', 'cp' ),
//                    'desc' => __( 'Multi checkbox description', 'cp' ),
//                    'type' => 'multicheck',
//                    'options' => array(
//                        'one' => 'One',
//                        'two' => 'Two',
//                        'three' => 'Three',
//                        'four' => 'Four'
//                    )
//                ),
//                array(
//                    'name' => 'selectbox',
//                    'label' => __( 'A Dropdown', 'cp' ),
//                    'desc' => __( 'Dropdown description', 'cp' ),
//                    'type' => 'select',
//                    'options' => array(
//                        'yes' => 'Yes',
//                        'no' => 'No'
//                    )
//                ),
//                array(
//                    'name' => 'password',
//                    'label' => __( 'Password', 'cp' ),
//                    'desc' => __( 'Password description', 'cp' ),
//                    'type' => 'password',
//                    'default' => ''
//                ),
//                array(
//                    'name' => 'file',
//                    'label' => __( 'File', 'cp' ),
//                    'desc' => __( 'File description', 'cp' ),
//                    'type' => 'file',
//                    'default' => ''
//                )
//            )
            
            //============================================================
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo'<h2> CP User Profile <h2>';
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }
   

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new CP_User_Profile_settings();
