<?php
/*
Plugin Name: i3 Feedback Button
Plugin URI: 
Description: Displays a static button, on the left or right side of the page, which lets you open an post or page in an thickbox window.
Author: Mo
Version: 1.0
Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Init function
 **/
function i3fb_init_method() {
    load_plugin_textdomain('i3fb-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
    add_thickbox(); // Enable build in thickbox
}
add_action('init', 'i3fb_init_method');

/**
 * Inserts and displays the template in wp_footer
 **/
function i3fb_template_function() {

    $i3fb_options = get_option( 'APF_i3fb_Fields' ); // Get the admin options
        $i3fb_page_id = $i3fb_options['i3fb_page_id']; // Grab the page id
        $i3fb_btn_txt = $i3fb_options['i3fb_btn_txt']; // Grab the button text
        $i3fb_position = $i3fb_options['i3fb_position']; // Grab the position
        $i3fb_colors = $i3fb_options['i3fb_colors']; // Get all color options
            $i3fb_color_bg = $i3fb_colors[0]; // Grab first color (bg)
            $i3fb_color_txt = $i3fb_colors[1]; // Grab second color (txt)

        $i3fb_top_position = $i3fb_options['i3fb_top_position']; // Grab the position
        $i3fb_sizes = $i3fb_options['i3fb_size']; // Grab the sizes
            $i3fb_size_height = $i3fb_sizes[0]; // Get the height
            $i3fb_size_width = $i3fb_sizes[1]; // Get the width

            if (empty($i3fb_sizes[0])) { // If height is empty, insert default
               $i3fb_size_height = 550;
            }

            if (empty($i3fb_sizes[1])) { // If width is empty, insert default
                $i3fb_size_width = 450;
            }

    $post = get_post($i3fb_page_id); // Grab the data of the page
        $title = apply_filters('the_title', $post->post_title); // displays the post title
        $content = apply_filters('the_content', $post->post_content); // displays the post content
     
    echo    '<a href="#TB_inline?height='.$i3fb_size_height.'&width='.$i3fb_size_width.'&inlineId=i3fb-thickbox-'.$i3fb_page_id.'" title="'.$title.'" id="i3fb-btn-container" class="thickbox '.$i3fb_position.'" style="top:'.$i3fb_top_position.'px; background:'.$i3fb_color_bg.'; color:'.$i3fb_color_txt.';">'.$i3fb_btn_txt.'</a>';

    echo    '<div id="i3fb-thickbox-'.$i3fb_page_id.'" class="cf" style="display:none">';
    echo        $content;
    echo    '</div>';

}
add_action('wp_footer', 'i3fb_template_function');

/**
 * Enqueue the stylesheet
 **/
function i3fb_styles_scripts() {
    if ( ! is_admin() ) {
        wp_enqueue_style( 'i3fb-styles', plugins_url('/assets/css/style.css', __FILE__) );
    }    
}
add_action('wp_enqueue_scripts', 'i3fb_styles_scripts');

/**
 * Include the "admin-page-framework"
 **/

if ( ! class_exists( 'AdminPageFramework' ) ) {
    include_once( dirname( __FILE__ ) . '/library/admin-page-framework.min.php' );
}

// Extend the class
class APF_i3fb_Fields extends AdminPageFramework {
/**
* The set-up method which is triggered automatically with the 'wp_loaded' hook.
*
* Here we define the setup() method to set how many pages, page titles and icons etc.
*/
    public function setUp() {
        // Create the root menu - specifies to which parent menu to add.
        // the available built-in root menu labels: Dashboard, Posts, Media, Links, Pages, Comments, Appearance, Plugins, Users, Tools, Settings, Network Admin
        $this->setRootMenuPage( 'Settings' );
        // Add the sub menus and the pages
        $this->addSubMenuItems(
            array(
            'title' => 'i3 Feedback Button', // the page and menu title
            'page_slug' => 'i3fb_forms', // the page slug
        )
        );
    }
/**
* One of the pre-defined methods which is triggered when the registered page loads.
*
* Here we add form fields.
*/
    public function load_i3fb_forms( $oAdminPage ) { // load_{page slug}

        $this->addSettingFields(
            array( // Page ID field
                'field_id'      => 'i3fb_page_id',
                'type'          => 'text',
                'title'         => __( 'Page ID', 'i3fb-plugin' ),
                'description'   => __( 'Enter the ID of the page.', 'i3fb-plugin' )
                . ' <a href="#TB_inline?height=550&width=500&inlineId=i3fb-howto" class="thickbox" title="'.__('How to find a page ID?', 'i3fb-plugin').'">'.__('How to find a page ID?', 'i3fb-plugin').'</a>'
                . '<div id="i3fb-howto" style="display:none;">'.__( '<h4>Option 1:</h4>
                    <p>Log in into your dashboard and go to <strong>Manage > Pages</strong>. Hover over the page title you want to find the ID of. Your browser’s status bar will show you a URL ending with a number, this is your page ID.</p>
                    <h4>Option 2:</h4>
                    <p>Log in into your dashboard, go to <strong>Manage > Pages</strong>, click on the title of the page you want to find the ID of. Now you are on the edit page screen. Your browsers address bar will show you a URL ending with a number, this is your page ID.</p>
                    <h4>Example:</h4><pre>http://www.xyz.com/wp-admin/page.php?action=edit&post=<strong>12</strong></pre>
                    <p>In this example the page ID is <strong>12</strong>.</p>
                    ', 'i3fb-plugin').'</div>',
            
            ),
            array( // Button text field
                'field_id'      => 'i3fb_btn_txt',
                'type'          => 'text',
                'title'         => __( 'Button Text', 'i3fb-plugin' ),
                'description'   => __( 'Enter the button text.', 'i3fb-plugin' ),
                'default'       => 'FEEDBACK',
            ),
            array( // Position radioselect
                'field_id'      => 'i3fb_position',
                'title'         => __( 'Position', 'i3fb-plugin' ),
                'type'          => 'radio',
                'label'         => array(
                    'left'  => __( 'Left', 'i3fb-plugin' ),
                    'right' => __( 'Right', 'i3fb-plugin' ) 
                ),
                'default'       => 'left',
                'after_label'   => '<br />',
                'attributes'    => array(),
                'description'   => __( 'Display the button on the left or right side of the page.', 'i3fb-plugin' )    
            ),
            array( // Pixels from top
                'field_id'      => 'i3fb_top_position',
                'type'          => 'text',
                'title'         => __( 'Position from top (px)', 'i3fb-plugin' ),
                'default'       => 450,
                'description'   => __( 'Default: <code>450</code>', 'i3fb-plugin' )
            ),
            array( // Multiple text fields
                'field_id'          => 'i3fb_size',
                'title'             => __( 'Thickbox Size (px)', 'i3fb-plugin' ),
                'type'              => 'text',
                'default'           => 550,
                'label'             => __( 'Height', 'i3fb-plugin' ) . ': ',
                'attributes'        => array(
                    'size' => 20,     
                ),    
                'delimiter'         => '<br />',
                array(
                    'default'       => 450,
                    'label'         => __( 'Width', 'i3fb-plugin' ) . ': ',
                    'attributes'    => array(
                        'size' => 20,
                    )
                ),    
                'description'       => __( 'Enter height and width of the thickbox window. Default: <code>550h*450w</code>', 'i3fb-plugin' ),
            ), 
            array( // Colorpickers
                'field_id'      => 'i3fb_colors',
                'title'         => __( 'Button Colors', 'i3fb-plugin' ),
                'type'          => 'color',
                'label'         => __( 'Background', 'i3fb-plugin' ),
                'delimiter'     => '<br />',
                array(
                    'label' => __( 'Text', 'i3fb-plugin' ),
                ),    
            ),
            array( // Submit button
                'field_id'      => 'i3fb_submit_btn',
                'type'          => 'submit',
                'label'         => __( 'Save', 'i3fb-plugin' ),
            )
        );
    }
}
// Instantiate the class object.
if ( is_admin() ) {
    new APF_i3fb_Fields;
}








