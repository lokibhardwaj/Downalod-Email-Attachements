<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	
	<h2> <?php _e('Download email attachments', 'email-attachment'); ?></h2>
	<div class="updated below-h2" id="wppdf_message"></div>
	
	
	<div id="gde-tabcontent">
		<?php
		if(isset($_POST) && $_POST['dn-submit']){
			//print_r($_POST);
			
			//call download-frm
			 help_attachment_download();
		}
		
		?><form method="post" name="download-frm" id="download-frm" action="">
		   
			<div id="ptpdf-options" class="ptpdf-option wrap">
			
			<div class="innerWrap">
			
			</div>
			
			<div class="submit">
				<input type="submit" class="button-primary" name="dn-submit" value="<?php _e( 'Download Attachments', 'email-attachment') ?>" />
			</div>
			
		   </div><!--wrap-->		   
		</form>

	</div>
</div>