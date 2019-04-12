<?php
/** @package date picker
 * @version 1 
 */
/*
Plugin Name: Download email attachments
Plugin URI: http://www.web-hike.com/ 
Description: Download attachments form email account.
Author: wHss team
Version: 1.2.0
Author URI: http://www.web-hike.com/

*/

error_reporting(E_ERROR | E_PARSE);
define('WP_NO_RECORD', 'No record found!');

define ( 'ATTACHMENT_PREFIX', 'att' );
define ( 'SHIPPED_PREFIX', 'vcc_shipped' );
define ( 'WPCSV_PREFIX', 'impCsv' );
ini_set('auto_detect_line_endings', TRUE);


/* Set constant path to the plugin directory. */
define('WP_EMAIL_ATT_PATH', plugin_dir_path(__FILE__));

/* Set constant url to the plugin directory. */
define('WP_EMAIL_ATT_URL', plugin_dir_url(__FILE__));


//echo WP_EMAIL_ATT_URL;


define('WP_EMAIL_ATT_TPL', WP_EMAIL_ATT_PATH . trailingslashit('template'), true);
define('WP_EMAIL_ATT_LIB', WP_EMAIL_ATT_PATH . trailingslashit('lib'), true);
define('WP_EMAIL_ATT_ADMIN', WP_EMAIL_ATT_PATH . trailingslashit('admin'), true);

//Full Current Url	
define('WP_FULL_URL', "http://" . $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);
	
	
	
//Custom post type
//require_once( WP_CUSTOM_BUDDYPRESS_INC . 'create_custom_post.php' );


require_once( WP_EMAIL_ATT_LIB . 'css_js_include.php' );
require_once( WP_EMAIL_ATT_LIB . 'ajax_functions.php' );
require_once( WP_EMAIL_ATT_LIB . 'help_functions.php' );
require_once( WP_EMAIL_ATT_LIB . 'functions.php' );


require_once( WP_EMAIL_ATT_ADMIN . 'attachment-cron.php' );

/********************** ****************
Admin settings include 
*****/
function att_attachments_list()
{   
	require_once( WP_EMAIL_ATT_ADMIN . 'attachment-list.php' );
}

function att_attachments_settings()
{   
	require_once( WP_EMAIL_ATT_ADMIN . 'attachment-settings.php' );
}
function att_attachments_download()
{   
	require_once( WP_EMAIL_ATT_ADMIN . 'attachments-download.php' );
}

function att_google_instructions()
{   
	require_once( WP_EMAIL_ATT_ADMIN . 'google-settings-instruction.php' );
}

function att_logs()
{   
	require_once( WP_EMAIL_ATT_ADMIN . 'attachment-logs.php' );
}

function import_csv_settings(){
	require_once( WP_EMAIL_ATT_ADMIN . 'import-csv-settings.php' );
}

function email_import_wp_csv()
{   
	require_once( WP_EMAIL_ATT_ADMIN . 'upload_csv.php' );
}

function vcc_shipped_email_config()
{   
	require_once( WP_EMAIL_ATT_ADMIN . 'vcc_shipped_email_config.php' );
}


function order_shortcode()
{
   require_once( WP_EMAIL_ATT_ADMIN . 'admin-short-codes.php' );	
}






function att_admin_menu()
{
	
  add_menu_page("Download email attachments", "Download email attachments", 8, "att_attachments_list","att_attachments_list",WP_EMAIL_ATT_URL.'images/AvectiaA_22.png',4);
  
  add_submenu_page("att_attachments_list", "1", "Email attachments", 8, "att_attachments_list", "att_attachments_list");
  
  add_submenu_page("att_attachments_list", "1", "Settings", 8, "att_attachments_settings", "att_attachments_settings");
  
  add_submenu_page("att_attachments_list", "1", "Download Attachments", 8, "att_attachments_download", "att_attachments_download");
  
  
  add_submenu_page("att_attachments_list", "1", "Server setting instructions", 8, "att_google_instructions", "att_google_instructions");
  
  
   add_submenu_page("att_attachments_list", "1", "Log details", 8, "att_logs", "att_logs");
   
   
   
	 
	 
	
	 
	 
	 
	
	
  
}
add_action('admin_menu', 'att_admin_menu');

if (! class_exists ( 'wpAttachments' )) {
	class wpAttachments {
		private $options = array ();
		private $options_csv = array ();
		
		public function __construct() {
			$this->options = get_option ( ATTACHMENT_PREFIX );
			if (is_admin ()) {
				add_action ( 'admin_init', array ( &$this, 'att_on_admin_Init' ) );
				
            }
			
			$this->options_csv = get_option ( WPCSV_PREFIX );
			if (is_admin ()) {
				add_action ( 'admin_init', array ( &$this, 'csv_on_admin_Init' ) );
				
            }
			//Shipped email 
			$this->options_csv = get_option ( SHIPPED_PREFIX );
			if (is_admin ()) {
				add_action ( 'admin_init', array ( &$this, 'vcc_shipped_on_admin_Init' ) );
				
            }
			
		}
		
	function vcc_shipped_on_admin_Init() {
			register_setting ( SHIPPED_PREFIX.'_options', SHIPPED_PREFIX, array (
					&$this,
					'vcc_shipped_on_update_options' 
			) );
			
          }	
		
	  function csv_on_admin_Init() {
			register_setting ( WPCSV_PREFIX.'_options', WPCSV_PREFIX, array (
					&$this,
					'csv_on_update_options' 
			) );
			
          }
		
         function att_on_admin_Init() {
			register_setting ( ATTACHMENT_PREFIX.'_options', ATTACHMENT_PREFIX, array (
					&$this,
					'att_on_update_options' 
			) );
			
          }
          function att_on_update_options($option) {
			return $option;
		   }
		   
		   function csv_on_update_options($option) {
			return $option;
		   }
		   
		   function vcc_shipped_on_update_options($option) {
			return $option;
		   }
		   
		   	
		
    } //Class Off
   $wpAttachments = new wpAttachments ();
 
}//If Class Exist



//Interval Cronjob Settings

function isa_add_every_three_minutes( $schedules ) {
  $att_Options = get_option ( ATTACHMENT_PREFIX );
 
 
  if($att_Options['cronjobTime'] =='every_30_minutes'){
		$schedules['every_30_minutes'] = array(
				'interval'  => 1800,
				'display'   => __( 'Every 30 Minutes', 'email-attachment' )
		);
		return $schedules;
   }
   
   if($att_Options['cronjobTime'] =='every_five_minutes'){
   $schedules['every_five_minutes'] = array(
				'interval'  => 300,
				'display'   => __( 'Every 5 Minutes', 'email-attachment' )
		);
		return $schedules;
   }
   
   if($att_Options['cronjobTime'] =='every_one_minutes'){
   
   $schedules['every_one_minutes'] = array(
            'interval'  => 60,
            'display'   => __( 'Every 1 Minutes', 'email-attachment' )
    );
	
	return $schedules;
   }
   
   
  if($att_Options['cronjobTime'] =='after_6_hours'){
   $schedules['after_6_hours'] = array(
				'interval'  => 21600,
				'display'   => __( 'Every 5 Minutes', 'email-attachment' )
		);
		return $schedules;
   }
   
   
   
  if($att_Options['cronjobTime'] =='weekly'){
	   $schedules['weekly'] = array(
			'interval' => 604800,
			'display' => __('Once Weekly')
		);
		
		return $schedules;
   }
   
   if($att_Options['cronjobTime'] =='monthly'){

	$schedules['monthly'] = array(
		'interval' => 2635200,
		'display' => __('Once a month')
	);
	
	return $schedules;
   }
   
   
   
}
add_filter( 'cron_schedules', 'isa_add_every_three_minutes' );


// create a scheduled event (if it does not exist already)
function cronstarter_activation() {
	
	
	 $att_Options = get_option ( ATTACHMENT_PREFIX );

	
	  if( $att_Options['allowCronjob'] == "1"){
		 
		 
			if( !wp_next_scheduled( 'whss_mycronjob' ) ) {  
			   wp_schedule_event( time(), $att_Options['cronjobTime'], 'whss_mycronjob' ); 
			   
                			   
			}
			
			
	
	  }

    	
}
add_action('admin_init', 'cronstarter_activation');




// unschedule event upon plugin deactivation
function cronstarter_deactivate() {	
	// find out when the last event was scheduled
	$timestamp = wp_next_scheduled ('whss_mycronjob');
	// unschedule previous event if any
	wp_unschedule_event ($timestamp, 'whss_mycronjob');
} 
register_deactivation_hook (__FILE__, 'cronstarter_deactivate');



register_activation_hook( __FILE__, 'email_download_att_install' );
 function email_download_att_install(){
	 global $wpdb;
	

	
 }



?>