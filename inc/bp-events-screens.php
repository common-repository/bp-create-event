<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



function bp_events_profile() {
	add_action( 'bp_template_content', 'bp_events_profile_screen' );
	bp_core_load_template( 'profile/plugins' );
}


function bp_events_profile_screen() {
	bp_get_template_part('profile/bp-profile-page-loop');
}


function bp_events_profile_create() {
	require( BP_EVENTS_DIR . '/inc/bp-events-create-class.php' );
	add_action( 'bp_template_title', 'bp_events_profile_create_title' );
	add_action( 'bp_template_content', 'bp_events_profile_create_screen' );
	bp_core_load_template( 'profile/plugins' );
}

function bp_events_profile_create_title() {

	if( isset( $_GET['eid'] ) )
	    echo __( 'Edit Event', 'bp-create-events' );
	else
		echo __( 'Create an Event', 'bp-create-events' );
}


function bp_events_profile_create_screen() {
	bp_get_template_part('profile/bp-profile-page-create');
}


function bp_events_profile_archive() {
	add_action( 'bp_template_content', 'bp_events_profile_archive_screen' );
	bp_core_load_template( 'profile/plugins' );
}

function bp_events_profile_archive_screen() {
	bp_get_template_part('profile/bp-profile-page-archive');
}


function bp_events_profile_enqueue() {

	if ( ( bp_is_my_profile() || is_super_admin() ) && 'create' == bp_current_action() ) {

		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-timepicker', plugin_dir_url(__FILE__) . '/js/jquery.ui.timepicker.min.js' );
		wp_enqueue_style( 'jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css', true);

		wp_enqueue_script('script', plugin_dir_url(__FILE__) . '/js/events.js', array('jquery'));
//wp_enqueue_script('script1', plugin_dir_url(__FILE__) . '/js/jquery.validate.js');
//wp_enqueue_script('script2', plugin_dir_url(__FILE__) . '/js/jquery-latest.js');

		wp_register_script( 'google-places-api', 'http://maps.google.com/maps/api/js?libraries=places' );
		wp_print_scripts( 'google-places-api' );

	}
}
add_action('wp_enqueue_scripts', 'bp_events_profile_enqueue');

