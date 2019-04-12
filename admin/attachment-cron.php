<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// hook that function onto our scheduled event:
add_action ('whss_mycronjob', 'my_repeat_function');
// here's the function we'd like to call with our cron job
function my_repeat_function() {
	
	//Download Attachments function call
	 help_attachment_download();
	 
	
	 
	// components for our email
	$to = 'gill.virender@gmail.com';
	
	$subject = 'Test CRON Mail from-'.site_url();
	
	$default =  date('h:i:s A');
	 
	 date_default_timezone_set(get_option('timezone_string')); // CDT
	 $current_date = date('m/d/Y == H:i:s A');
	
	 $ship_date =  date('m/d/Y', strtotime(' -1 day'));
	 $hrs = date('H');
	
	
	$body = 'The test email body content';	
	
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	wp_mail( $to, $subject, $body, $headers );
	
	
}

//add_action ('mainwp_child_cron_theme_health_check_watcher', 'my_repeat_function');
//add_action ('init', 'my_repeat_function');




?>
