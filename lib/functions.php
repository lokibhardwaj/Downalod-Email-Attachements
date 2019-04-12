<?php



  function getForm_Csv() {

            return '<form enctype="multipart/form-data" method="POST">
            Please choose a file: <input name="uploaded" type="file" />
            <input type="submit" value="Upload" name="submit" />
            </form>';
			
			
   }
   
   
   
 
	
	
	
	function getDirContentsDownloaded($dir, &$results = array()){
		$files = scandir($dir);

		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if(!is_dir($path)) {
				$results[] = $path;
				
			} else if($value != "." && $value != "..") {
				getDirContentsDownloaded($path, $results);
				if(!is_dir($path)) {
				$results[] = $path;
				
				}
			}
		}
		return $results;
	}
	
	
	
	
	
	//Get all admin user ID's in the DB
function admin_user_ids(){
    //Grab wp DB
    global $wpdb;
    //Get all users in the DB
    $wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");

    //Blank array
    $adminArray = array();
    //Loop through all users
    foreach ( $wp_user_search as $userid ) {
        //Current user ID we are looping through
        $curID = $userid->ID;
        //Grab the user info of current ID
        $curuser = get_userdata($curID);
        //Current user level
        $user_level = $curuser->user_level;
        //Only look for admins
        if($user_level >= 8){//levels 8, 9 and 10 are admin
            //Push user ID into array
            $adminArray[] = $curID;
        }
    }
    return $adminArray;
}


function getCsvheaderInoption($header){
	
	
	
	$options = '<option value="">Select</option>';
	if($header !=""){
	foreach($header as $option){
		
	  $options .= '<option value="'.$option.'">'.$option.'</option>';	
		
	  }
	}
	return $options;
	
	
}


function array_combine2($arr1, $arr2) {
    $count = min(count($arr1), count($arr2));
    return array_combine(array_slice($arr1, 0, $count), array_slice($arr2, 0, $count));
}



/******** Run function by cron**/
//import_csv_by_cron();
function import_csv_by_cron(){ 
	$dirAtt = WP_CONTENT_DIR ."/downloadAttachments/";	
	if(is_dir($dirAtt)){
         $resultsData = getDirContentsDownloaded($dirAtt);
		 if(!empty($resultsData)){
			  foreach($resultsData as $dataFile){
				
				update_option( 'wp_import_csv', $dataFile, 'yes' );
				find_csv_file();
			  }
		 }
	}
	
}//Function close
 
 
 
function find_csv_file(){
	global $wpdb;
	
 $csv_file = get_option( 'wp_import_csv');
if($csv_file !=""){
	 
	 if (($handle = fopen($csv_file, "r")) !== FALSE) {
		        $all_rows = array();
				$header = null;
				while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {	
				  if ($header === null) {
						$header = $row;
						continue;
					}
					
					
					
				}//While
				
			
			fclose($handle);
			
			
			//print_r($header);
			for($i = 0 ; $i < 5; $i++ ){
				$reportName[] =  $header[$i]; echo "<br>";
			}
			
			
			 $reportNameStr = implode(',',$reportName);
			 $reportNameStr = strtolower($reportNameStr);
			
			
			$find_outbound   = 'outbound';
			$pos = strpos($reportNameStr, $find_outbound);
			
			if ($pos === false) {				
			}else{
				//echo "oubound_Deliveries Call<br>";
				oubound_Deliveries();
			}
			
			//Sales
			$find_sale   = 'sales backlog';
			$pos_sale = strpos($reportNameStr, $find_sale);
			
			if ($pos_sale === false) {				
			}else{
				//echo "sales backlog Call<br>";
				sales_Backlog();
			}
			
			
			
	}//If handel
	
  }	//if not empty file
  else{
	  
	$error = "No file found!";  
  }
  
  echo $error;
	
} 
 






function readCSV($csv_file,$skipLineNumber){
	
	if( $csv_file !=""){
	 $header = '';
	 $rows = array_map('str_getcsv', file($csv_file));
	 $header = array_shift($rows);
	 $csv = array();
	 
	 $counter = 0;

		foreach ($rows as $row) {
		  if($counter > $skipLineNumber){
			  
		    $csv[] =  $row;
		  }
		  
		  $counter++  ;
		}
		
		return $csv;
	 
	}
    
}
 
 
 /*************** Sale Blacklog CSV IMPORT********/
 function sales_Backlog($file="")
 {
	global $wpdb;
	
	echo "In sales<br>";
	
	
	
	
	 $csv_file = get_option( 'wp_import_csv');
	
 if($csv_file !=""){
	
    $table_name = $wpdb->prefix . 'sales_backlog';
	
	
    //Update All open_quantity set 0 for all records	
		
	$results_backlog = $wpdb->get_results( "SELECT id FROM $table_name", OBJECT );
	//echo "<pre>";
	//print_r($results_backlog);
	
	for($kk = 0 ; $kk< count($results_backlog); $kk++){
		
	  $db_openQty['open_quantity'] = 0;
	  $ids = $results_backlog[$kk]->id;
	  
	  $wpdb->update($table_name, $db_openQty, array('id'=>$ids));
	}
	
	
     
	 //skip line
	 $impCsv_Opt = get_option ( WPCSV_PREFIX );
	 
	 if($impCsv_Opt['salesBacklog'] == "" || $impCsv_Opt['salesBacklog'] < 0){
		 $skipLine = 12;
	 }else{
		 $skipLine = $impCsv_Opt['salesBacklog'];
	 }
   	
	$csv = readCSV($csv_file, $skipLineNumber = $skipLine);
     //echo '<pre>';   print_r($csv);
	 
	$count_csv_colm = $csv; 
	 for($i = 0 ; $i<count($csv); $i++){
	 
	 if( count($count_csv_colm[0])== 15 || count($count_csv_colm[0])==14 ){
		 
		 if(count($count_csv_colm[0])== 15){
		
		$db['product'] = $csv[$i][0];
		$db['account'] = $csv[$i][1]; 
		$db['company'] = $csv[$i][2];
		$db['external_reference'] = $csv[$i][3];
		$db['sales_order'] = $csv[$i][4];
		$db['sales_order_item'] = $csv[$i][5];
		$db['cancellation_status'] = $csv[$i][6];
		$db['requested_date'] = $csv[$i][7];
		$db['created_on'] = $csv[$i][8];
		$db['customer_requested_date'] = $csv[$i][9];
		$db['revised_ship_date'] = $csv[$i][10];
		
		$each_cost  = str_replace('USD/ea',"",$csv[$i][11]);
		$db['each_cost'] = trim($each_cost);
		
		$requested_quantity  = str_replace('ea',"",$csv[$i][12]);
		$db['requested_quantity'] = trim($requested_quantity);
		
		$open_quantity  = str_replace('ea',"",$csv[$i][13]);
		$db['open_quantity'] = trim($open_quantity);
		
		$total_open_usd  = str_replace('USD',"",$csv[$i][14]);
		$db['total_open_usd'] = trim($total_open_usd);
		
		 $db['salesID_itemID'] = $csv[$i][4].'-'.$csv[$i][5];
		}else{
			
			$db['product'] = $csv[$i][0];
		$db['account'] = $csv[$i][1]; 
		$db['company'] = $csv[$i][2];
		$db['external_reference'] = $csv[$i][3];
		$db['sales_order'] = $csv[$i][4];
		$db['sales_order_item'] = $csv[$i][5];
		$db['cancellation_status'] = "";
		$db['requested_date'] = $csv[$i][6];
		$db['created_on'] = $csv[$i][7];
		$db['customer_requested_date'] = $csv[$i][8];
		$db['revised_ship_date'] = $csv[$i][9];
		
		$each_cost  = str_replace('USD/ea',"",$csv[$i][10]);
		$db['each_cost'] = trim($each_cost);
		
		$requested_quantity  = str_replace('ea',"",$csv[$i][11]);
		$db['requested_quantity'] = trim($requested_quantity);
		
		$open_quantity  = str_replace('ea',"",$csv[$i][12]);
		$db['open_quantity'] = trim($open_quantity);
		
		$total_open_usd  = str_replace('USD',"",$csv[$i][13]);
		$db['total_open_usd'] = trim($total_open_usd);
		
		 $db['salesID_itemID'] = $csv[$i][4].'-'.$csv[$i][5];
		}
		  
	 date_default_timezone_set(get_option('timezone_string')); // CDT
				
				//InsertInto DB
				$salesID_itemID = $db['salesID_itemID'];
				$results = $wpdb->get_results( "SELECT id FROM $table_name WHERE salesID_itemID = '$salesID_itemID'", OBJECT );

                if($results[0]->id ==""){ //Insert
					//echo "Insert<br>";
					$db['createded_on']= date('m-d-Y H:i:s');
					$db['updated_on']= date('m-d-Y H:i:s');
				  $insertID = $wpdb->insert( $table_name, $db );
					
				}else{ //Update
					//echo "Update<br>";
					$db['updated_on']= date('m-d-Y H:i:s');
				   $id = $results[0]->id; //added stripslashes_deep
				  $wpdb->update($table_name, $db, array('id'=>$id));
					
				}
			//print_r($db);	
			
	  }//ccount col 13 14
				
	 }//for	
					
		 echo '<h2 class="success">Data imported successfully!</h2>';
		 
		 //Copy another dir after impoert Data	and del
		 
		 del_copy_after_import($csv_file);
		 update_option( 'wp_import_csv', "", 'yes' );
		 
		 create_logs_imprtCsv($csv_file); //update  log file
	  
		
		
	}else{
		echo '<h3 class="error SalesBacklog">No file to import!</h3>';	
	}	
	
	 
 }// function close sales_Backlog
 
 
 
 
 
 
  /*************** oubound_deliveries CSV IMPORT********/
 function oubound_Deliveries($file="")
 {
	global $wpdb;
	
	echo "oubound_Deliveries<br>";
	
    $csv_file = get_option( 'wp_import_csv'); 
	
   if($csv_file !=""){
	
   $table_name = $wpdb->prefix . 'oubound_deliveries';
   
   //skip line
	 $impCsv_Opt = get_option ( WPCSV_PREFIX );
	 
	 if($impCsv_Opt['ouboundDeliveries'] == "" || $impCsv_Opt['ouboundDeliveries'] < 0){
		 $skipLine = 8;
	 }else{
		 $skipLine = $impCsv_Opt['ouboundDeliveries'];
	 }
   
   
    $csv = readCSV($csv_file,$skipLineNumber = $skipLine);
     //echo '<pre>';   print_r($csv);
	 
	$csv_count_Colmn = $csv;
	 for($i = 0 ; $i<count($csv); $i++){
		 if(  count($csv_count_Colmn[0])== 12 || count($csv_count_Colmn[0])==11 ){
			 
			if(  count($csv_count_Colmn[0])== 12){ 
			$db['shipment_date'] = $csv[$i][0];
			$db['product'] = $csv[$i][1];
			$db['country_of_origin_product'] = $csv[$i][2];
			$db['external_reference_sales_order_item_id'] =$csv[$i][3];
			$db['sales_order_id']= $csv[$i][4];
			$db['sales_order_item_id'] = $csv[$i][5];
			$db['item_cancellation_status'] = $csv[$i][6];
			$db['pack_list_id']= $csv[$i][7];
			$db['account_sales_order_item_id'] = $csv[$i][8];
			$db['freight_forwarder_delivery_id'] =$csv[$i][9];
			$db['tracking_id_delivery_id'] = $csv[$i][10];
			$del_Quan = str_replace("ea","",$csv[$i][11]);
			$db['delivered_quantity'] = $del_Quan;
			
			}else{
			$db['shipment_date'] = $csv[$i][0];
			$db['product'] = $csv[$i][1];
			$db['country_of_origin_product'] = $csv[$i][2];
			$db['external_reference_sales_order_item_id'] =$csv[$i][3];
			$db['sales_order_id']= $csv[$i][4];
			$db['sales_order_item_id'] = $csv[$i][5];
			$db['item_cancellation_status'] = "";
			$db['pack_list_id']= $csv[$i][6];
			$db['account_sales_order_item_id'] = $csv[$i][7];
			$db['freight_forwarder_delivery_id'] =$csv[$i][8];
			$db['tracking_id_delivery_id'] = $csv[$i][9];
			$del_Quan = str_replace("ea","",$csv[$i][10]);
			$db['delivered_quantity'] = $del_Quan;
			}
			 
			 $db['salesID_itemID']  = $csv[$i][4].'-'.$csv[$i][5];
			
		 date_default_timezone_set(get_option('timezone_string')); // CDT
		  //print_r($db);echo "<br>";
		 
		
		//InsertInto DB
		$salesID_itemID = $db['salesID_itemID'];
		$results = $wpdb->get_results( "SELECT id FROM $table_name WHERE salesID_itemID = '$salesID_itemID'", OBJECT );

		if($results[0]->id ==""){ //Insert
			//echo "Insert<br>";
			$db['createded_on']= date('m-d-Y H:i:s');
			$db['updated_on']= date('m-d-Y H:i:s');
			
			$insertID = $wpdb->insert( $table_name, $db );
			
		}else{ //Update
			//echo "Update<br>";
		   $db['updated_on']= date('m-d-Y H:i:s');
		   
		   $id = $results[0]->id; //added stripslashes_deep
		   $wpdb->update($table_name, $db, array('id'=>$id));
			
		   }
	   }//If 12	 or 11 
		 
	 }//for
	 		
     echo '<h2 class="success">Data imported successfully!</h2>';
	 
	 //Copy another dir after impoert Data	and del
		 
		del_copy_after_import($csv_file);
		 update_option( 'wp_import_csv', "", 'yes' );
		 
	     create_logs_imprtCsv($csv_file) ;//update  log file
	 
	 
   }
   else{
		echo '<h3 class="error outBond">No file to import!</h3>';	
	}
	
	 
 }// function close oubound_deliveries



 
 
 function del_copy_after_import($csv_file){
	 $folder = WP_CONTENT_DIR ."/importedFiles/";
		
		if(!is_dir($folder))
		{
			 mkdir($folder);
		}
		$fileName = basename($csv_file);
		$importedFile = $folder.$fileName;
		copy($csv_file, $importedFile);
		$csv_file = str_replace('\\\\', '\\', $csv_file);
		//$csv_file = str_replace('\\', '/', $csv_file);
		if (file_exists($csv_file)) {
			//echo $csv_file; echo "<br>";
		chmod($csv_file, 0777);
			@unlink($csv_file);
			//echo 'Deleted file!';
		}
		else {
			echo 'File does not exist';
		}
		if (@unlink($csv_file)) {
		  @rmdir(dirname($csv_file));
		 }
 }//del_copy_after_impoert
 
 
 
  
 //Create log file 
 
 function create_logs_imprtCsv($csv_log_file=null){
	  $att_Options = get_option ( ATTACHMENT_PREFIX );
	 
	
	  if( $att_Options['allowlog'] == "1"){
		  
		  $file = WP_EMAIL_ATT_PATH.'log.txt';
			// The new person to add to the file
			$content = date("d/M/Y h:i:sa")."\n\n";
			if(!empty($csv_log_file)){
				
				update_option("csv_import_temp", $csv_log_file);	
				$csv_temp = get_option ( 'csv_import_temp' );
					
				$content .= "Following file have been imported. \n\n";
				$content .= "".$csv_temp."\n";				
			}
			$content .= "\n..........................................................................\n";
			
			// using the FILE_APPEND flag to append the content to the end of the file
			// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
			file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
						
	  }
	  
 } //Close att_create_logs
 
 
 
 
 
 
 
 
 
 
 
 
 
 /***************Old Sale Blacklog CSV IMPORT********/
 function sales_Backlog_old($file="")
 {
	global $wpdb;
	
	echo "In sales<br>";
	
	
	 $csv_file = get_option( 'wp_import_csv');
	
	 
	 
	  
	
	 if($csv_file !=""){
		 
	//$csv = readCSV($csv_file);
	
	//echo '<pre>';
	//print_r($csv);
	
	$table_name = $wpdb->prefix . 'sales_backlog';
		
	


		

	
					
		if (($handle = fopen($csv_file, "r")) !== FALSE) {	
			   $all_rows = array();
			   $db = array();
				$header = null;
				$totalRecord = 0;
				//while ($row = fgetcsv($handle)) {
					$flag = true;
				while (($row = fgetcsv($handle, 3200, "\t")) !== FALSE) {	
					/*
					if ($header === null) {
						$header = $row;
						continue;
					}
					//$all_rows[] = array_combine($header, $row);
					//$all_rows[] =array_combine2($header, $row);
					*/
					
					//if($flag) { $flag = false; continue; }
					if($totalRecord >13){
					$num = count($row);
					for ($c=0; $c < $num; $c++) {
						if (array(null) !== $row) {
					     $col[$c] = $row[$c];
						}
					}
					
					
					
					
				
				
			     $db['product'] = $col[0];
				 $db['account'] = $col[1]; 
				$db['company'] = $col[2];
				$db['external_reference'] = $col[3];
				$db['sales_order'] = $col[4];
				$db['sales_order_item'] = $col[5];
				$db['requested_date'] = $col[6];
				$db['created_on'] = $col[7];
				$db['customer_requested_date'] = $col[8];
				$db['revised_ship_date'] = $col[9];
				$db['each_cost'] = $col[10];
				$db['requested_quantity'] = $col[11];
				$db['open_quantity'] = $col[12];
				$db['total_open_usd'] = $col[13];
				
				 $db['salesID_itemID'] = $col[4].'-'.$col[5];
				
				//InsertInto DB
				$salesID_itemID = $db['salesID_itemID'];
				$results = $wpdb->get_results( "SELECT id FROM $table_name WHERE salesID_itemID = '$salesID_itemID'", OBJECT );

                if($results[0]->id ==""){ //Insert
					//echo "Insert<br>";
					//$insertID = $wpdb->insert( $table_name, $db );
					
				}else{ //Update
					//echo "Update<br>";
				   $id = $results[0]->id; //added stripslashes_deep
				   //$wpdb->update($table_name, $db, array('id'=>$id));
					
				}
				
			
					}
					
				$totalRecord++;	
				
				}//While
				
				
				//echo "<pre>";
				//print_r($col);
				
			fclose($handle);
			
			 echo '<h2 class="success">Data imported successfully!</h2>';
			 
			 //Copy another dir after impoert Data	and del
			 
			// del_copy_after_import($csv_file);
			 //update_option( 'wp_import_csv', "", 'yes' );
			
		    }//If handel
			
			
		}else{
		    echo '<h3 class="error">No file to import!</h3>';	
		}	
	
	 
 }// function close sales_Backlog
 
 
 
 /**** Add body class user role if user logged in */  
 add_filter( 'body_class', 'vcc_body_class' );
function vcc_body_class( $classes ) {
    $classes[] = vcc_get_user_role();
	
    return $classes;	
}
function vcc_get_user_role() {
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    return $user_role;
}
 
 
 
?>