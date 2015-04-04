<?php
/*
Plugin Name: i3 Feedback Button
Plugin URI: 
Description: Displays a static button, on the left or right side of the website, which lets you open a page in an thickbox window.
Author: Mo
Version: 1.3
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




function i3fb_style_function() {

    // Get all admin options
    $i3fb_option = get_option( 'i3fb_settings' );


    // Get the button top margin
    $i3fb_btn_top = $i3fb_option['i3fb_btn_top_field'];

    // Get the button bg color
    $i3fb_btn_bg = $i3fb_option['i3fb_btn_bg_color_field'];

    // Get the button text color
    $i3fb_btn_txt = $i3fb_option['i3fb_btn_txt_color_field'];

    // Get the button top padding
    $i3fb_pad_top = $i3fb_option['i3fb_btn_padding_top'];

    // Get the button bottom padding
    $i3fb_pad_bot = $i3fb_option['i3fb_btn_padding_bottom'];

    // Get the button left padding
    $i3fb_pad_lef = $i3fb_option['i3fb_btn_padding_left'];

    // Get the button right padding
    $i3fb_pad_rig = $i3fb_option['i3fb_btn_padding_right'];

    // Get the button font-size
    $i3fb_btn_fs = $i3fb_option['i3fb_btn_typo_size'];

    // Get the button font-style
    $i3fb_btn_st = $i3fb_option['i3fb_btn_typo_style'];


    $output='<style type="text/css">

        a#i3fb-btn-container {
            top: '.$i3fb_btn_top.'%;
            background:'.$i3fb_btn_bg.';
            color:'.$i3fb_btn_txt.';
            font-size:'.$i3fb_btn_fs.'px;
            font-weight:'.$i3fb_btn_st.';
            padding-top:'.$i3fb_pad_top.'px;
            padding-bottom:'.$i3fb_pad_bot.'px;
            padding-left:'.$i3fb_pad_lef.'px;
            padding-right:'.$i3fb_pad_rig.'px;
        };

    </style>';

    echo $output;

}
add_action('wp_head','i3fb_style_function');


/*
 * Inserts and displays the template in wp_footer
 */
function i3fb_template_function() {

    // Get all admin options
    $i3fb_option = get_option( 'i3fb_settings' );


    // Get the button text field
    $i3fb_btn_caption = $i3fb_option['i3fb_btn_txt_field'];
    if (empty($i3fb_option['i3fb_btn_txt_field'])) {
        $i3fb_btn_caption = 'Feedback'; // if is emtpy display this default text
    }

    // Get the button position
    $i3fb_btn_pos = $i3fb_option['i3fb_btn_pos_field'];

    // Get the page select field
    $i3fb_tb_page = $i3fb_option['i3fb_btn_pselect_field'];

    // Get the thickbox height
    $i3fb_tb_height = $i3fb_option['i3fb_tbheight_field'];
    if (empty($i3fb_option['i3fb_tbheight_field'])) {
        $i3fb_tb_height = 400; // if is emtpy display this default value
    }

    // Get the thickbox width
    $i3fb_tb_width = $i3fb_option['i3fb_tbwidth_field'];
    if (empty($i3fb_option['i3fb_tbwidth_field'])) {
        $i3fb_tb_width = 300; // if is emtpy display this default value
    }


    /*
     * The frontend template
     */
    $post = get_post($i3fb_tb_page); // Grab the data of the page
    $title = apply_filters('the_title', $post->post_title); // Displays the post title
    $content = apply_filters('the_content', $post->post_content); // Displays the post content
     
    echo    '<a href="#TB_inline?height='.$i3fb_tb_height.'&width='.$i3fb_tb_width.'&inlineId=i3fb-thickbox-'.$i3fb_tb_page.'" title="'.$title.'" id="i3fb-btn-container" class="thickbox '.$i3fb_btn_pos.'">'.$i3fb_btn_caption.'</a>';

    echo    '<div id="i3fb-thickbox-'.$i3fb_tb_page.'" class="cf" style="display:none">';
    echo        $content;
    echo    '</div>';

}
add_action('wp_footer', 'i3fb_template_function');


/*
 * Include the admin page
 */
include_once( dirname( __FILE__ ) . '/admin-page/i3fb-admin-page.php' );