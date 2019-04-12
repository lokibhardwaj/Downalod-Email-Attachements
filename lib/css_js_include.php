<?php

///////////////////////////////////////////////////////////////////////////////////////////
/////// Js & Css include on front And Admin site 
///////////////////////////////////////////////////////////////////////////////////////////


add_action( 'wp_head', 'att_style' );
function att_style() {
	

    wp_register_style( 'email_attachment', WP_EMAIL_ATT_URL.'css/email_attachment.css' );
	wp_enqueue_style( 'email_attachment' );
	
	 wp_register_style( 'pagi', WP_EMAIL_ATT_URL.'css/wp_csv_pagination.css' );
	 wp_enqueue_style( 'pagi' );
	 
	 /*wp_register_style( 'date-picker-css', WP_EMAIL_ATT_URL.'css/datepicker.css' );
	 wp_enqueue_style( 'date-picker-css' );
	 
	 
	 wp_register_style( 'date-picker-bootstrap', WP_EMAIL_ATT_URL.'css/bootstrap.css' );
	 wp_enqueue_style( 'date-picker-bootstrap' );
	 */
	 wp_register_style( 'jquery_date', WP_EMAIL_ATT_URL.'css/jquery_date.css' );
	 wp_enqueue_style( 'jquery_date' );
	 
	 wp_register_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	 wp_enqueue_style( 'font-awesome' );
	 
	 //vcc_tabbed.css
	 wp_register_style( 'vcc_tabbed', WP_EMAIL_ATT_URL.'css/vcc_tabbed.css' );
	 wp_enqueue_style( 'vcc_tabbed' );
	 
	 
	
	/************** Apply Js files***/
	wp_register_script('jquery-att', WP_EMAIL_ATT_URL. 'js/jquery_functions.js');
	wp_enqueue_script( 'jquery-att' );	//Custom jquery functions
	
	/*
	wp_register_script('date-picker', WP_EMAIL_ATT_URL. 'js/bootstrap-datepicker.js');
	wp_enqueue_script( 'date-picker' );	//Custom jquery functions
	*/
	
	wp_register_script('jquery_date_ui', WP_EMAIL_ATT_URL. 'js/jquery_date_ui.js');
	wp_enqueue_script( 'jquery_date_ui' );	//Custom jquery functions
	
	//Tabed js
	
	wp_register_script('jquery.easytabs', WP_EMAIL_ATT_URL. 'js/jquery.easytabs.min.js');
	wp_enqueue_script( 'jquery.easytabs' );	//Custom jquery functions
   
	
}


// Add css file in Admin 
add_action( 'admin_enqueue_scripts', 'att_load_admin_style' );
 function att_load_admin_style() {
	 
	 wp_register_style( 'admin-email', WP_EMAIL_ATT_URL.'css/admin-email_attachment.css' );
	 wp_enqueue_style( 'admin-email' );
	 
	 //pagination
	 
	 wp_register_style( 'pagi', WP_EMAIL_ATT_URL.'css/wp_csv_pagination.css' );
	 wp_enqueue_style( 'pagi' );
	 
	 //date calender css
	  wp_register_style( 'jquery_date', WP_EMAIL_ATT_URL.'css/jquery_date.css' );
	  wp_enqueue_style( 'jquery_date' );
	  
	  
	  
	
	/************** Apply Js files***/
	
	//Date calender
	wp_register_script('jquery_date_ui', WP_EMAIL_ATT_URL. 'js/jquery_date_ui.js');
	wp_enqueue_script( 'jquery_date_ui' );	//Custom jquery functions
	
	
	wp_register_script('jquery-att', WP_EMAIL_ATT_URL. 'js/custom_email_attachment.js');
	wp_enqueue_script( 'jquery-att' );	//Custom jquery functions
	
	
	wp_register_script('jquery-val', WP_EMAIL_ATT_URL. 'js/jquery.validate.js');
	wp_enqueue_script( 'jquery-val');
	
	
   }
?>