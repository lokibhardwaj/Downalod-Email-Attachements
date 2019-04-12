<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}




$dirAtt = WP_CONTENT_DIR ."/downloadAttachments/";




function getDirContents($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
			
        } else if($value != "." && $value != "..") {
            getDirContents($path, $results);
			if(!is_dir($path)) {
            $results[] = $path;
			
			}
        }
    }
	return $results;
}
$dirAtt = WP_CONTENT_DIR ."/downloadAttachments/";
$resultsData = getDirContents($dirAtt);

   //echo "..............";
	//echo "<pre>";
    //print_r($resultsData);
	
	
		$fileCountNumber = 1;
		foreach($resultsData as $dataFile){
			
			$dataFile1 = explode('wp-content',$dataFile);
			//print_r($dataFile1);
			
			$fileName = basename($dataFile);
			$info     = pathinfo($fileName);
			//echo "<pre>";
			//print_r($info);
			$ext      = $info['extension'];
			
			$attDataFile1[] = array(
			   'ID' => $fileCountNumber,
			   'thumb' => str_replace('\\','/',$dataFile1[1]),
			   'title' => $fileName,
			  ) ;
			   
		    $fileCountNumber++;	   
		}
 
    //echo "<pre>";
	//print_r($attDataFile);
		  
  
  
   


class ATTACMENTS_List_Table extends WP_List_Table {
    
    /** ************************************************************************
     * Normally we would be querying data from a database and manipulating that
     * for use in your list table. For this example, we're going to simplify it
     * slightly and create a pre-built array. Think of this as the data that might
     * be returned by $wpdb->query()
     * 
     * In a real-world scenario, you would make your own custom query inside
     * this class' prepare_items() method.
     * 
     * @var array 
     **************************************************************************/
    
	 

    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'file',     //singular name of the listed records
            'plural'    => 'files',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'thumb':
            case 'title':
            case 'location':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }


    /** ************************************************************************
     **************************************************************************/
    function column_title1($item){
		
		
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&file=%s">Edit</a>',$_REQUEST['page'],'edit',$item['title']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&file=%s">Delete</a>',$_REQUEST['page'],'delete',$item['title']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver"></span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
		
    }


   
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("file")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }


    /** ************************************************************************
     
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
          
            'title'     => 'Files name',
            'location'     => 'Files Location',
            
        );
        return $columns;
    }


    
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false)     //true means it's already sorted
            
        );
        return $sortable_columns;
    }


    /** ************************************************************************
     
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }


    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
        
        
		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );
			
           
			
				self::delete_Files( $_GET['file'] );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		             //wp_redirect( esc_url_raw(add_query_arg()) );
					 
					// admin_url ( "admin.php?page=att_attachments_settings" )
					wp_safe_redirect( add_query_arg( array( 'page' => 'att_attachments_list' ), admin_url( 'admin.php' ) ) );
				
			

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'delete' )) {
        
			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_Files( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_safe_redirect( add_query_arg( array( 'page' => 'att_attachments_list' ), admin_url( 'admin.php' ) ) );
			exit;
		}
		
		
        
    }//function close
	
	
	/******** Delet firls from folder*/
	public static function delete_Files( $ids ) {
		global $wpdb;

		
		$dir =  WP_CONTENT_DIR ."/downloadAttachments/";
		
		foreach($ids as $id){
			//echo $filename."<br>";
		$getOption = $id.'_att_del';
		 $actualFile =  get_option( $getOption,true ); echo "<br>";
		 //$path = $dir.$filename;
		 //@unlink($actualFile);
		 
		 if (@unlink($actualFile)) {
				@rmdir(dirname($actualFile));
			}
		 
		}
	}
	
	
	
//***************************************************
// Function getDirContents
// Read All files from folder
//***************************************	
	
	function getDirContents($dir, &$results = array()){
		$files = scandir($dir);

		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if(!is_dir($path)) {
				$results[] = $path;
				
			} else if($value != "." && $value != "..") {
				getDirContents($path, $results);
				if(!is_dir($path)) {
				$results[] = $path;
				
				}
			}
		}
		return $results;
	}



    /** ************************************************************************
    
     **************************************************************************/
	 
	 
    function prepare_items() {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 20;        
        
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();       
        
        
        $this->_column_headers = array($columns, $hidden, $sortable);       
        
        
        $this->process_bulk_action();
		
		/****** Read Data from folder ****************************/
	   $mimeText = array( 
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',
		);

        // images
		 $mimeImages = array(
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml'
	    );

        // archives
		$mimeArchives = array(
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed'
		);

        // audio/video
		$mimeAudio = array(
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime'
		);

        // adobe
		$mimeAdobe = array(
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript'
		);

        // ms office
		
		$mimeMsOffice = array(
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint'
		);


        // open office
		$mimeOpOffice = array(
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet'
       );
	
        
        
		
		
				
        //$data = $attData;  //Old data
		
		$dirAtt = WP_CONTENT_DIR ."/downloadAttachments/";		
		$dirAtt_URL = WP_CONTENT_URL ."/downloadAttachments/";
		$plgImgUrl = WP_EMAIL_ATT_URL ."/images/";
		$wp_content = WP_CONTENT_URL ;
		
		
        $resultsData = getDirContents($dirAtt);
		$fileCountNumber = 1;
		foreach($resultsData as $dataFile){
			
			$dataFile1 = explode('wp-content',$dataFile);
			$dataFile1Forwrd = str_replace('\\','/',$dataFile1[1]);
			
			$fileName = basename($dataFile);
			$info     = pathinfo($fileName);
			$ext      = $info['extension'];
		   if(array_key_exists($ext,$mimeImages))				
			{
				
				$fileThumb  ='<img src="'.WP_CONTENT_URL.$dataFile1Forwrd.'" width="50px" height="50px;"/>';
			}
			else{
				$fileNameDemo = 'demofile.png';				
				$fileThumb  ='<img src="'.$plgImgUrl.$fileNameDemo.'" width="50px" height="50px;"/>';
			}
			
			
			$attDataFile[] = array(
			   'ID' => $fileCountNumber,
			   'thumb' => $fileThumb,
			   'title' => $fileName,
			   'location' => $dataFile
			  ) ;
			  
			  //Save As option for delete
			  $optionDel = $fileCountNumber."_att_del";
			  update_option( $optionDel, $dataFile, $autoload="yes" );
			   
		    $fileCountNumber++;	   
		}
		
		
		 $data = $attDataFile;  //ListData
		
		
                
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}

    
    //Create an instance of our package class...
    $attListTable = new ATTACMENTS_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $attListTable->prepare_items();
    
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2>All attachments</h2>
        
        
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $attListTable->display() ?>
        </form>
        
    </div>
    