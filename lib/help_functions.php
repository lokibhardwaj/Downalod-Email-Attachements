<?php


//Download Attachments
 if( ! function_exists( 'help_attachment_download' ) ) {
	function help_attachment_download() {
		$att_Options = get_option ( ATTACHMENT_PREFIX );
		//echo "<pre>";
		//print_r($att_Options);
		//die();
		
		
	   $allowExtentions = explode(',',$att_Options['fileType']);
		set_time_limit(3000); 
		/* connect to gmail with your credentials */		
        if(check_user()){
			
		$hostname = trim($att_Options['hostName']);//'{imap.gmail.com:993/imap/ssl}INBOX';
		$username = trim($att_Options['userName']);//'vikas.bhardwaj.ladher@gmail.com'; 
		$password = trim($att_Options['userPassword']);

		/* try to connect */
		//$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
		 //$hostname = '{'.$hostname.'}INBOX';
		 if(isset($att_Options['selectFolder']) &&  $att_Options['selectFolder'] !=""){
			$hostname =  $att_Options['selectFolder'];
		 }else{
			$hostname = '{'.$hostname.'}INBOX';  
		 } 
		
		$inbox = imap_open($hostname,$username,$password);
		
		/*$list = imap_list($inbox, "{imap.example.org}", "*");
		if (is_array($list)) {
			foreach ($list as $val) {
				echo imap_utf7_decode($val) . "<br>";
			}
		} else {
			echo "imap_list failed: " . imap_last_error() . "\n";
		}
		
		die();
		*/
		if (!$inbox) {			
            print '<div class="attError error login-error">Cannot connect to server: '.imap_last_error().'. check server imap settings. <a href="'. admin_url ( "admin.php?page=att_google_instructions" ).'">"Click here"</a></div>';
		
        } else {
			/* get all new emails. If set to 'ALL' instead 
			 * of 'NEW' retrieves all the emails, but can be 
			 * resource intensive, so the following variable, 
			 * $max_emails, puts the limit on the number of emails downloaded.
			 * 
			 */
			

			/* grab emails */
			$fromEmail = $att_Options['fromEmail'];
			
			if($fromEmail !=""){
			
			
				if($fromEmail =='ALL'){
					$emails = imap_search($inbox,'ALL');
				}else{
					
				   $from = explode(',',$att_Options['fromEmail']);
				   
				   foreach($from as $search)
				   {
					 $emails1[] = imap_search($inbox, 'FROM "'.trim($search).'"');
					 
				   }
				   
				   //$emails = call_user_func_array('array_merge', $emails1);
				   
				   $list = array();

					foreach($emails1 as $arr) {
						if(is_array($arr)) {
							$list = array_merge($list, $arr);
						}
					}
                    $emails = $list;
					
				}
			
            //echo "<pre>";
			//print_r($emails1);
			
			/* useful only if the above search is set to 'ALL' */
			$max_emails = ($att_Options['maxEmails'] !="" ? $att_Options['maxEmails'] : '10');
            
			
            echo '<div class="cntShow">Count-'.count($emails).'</div>';
			
			/* if any emails found, iterate through each email */
			if(!empty($emails) && count($emails) > 0) {
				
				
    
             $count = 1;
    
			/* put the newest emails on top */
			rsort($emails);
			
			/* for every email... */
			foreach($emails as $email_number) 
			{
				//echo $email_number."<br>";

			/* get information specific to this email */
			$overview = imap_fetch_overview($inbox,$email_number,0);
			
			/* get mail message */
			$message = imap_fetchbody($inbox,$email_number,2);
			
			/* get mail structure */
			$structure = imap_fetchstructure($inbox, $email_number);

			$attachments = array();
			
			/* if any attachments found... */
			 if(isset($structure->parts) && count($structure->parts)) 
			  {
				 // echo "<pre>";
				  //print_r ($structure->parts);
				  
				for($i = 0; $i < count($structure->parts); $i++) 
				{
					$attachments[$i] = array(
						'is_attachment' => false,
						'filename' => '',
						'name' => '',
						'attachment' => '',
						'subtype' => ''
					);
				    
				 $attachments[$i]['subtype'] = $structure->parts[$i]->subtype;
				
					if($structure->parts[$i]->ifdparameters) 
					{
						foreach($structure->parts[$i]->dparameters as $object) 
						{
							if(strtolower($object->attribute) == 'filename') 
							{
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['filename'] = $object->value;
							}
						}
					}
				
					if($structure->parts[$i]->ifparameters) 
					{
						foreach($structure->parts[$i]->parameters as $object) 
						{
							if(strtolower($object->attribute) == 'name') 
							{
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['name'] = $object->value;
							}
						}
					}
				
					if($attachments[$i]['is_attachment']) 
					{
						$attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
						
						/* 4 = QUOTED-PRINTABLE encoding */
						if($structure->parts[$i]->encoding == 3) 
						{ 
							$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
						}
						/* 3 = BASE64 encoding */
						elseif($structure->parts[$i]->encoding == 4) 
						{ 
							$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
						}
					}
				}
			}
			
			/* iterate through each attachment and save it */
			//$totalDownlaod = 1;
			foreach($attachments as $attachment)
			{
				
				if($attachment['is_attachment'] == 1)
				{
					
					
					$filename = $attachment['name'];
					if(empty($filename)) $filename = $attachment['filename'];
					
					if(empty($filename)) $filename = time() . ".dat";
					
					/* prefix the email number to the filename in case two emails
					 * have the attachment with the same file name.
					 */
					$folder = WP_CONTENT_DIR ."/downloadAttachments";
					$folderZiped = WP_CONTENT_DIR ."/downloadAttachments/folderZiped";
					if(!is_dir($folder))
					{
						 mkdir($folder);
					}
					if(!is_dir($folderZiped))
					{
						 //mkdir($folderZiped);
					}
					
					//$fp = fopen("./" . $email_number . "-" . $filename, "w+");
					//$fp = fopen( $folder ."/". $email_number . "-" . $filename, "w+");
					
					 $info     = pathinfo($filename);
				     $ext      = $info['extension'];
				
					
		          
				   
				  //if (in_array($info['extension'], $allowExtentions)){
				  if(allowExtentions($ext)){
					  if(is_zip($ext))
					  {
						if ( file_exists($folder ."/".$filename) ) 
						{ 
						    
							$newNameZip = time().'-'.$info['filename'].'.'.$ext;
						}else{
							$newNameZip = $filename;
						}
						// echo 'newNameZip:- '.$newNameZip."<br>"; 
						  
						  
						  $fp = fopen( $folder ."/".$newNameZip, "w+");
					      fwrite($fp, $attachment['attachment']);
						  fclose($fp);
						  if($att_Options['unZip'] =='1')
							{
							  extractZipFile($newNameZip);
								
							}//unZip
						  
					  }
					  else
					  {		
                        if ( file_exists($folder ."/".$filename) ) 
						{ 
						
							$newName = time().'-'.$info['filename'].'.'.$ext;
						}else{
							$newName = $filename;
						} 				  
				   
					   $fp = fopen( $folder ."/".$newName, "w+");
					
					   fwrite($fp, $attachment['attachment']);
					
					    fclose($fp);
					  }
					
					
					
					
					//Delete Files from Server if Enable in admin
					//deleteEmailFrom_Server();
					//echo "emailNumbe-". $email_number."<br>";
					if( $att_Options['deleteEmails'] == "1"){
						  imap_delete($inbox, $email_number);		
					  }
					
                   					
				    if( $att_Options['moveEmails'] == "1"){
						
					  imap_mail_move($inbox,$email_number,$att_Options['selectFolderMove']);
					}
                  				 
					
					//Sendf files to  log file
					$att_log_files[] =  $filename;
					
					
				   }//AllowExtentions
				}
			
			   }//foreach attachments close
			   
			   
			
			   if($count++ >= $max_emails) break;
			   
		   }//Foreach emails
		   

		/* close the connection */
		imap_close($inbox);
       
			  //Call log files fun
			if(!empty($att_log_files))
			{
			  att_create_logs($att_log_files);
						
				echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
				<p><strong>Files download successfully!</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
			}
			else
			 {
				echo '<div id="setting-error-settings_updated" class="updated settings-error"> <p><strong>No record !</strong></p></div>';
				
			 }
			
			
			
		 }// if emails
		 else
		 {
				 
				 echo '<div id="setting-error-settings_updated" class="updated settings-error"> <p><strong>No any file download!</strong></p></div>';
	     }
			 
			
		}//$fromEmail if not empty
		else{
			 print '<div class="attError error login-error">Please enter comma separated email address in settings. <a href="'. admin_url ( "admin.php?page=att_attachments_settings" ).'">"Click here"</a></div>';
		}
							  
      }//Else close imap no error
		
		
	 }//check_user
	 else
	  {
		 echo '<div class="attError error">Please enter your credentials first! <a href="'. admin_url ( "admin.php?page=att_attachments_settings" ).'">"Click here"</a></div>';
	  }
	}//Function close
	
	
	
	
	
 }//function_exists

 
 function check_user(){
	 $att_Options = get_option ( ATTACHMENT_PREFIX );
	
		$hostname = $att_Options['hostName'];
		$username = $att_Options['userName'];
		$password = $att_Options['userPassword'];
		if($hostname !="" && $username !="" && $password !=""){
			return true;
		}else{
			return false;
		}
	 
 }
 
 //Check allowed extension
 function allowExtentions($ext){
	 
	  $att_Options = get_option ( ATTACHMENT_PREFIX );
	  $allowExt =  $att_Options['fileType'];
	  if( $allowExt =="" || $allowExt =="ALL"){
		  return true;
	  }else{
		  
		  $allowExtArr =  explode(",",$att_Options['fileType']);
		  
		  if (in_array($ext, $allowExtArr)){
			  return true;
		  }else{
			  return false;
		  }
	  }
	  
 }
 
 //Delete Email from Server

 function deleteEmailFrom_Server(){
	 
	  $att_Options = get_option ( ATTACHMENT_PREFIX );
	
	  if( $att_Options['deleteEmails'] == "1"){
		  $att_Options['fromEmail'] = 'ALL';
		  
		//update_option(ATTACHMENT_PREFIX, $att_Options);		
	  }
	  
 } //Close deleteEmailFrom_Server
 
 
 function is_zip($ext){
	 $mimeArchives = array('zip','rar','exe','msi','cab');
		
	 if(in_array($ext,$mimeArchives))				
	 {	
       return true;
	 }else{
		 return false;
	 }
 }
 
 
 // Extract zip file
 function extractZipFile($filename){
	 
	 $mimeArchives = array('zip','rar','exe','msi','cab');
		$info     = pathinfo($filename);
		$ext      = $info['extension'];
	 if(in_array($ext,$mimeArchives))				
	 {	 
		 $dirAttZip = WP_CONTENT_DIR."/downloadAttachments/".$filename;
		 $timeDir = time();
		 $dirAttZipEx = WP_CONTENT_DIR ."/downloadAttachments/".$timeDir;
		 	
         if(!is_dir($dirAttZipEx))
			{
				 mkdir($dirAttZipEx);
			}			
		 
			$zip = new ZipArchive;
			$res = $zip->open($dirAttZip);
			if ($res === TRUE) {
				
				for($i = 0; $i < $zip->numFiles; $i++) {
					$filename1 = $zip->getNameIndex($i);
					$fileinfo = pathinfo($filename1);
					//echo $newName = time().$fileinfo['filename'].'.'.$fileinfo['extension'];
					//echo "<br>";
					
				  }  
			  $zip->extractTo($dirAttZipEx);
			  $zip->close();
			  
			  $unlinkFile =  WP_CONTENT_DIR ."/downloadAttachments/".$filename;		  
			  @unlink($unlinkFile);
			
			} 
			
			
			
			
			
			
	 }
 }//function close 
 
 
 function getDirContentsCheck($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
			
        } else if($value != "." && $value != "..") {
            getDirContentsCheck($path, $results);
			if(!is_dir($path)) {
            $results[] = $path;			
			}
        }
    }
	return $results;
}
 
 
 //Get file name from downloadAttachments
function is_file_exist(){ 
 
	$dirAtt = WP_CONTENT_DIR ."/downloadAttachments/";
		// Open a directory, and read its contents
		if (is_dir($dirAtt)){
		  if ($dh = opendir($dirAtt)){
			  
			 $fileCounter = 1; 
			while (($file = readdir($dh)) !== false){				
				
				if($file == ".." || $file == ".") continue;			
			    //echo $fileCounter."-filename:" . $file . "<br>";	   
			  
			    $info     = pathinfo($file);
				$ext      = $info['extension'];
				

              $result[] = $file;
			  
			}
			closedir($dh);
		  }
		  return $result;
		}
 }//function 	close
 
 
 
 //Create log file 
 
 function att_create_logs($att_log_files=null){
	  $att_Options = get_option ( ATTACHMENT_PREFIX );
	 
	
	  if( $att_Options['allowlog'] == "1"){
		  $file = WP_EMAIL_ATT_PATH.'log.txt';
			// The new person to add to the file
			$content = date("d/M/Y h:i:sa")."\n\n";
			if(!empty($att_log_files)){
				
				update_option("att_temp", $att_log_files);	
				$att_temp = get_option ( 'att_temp' );
	
	            
				
				$content .= "Following files have been downloaded. \n\n";
				
				$count=1;
				foreach($att_temp as $temp){
				   $content .= "  ".$count.".   ".$temp."\n";
				  $count++; 
				}
				
				
				
			}
			$content .= "\n..........................................................................\n";
			
			// Write the contents to the file, 
			// using the FILE_APPEND flag to append the content to the end of the file
			// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
			file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
						
	  }
	  
 } //Close att_create_logs
 

//Get Current url 
 if ( ! function_exists( 'help_current_page_url' ) ) {
	function help_current_page_url() {
	  global $wp;
	  return add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) );
	}
}

function help_buy_page_url(){
	$new_page_title = 'Buy';
	$page_check = get_page_by_title($new_page_title);
	if(isset($page_check->ID)){
	echo $url = 	get_permalink( $page_check->ID );
	}else{
	 echo $url= site_url().'/';
	}
	
}




add_action('wp_ajax_nopriv_ajax_Att_clearLog', 'ajax_Att_clearLog' );
add_action( 'wp_ajax_ajax_Att_clearLog', 'ajax_Att_clearLog' );

if( !function_exists('ajax_Att_clearLog') ):
    function ajax_Att_clearLog(){       
        $file = WP_EMAIL_ATT_PATH.'log.txt';
		
		file_put_contents($file, " ");
		
		echo "1";
        die();
    }//Function close
         
        
endif;
?>