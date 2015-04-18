<?php

/*
 * Table of content
 *
 * 1. Add scripts and styles for the admin page
 * 2. Register the admin page
 * 3. Add page sections and setting fields
 * 4. Setting fields templates
 * 5. Section descriptions
 * 6. Building the admin page layout
 *
 */


/*
 * Add scripts and styles for Chosen and WP Color Picker
 */
function i3fb_admin_enqueue($hook) {

	// Only enqueue scripts on specific admin page
	global $i3fb_settings_page;

	if( $hook != $i3fb_settings_page ) 
		return;

	// Add Chosen js
	wp_register_script( 'i3fb-chosen-js', plugins_url('chosen/chosen.jquery.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'i3fb-chosen-js' ); 

	// Add Chosen css
	wp_register_style( 'i3fb-chosen-css', plugins_url('chosen/chosen.min.css', __FILE__ ));
	wp_enqueue_style( 'i3fb-chosen-css' ); 

	// Add WP Color Picker
    wp_enqueue_style( 'wp-color-picker' );

    // Add Chosen and WP Color Picker options
	wp_register_script( 'i3fb-admin-js', plugins_url('i3fb-admin-page.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	wp_enqueue_script( 'i3fb-admin-js' ); 
}
add_action( 'admin_enqueue_scripts', 'i3fb_admin_enqueue' );


/*
 * Add page to the WP Settings menu
 */
function i3fb_add_admin_menu() { 

	// Setting the global variable
	global $i3fb_settings_page;

	$i3fb_settings_page = add_options_page( 
		'Feedback Button', // Page title
	 	'Feedback Button', // Menu title
	 	'manage_options', // Capability
	 	'i3_feedback_button', // Page slug
	 	'i3_feedback_button_options_page' // Callback function
	);
}
add_action( 'admin_menu', 'i3fb_add_admin_menu' );


/*
 * Building options page sections and setting fields
 */
function i3fb_settings_init() { 

	register_setting( 'pluginPage', 'i3fb_settings' );

	/* 
	 * Add page sections 
	 */

	// Add first section, "Button Settings"
	add_settings_section(
		'i3fb_section_btn', 
		__( 'Button Settings', 'i3fb-plugin' ), 
		'i3fb_section_btn_callback', 
		'pluginPage'
	);

	// Add second section, "Thickbox Settings"
	add_settings_section(
		'i3fb_section_tb', 
		__( 'Thichbox Settings', 'i3fb-plugin' ), 
		'i3fb_section_tb_callback', 
		'pluginPage'
	);

	/*
	 * Add page setting fields
	 */

	/* Button setting fields */

	// Page select setting
	add_settings_field( 
		'i3fb_btn_pselect_field', 
		__( 'Button links to', 'i3fb-plugin' ), 
		'i3fb_btn_pselect_field_render', 
		'pluginPage', 
		'i3fb_section_btn' 
	);

	// Button position setting
	add_settings_field( 
		'i3fb_btn_pos_field', 
		__( 'Button position', 'i3fb-plugin' ), 
		'i3fb_btn_pos_field_render', 
		'pluginPage', 
		'i3fb_section_btn' 
	);

	// Button top margin setting
	add_settings_field( 
		'i3fb_btn_top_field', 
		__( 'Button top spacing (%)', 'i3fb-plugin' ), 
		'i3fb_btn_top_field_render', 
		'pluginPage', 
		'i3fb_section_btn' 
	);

	// Button background color setting
	add_settings_field( 
		'i3fb_btn_bg_color_field', 
		__( 'Button background color', 'i3fb-plugin' ), 
		'i3fb_btn_bg_color_field_render', 
		'pluginPage', 
		'i3fb_section_btn' 
	);

	// Button text setting
	add_settings_field( 
		'i3fb_btn_txt_field', 
		__( 'Button text', 'i3fb-plugin' ), 
		'i3fb_btn_txt_field_render', 
		'pluginPage', 
		'i3fb_section_btn' 
	);

	// Button typographie
	add_settings_field( 
		'i3fb_btn_typo', 
		__( 'Button Typographie', 'i3fb-plugin' ), 
		'i3fb_btn_typo_render', 
		'pluginPage', 
		'i3fb_section_btn'
	);


/* Thickbox setting fields */

	// Thickbox height setting
	add_settings_field( 
		'i3fb_tbheight_field', 
		__( 'Thickbox height (px)', 'i3fb-plugin' ), 
		'i3fb_tbheight_field_render', 
		'pluginPage', 
		'i3fb_section_tb' 
	);

	// Thickbox width setting
	add_settings_field( 
		'i3fb_tbwidth_field', 
		__( 'Thickbox width (px)', 'i3fb-plugin' ), 
		'i3fb_tbwidth_field_render', 
		'pluginPage', 
		'i3fb_section_tb'
	);

}
add_action( 'admin_init', 'i3fb_settings_init' );


/*
 * HTML templates for the single fields
 */

// Page select field
function i3fb_btn_pselect_field_render(  ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<select data-placeholder='<?php _e('Select a Page', 'i3fb-plugin'); ?>' style='width:300px;' class='chosen-select' name='i3fb_settings[i3fb_btn_pselect_field]'>
		<option value='0'></option>
		<?php $pages = get_pages(); ?>
		<?php foreach( $pages as $page ) { ?>
			<option value='<?php echo $page->ID; ?>' <?php selected( $options['i3fb_btn_pselect_field'], $page->ID ); ?>><?php echo $page->post_title; ?></option>
		<?php }; ?>
	</select>
	<p class="description"><em><?php _e('Define the page you want to open in the Thickbox window.', 'i3fb-plugin'); ?></em></p>

	<?php
}

// Button text field
function i3fb_btn_txt_field_render(  ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<input type='text' name='i3fb_settings[i3fb_btn_txt_field]' value='<?php if (empty($options['i3fb_btn_txt_field'])) { echo 'Feedback'; }else{ echo $options['i3fb_btn_txt_field']; }; ?>'>
	
	<?php
}

// Button position field
function i3fb_btn_pos_field_render(  ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<label><?php _e('Left', 'i3fb-plugin'); ?> <input type='radio' name='i3fb_settings[i3fb_btn_pos_field]' <?php if (empty($options['i3fb_btn_pos_field'])) { echo 'checked=checked'; }else{ echo checked( $options['i3fb_btn_pos_field'], left ); }; ?> value='left'></label>
	<label><?php _e('Right', 'i3fb-plugin'); ?> <input type='radio' name='i3fb_settings[i3fb_btn_pos_field]' <?php checked( $options['i3fb_btn_pos_field'], right ); ?> value='right'></label>
	
	<?php
}

// Button top margin
function i3fb_btn_top_field_render(  ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<input type='text' name='i3fb_settings[i3fb_btn_top_field]' value='<?php if (empty($options['i3fb_btn_top_field'])) { echo '40'; }else{ echo $options['i3fb_btn_top_field']; }; ?>'>
	<p class="description"><em><?php _e('Default:', 'i3fb-plugin'); ?> <code>40</code></em></p>
	
	<?php
}

// Button background color field
function i3fb_btn_bg_color_field_render(  ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<input class='wp-color-picker-field' type='text' name='i3fb_settings[i3fb_btn_bg_color_field]' value='<?php if (empty($options['i3fb_btn_bg_color_field'])) { echo '#dddddd'; }else{ echo $options['i3fb_btn_bg_color_field']; }; ?>'>
	
	<?php
}

// Button typographie
function i3fb_btn_typo_render( $args ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<span style="vertical-align:top;">

		<select data-placeholder='13px' style='width:80px;' class='chosen-select' name='i3fb_settings[i3fb_btn_typo_size]'>
			<option value='13'></option>
			<option value='9' <?php selected( $options['i3fb_btn_typo_size'], '9' ); ?>>9px</option>
			<option value='10' <?php selected( $options['i3fb_btn_typo_size'], '10' ); ?>>10px</option>
			<option value='11' <?php selected( $options['i3fb_btn_typo_size'], '11' ); ?>>11px</option>
			<option value='12' <?php selected( $options['i3fb_btn_typo_size'], '12' ); ?>>12px</option>
			<option value='13' <?php selected( $options['i3fb_btn_typo_size'], '13' ); ?>>13px</option>
			<option value='14' <?php selected( $options['i3fb_btn_typo_size'], '14' ); ?>>14px</option>
			<option value='15' <?php selected( $options['i3fb_btn_typo_size'], '15' ); ?>>15px</option>
			<option value='16' <?php selected( $options['i3fb_btn_typo_size'], '16' ); ?>>16px</option>
			<option value='17' <?php selected( $options['i3fb_btn_typo_size'], '17' ); ?>>17px</option>
			<option value='18' <?php selected( $options['i3fb_btn_typo_size'], '18' ); ?>>18px</option>
			<option value='19' <?php selected( $options['i3fb_btn_typo_size'], '19' ); ?>>19px</option>
			<option value='20' <?php selected( $options['i3fb_btn_typo_size'], '20' ); ?>>20px</option>
			<option value='21' <?php selected( $options['i3fb_btn_typo_size'], '21' ); ?>>21px</option>
			<option value='22' <?php selected( $options['i3fb_btn_typo_size'], '22' ); ?>>22px</option>
			<option value='23' <?php selected( $options['i3fb_btn_typo_size'], '23' ); ?>>23px</option>
			<option value='24' <?php selected( $options['i3fb_btn_typo_size'], '24' ); ?>>24px</option>
			<option value='25' <?php selected( $options['i3fb_btn_typo_size'], '25' ); ?>>25px</option>
			<option value='26' <?php selected( $options['i3fb_btn_typo_size'], '26' ); ?>>26px</option>
			<option value='27' <?php selected( $options['i3fb_btn_typo_size'], '27' ); ?>>27px</option>
			<option value='28' <?php selected( $options['i3fb_btn_typo_size'], '28' ); ?>>28px</option>
			<option value='29' <?php selected( $options['i3fb_btn_typo_size'], '29' ); ?>>29px</option>
			<option value='30' <?php selected( $options['i3fb_btn_typo_size'], '30' ); ?>>30px</option>
			<option value='31' <?php selected( $options['i3fb_btn_typo_size'], '31' ); ?>>31px</option>
			<option value='32' <?php selected( $options['i3fb_btn_typo_size'], '32' ); ?>>32px</option>
			<option value='33' <?php selected( $options['i3fb_btn_typo_size'], '33' ); ?>>33px</option>
			<option value='34' <?php selected( $options['i3fb_btn_typo_size'], '34' ); ?>>34px</option>
			<option value='35' <?php selected( $options['i3fb_btn_typo_size'], '35' ); ?>>35px</option>
			<option value='36' <?php selected( $options['i3fb_btn_typo_size'], '36' ); ?>>36px</option>
			<option value='37' <?php selected( $options['i3fb_btn_typo_size'], '37' ); ?>>37px</option>
			<option value='38' <?php selected( $options['i3fb_btn_typo_size'], '38' ); ?>>38px</option>
			<option value='39' <?php selected( $options['i3fb_btn_typo_size'], '39' ); ?>>39px</option>
			<option value='40' <?php selected( $options['i3fb_btn_typo_size'], '40' ); ?>>40px</option>
			<option value='41' <?php selected( $options['i3fb_btn_typo_size'], '41' ); ?>>41px</option>
			<option value='42' <?php selected( $options['i3fb_btn_typo_size'], '42' ); ?>>42px</option>
			<option value='43' <?php selected( $options['i3fb_btn_typo_size'], '43' ); ?>>43px</option>
			<option value='44' <?php selected( $options['i3fb_btn_typo_size'], '44' ); ?>>44px</option>
			<option value='45' <?php selected( $options['i3fb_btn_typo_size'], '45' ); ?>>45px</option>
			<option value='46' <?php selected( $options['i3fb_btn_typo_size'], '46' ); ?>>46px</option>
			<option value='47' <?php selected( $options['i3fb_btn_typo_size'], '47' ); ?>>47px</option>
			<option value='48' <?php selected( $options['i3fb_btn_typo_size'], '48' ); ?>>48px</option>
			<option value='49' <?php selected( $options['i3fb_btn_typo_size'], '49' ); ?>>49px</option>
			<option value='50' <?php selected( $options['i3fb_btn_typo_size'], '50' ); ?>>50px</option>
			<option value='51' <?php selected( $options['i3fb_btn_typo_size'], '51' ); ?>>51px</option>
			<option value='52' <?php selected( $options['i3fb_btn_typo_size'], '52' ); ?>>52px</option>
			<option value='53' <?php selected( $options['i3fb_btn_typo_size'], '53' ); ?>>53px</option>
			<option value='54' <?php selected( $options['i3fb_btn_typo_size'], '54' ); ?>>54px</option>
			<option value='55' <?php selected( $options['i3fb_btn_typo_size'], '55' ); ?>>55px</option>
			<option value='56' <?php selected( $options['i3fb_btn_typo_size'], '56' ); ?>>56px</option>
			<option value='57' <?php selected( $options['i3fb_btn_typo_size'], '57' ); ?>>57px</option>
			<option value='58' <?php selected( $options['i3fb_btn_typo_size'], '58' ); ?>>58px</option>
			<option value='59' <?php selected( $options['i3fb_btn_typo_size'], '59' ); ?>>59px</option>
			<option value='60' <?php selected( $options['i3fb_btn_typo_size'], '60' ); ?>>60px</option>
			<option value='61' <?php selected( $options['i3fb_btn_typo_size'], '61' ); ?>>61px</option>
			<option value='62' <?php selected( $options['i3fb_btn_typo_size'], '62' ); ?>>62px</option>
			<option value='63' <?php selected( $options['i3fb_btn_typo_size'], '63' ); ?>>63px</option>
			<option value='64' <?php selected( $options['i3fb_btn_typo_size'], '64' ); ?>>64px</option>
			<option value='65' <?php selected( $options['i3fb_btn_typo_size'], '65' ); ?>>65px</option>
			<option value='66' <?php selected( $options['i3fb_btn_typo_size'], '66' ); ?>>66px</option>
			<option value='67' <?php selected( $options['i3fb_btn_typo_size'], '67' ); ?>>67px</option>
			<option value='68' <?php selected( $options['i3fb_btn_typo_size'], '68' ); ?>>68px</option>
			<option value='69' <?php selected( $options['i3fb_btn_typo_size'], '69' ); ?>>69px</option>
			<option value='70' <?php selected( $options['i3fb_btn_typo_size'], '70' ); ?>>70px</option>
		</select>

		<select data-placeholder='Normal' style='width:100px;' class='chosen-select' name='i3fb_settings[i3fb_btn_typo_style]'>
			<option value='0'></option>
			<option value='normal' <?php selected( $options['i3fb_btn_typo_style'], 'normal' ); ?>>Normal</option>
			<option value='bold' <?php selected( $options['i3fb_btn_typo_style'], 'bold' ); ?>>Bold</option>
		</select>

	</span>

	<input class='wp-color-picker-field' style='display:block;' type='text' name='i3fb_settings[i3fb_btn_txt_color_field]' value='<?php if (empty($options['i3fb_btn_txt_color_field'])) { echo '#000000'; }else{ echo $options['i3fb_btn_txt_color_field']; }; ?>'>

	<p class="description"><em><?php _e('Select typography for the button.', 'i3fb-plugin'); ?></em></p>	
	
	<?php 
}

/* Thickbox setting fields */

// Thickbox height field
function i3fb_tbheight_field_render(  ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<input type='text' name='i3fb_settings[i3fb_tbheight_field]' value='<?php if (empty($options['i3fb_tbheight_field'])) { echo '400'; }else{ echo $options['i3fb_tbheight_field']; }; ?>'>
	<p class="description"><em><?php _e('Default:', 'i3fb-plugin'); ?> <code>400</code></em></p>
	
	<?php
}

// Thickbox width field
function i3fb_tbwidth_field_render(  ) { 
	$options = get_option( 'i3fb_settings' ); ?>

	<input type='text' name='i3fb_settings[i3fb_tbwidth_field]' value='<?php if (empty($options['i3fb_tbwidth_field'])) { echo '300'; }else{ echo $options['i3fb_tbwidth_field']; }; ?>'>
	<p class="description"><em><?php _e('Default:', 'i3fb-plugin'); ?> <code>300</code></em></p>
	
	<?php
}


/*
 * Section callbacks
 */
// Button settings description
function i3fb_section_btn_callback() { 
	echo __( 'Change content and visual style of the Feedback Button.', 'i3fb-plugin' );
}

// Thickbox settings description
function i3fb_section_tb_callback() { 
	echo __( 'Change the size of the Thickbox window.', 'i3fb-plugin' );
}


/*
 * Building the options page layout
 */
function i3_feedback_button_options_page() { ?>

	<div class="wrap">
		<h2><?php _e('Feedback Button Settings', 'i3fb-plugin'); ?></h2>
		<form action='options.php' method='post'>
		
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>
		
		</form>
	</div>
	
	<?php
} ?>