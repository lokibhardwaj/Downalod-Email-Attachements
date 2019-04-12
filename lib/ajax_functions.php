<?php



////////////////////////////////////////////////////////////////////////////////
/// Get vehicle sub models
////////////////////////////////////////////////////////////////////////////////

add_action('wp_ajax_nopriv_bolsert_ajax_get_sub_models', 'bolsert_ajax_get_sub_models' );
add_action( 'wp_ajax_bolsert_ajax_get_sub_models', 'bolsert_ajax_get_sub_models' );

if( !function_exists('bolsert_ajax_get_sub_models') ):
    function bolsert_ajax_get_sub_models(){       
    
    }//Function close      
        
endif;   






// Import email folder maping

add_action('wp_ajax_nopriv_email_ajax_att_sub_folder', 'email_ajax_att_sub_folder' );
add_action( 'wp_ajax_email_ajax_att_sub_folder', 'email_ajax_att_sub_folder' );

if( !function_exists('email_ajax_att_sub_folder') ):
    function email_ajax_att_sub_folder(){ 
   
	 $att_Options = get_option ( ATTACHMENT_PREFIX );
	 
	  $hostname = trim($att_Options['hostName']);
	  $username = trim($att_Options['userName']);
	  $password = trim($att_Options['userPassword']);

		 
		 //$hostname = '{'.$hostname.'}INBOX'; 
		 $hostname = '{'.$hostname.'}'; 
		$inbox = imap_open($hostname,$username,$password);
		
		if (!$inbox) {
            $error  = array();
             $error['key'] = '0';
			 $error['msg']= '<div class="attError error login-error">Cannot connect to server: '.imap_last_error().'. check server imap settings. <a target="_blank" href="'. admin_url ( "admin.php?page=att_google_instructions" ).'">"Click here"</a></div>';
			echo json_encode($error);
		
        } else {
			$folder_options = array();
			
			$list = imap_list($inbox, $hostname, "*");
				if (is_array($list)) {	
				
					foreach ($list as $val) {
						$value = imap_utf7_decode($val) ;						
						$folder_options[$value] = $value;						
					}
				 
				 echo json_encode($folder_options);
				 	
				  update_option('email_selectFolder', $folder_options);

				} 
			
		}
		
		
    
	 
	 die();
    }//Function close      
        
endif;   






// Import folder  email folder maping by Click

add_action('wp_ajax_nopriv_import_ajax_att_sub_folder', 'import_ajax_att_sub_folder' );
add_action( 'wp_ajax_import_ajax_att_sub_folder', 'import_ajax_att_sub_folder' );

if( !function_exists('import_ajax_att_sub_folder') ):
    function import_ajax_att_sub_folder(){ 
	
   
	 $att_Options = get_option ( ATTACHMENT_PREFIX );
	 
	  $hostname = trim($_POST['hostName']);
	  $username = trim($_POST['userName']);
	  $password = trim($_POST['userPassword']);
	  
	   $hostname = '{'.$hostname.'}'; 
		$inbox = imap_open($hostname,$username,$password);
		
		if (!$inbox) {
            $error  = array();
             $error['key'] = '0';
			 $error['msg']= '<div class="attError error login-error">Cannot connect to server: '.imap_last_error().'. check server imap settings. <a target="_blank" href="'. admin_url ( "admin.php?page=att_google_instructions" ).'">"Click here"</a></div>';
			echo json_encode($error);
		
        } else {
			$folder_options = array();
			
			$list = imap_list($inbox, $hostname, "*");
				if (is_array($list)) {	
				
					foreach ($list as $val) {
						$value = imap_utf7_decode($val) ;						
						$folder_options[$value] = $value;						
					}
				 
				 echo json_encode($folder_options);
				 	
				  update_option('email_selectFolder', $folder_options);

				} 
			
		}
		
		
    
	 
	 die();
    }//Function close      
        
endif; 






// Send test shipped email by Click

add_action('wp_ajax_nopriv_send_ajax_test_shipped_email', 'send_ajax_test_shipped_email' );
add_action( 'wp_ajax_send_ajax_test_shipped_email', 'send_ajax_test_shipped_email' );

if( !function_exists('send_ajax_test_shipped_email') ):
    function send_ajax_test_shipped_email(){
	 
	 $current = $_POST['testShippedDate'];
	 $sapAccArr = explode(',',$_POST['testSapAccounts']);
	 $testEmail = $_POST['testEmail'];
	 
	 $user['ID'] = 0;
	 $user['user_email'] = $testEmail;
	 
	 $result = vcc_get_sipped_orders($sapAccArr,$user,$current,$testMail=1);
	
	 echo $result;
	 
	 die();
    }//Function close      
        
endif; 





?>