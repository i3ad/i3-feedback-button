<?php
/*
Plugin Name: i3 Feedback Button
Plugin URI: 
Description: Displays a static button, on the left or right side of the website, which lets you open a page in an thickbox window.
Author: Mo
Version: 1.2
Author URI: 
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Table of contents
 *
 * 1. Init function
 *    - Load textdomain
 *    - Add thickbox
 * 2. Enqueue frontend stylesheet
 * 3. Inserts and displays the template in wp_footer
 *    - Get the admin options
 *    - The frontend template
 * 4. Include the admin page
 *
 */


/*
 * Init function
 */
function i3fb_init_method() {
    load_plugin_textdomain('i3fb-plugin', false, basename( dirname( __FILE__ ) ) . '/languages' );
    add_thickbox(); // Enable build-in thickbox
}
add_action('init', 'i3fb_init_method');


/*
 * Enqueue frontend stylesheet
 */
function i3fb_styles() {
    if ( ! is_admin() ) {
        wp_enqueue_style( 'i3fb-styles', plugins_url('/assets/css/style.css', __FILE__) );
    }    
}
add_action('wp_enqueue_scripts', 'i3fb_styles');


/*
 * Inserts and displays the template in wp_footer
 */
function i3fb_template_function() {

    // Get all admin options
    $i3fb_admin_field = get_option( 'i3fb_settings' );

    // Get the page select field
    $i3fb_field_pselect = $i3fb_admin_field['i3fb_btn_pselect_field'];

    // Get the button text field
    $i3fb_field_btn_txt = $i3fb_admin_field['i3fb_btn_txt_field'];
    if (empty($i3fb_admin_field['i3fb_btn_txt_field'])) {
        $i3fb_field_btn_txt = 'Feedback';
    }

    // Get the button position
    $i3fb_field_btn_pos = $i3fb_admin_field['i3fb_btn_pos_field'];

    // Get the button top margin
    $i3fb_field_btn_top = $i3fb_admin_field['i3fb_btn_top_field'];

    // Get the button bg color
    $i3fb_field_btn_bg_color = $i3fb_admin_field['i3fb_btn_bg_color_field'];

    // Get the button text color
    $i3fb_field_btn_txt_color = $i3fb_admin_field['i3fb_btn_txt_color_field'];

    // Get the thickbox height
    $i3fb_field_tb_height = $i3fb_admin_field['i3fb_tbheight_field'];
    if (empty($i3fb_admin_field['i3fb_tbheight_field'])) {
        $i3fb_field_tb_height = 400;
    }

    // Get the thickbox width
    $i3fb_field_tb_width = $i3fb_admin_field['i3fb_tbwidth_field'];
    if (empty($i3fb_admin_field['i3fb_tbwidth_field'])) {
        $i3fb_field_tb_width = 300;
    }


    /*
     * The frontend template
     */
    $post = get_post($i3fb_field_pselect); // Grab the data of the page
    $title = apply_filters('the_title', $post->post_title); // Displays the post title
    $content = apply_filters('the_content', $post->post_content); // Displays the post content
     
    echo    '<a href="#TB_inline?height='.$i3fb_field_tb_height.'&width='.$i3fb_field_tb_width.'&inlineId=i3fb-thickbox-'.$i3fb_field_pselect.'" title="'.$title.'" id="i3fb-btn-container" class="thickbox '.$i3fb_field_btn_pos.'" style="top:'.$i3fb_field_btn_top.'%; background:'.$i3fb_field_btn_bg_color.'; color:'.$i3fb_field_btn_txt_color.';">'.$i3fb_field_btn_txt.'</a>';

    echo    '<div id="i3fb-thickbox-'.$i3fb_field_pselect.'" class="cf" style="display:none">';
    echo        $content;
    echo    '</div>';

}
add_action('wp_footer', 'i3fb_template_function');


/*
 * Include the admin page
 */
include_once( dirname( __FILE__ ) . '/admin-page/i3fb-admin-page.php' );