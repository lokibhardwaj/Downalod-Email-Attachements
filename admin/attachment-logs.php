<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	
	<h2> <?php _e('Log details', 'email-attachment'); ?></h2>
	<div class="updated below-h2" id="wppdf_message"></div>
	
	<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible" style="display:none;"> 
			
			</div>
	
	 <div id="logFileContent">
	 
		<?php 
		
			 $file = WP_EMAIL_ATT_PATH.'log.txt';
			 
			 $fh = fopen($file, 'r');

			$pageText = fread($fh, 25000);

			echo nl2br($pageText);
			 
			 
			
			//echo file_get_contents($file);
		?>
		 
	 </div>
	      <div class="submit">
				<input type="submit" id="clearLog" class="button-primary" name="log-submit" value="<?php _e( 'Clear log', 'email-attachment') ?>" />
			</div>
</div><!--wrap-->	
