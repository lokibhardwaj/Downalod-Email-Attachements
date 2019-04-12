<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	
	<h2> <?php _e('Email Attachment Settings', 'email-attachment'); ?></h2>
	<div class="updated below-h2" id="wppdf_message"></div>
	
	
	<div id="gde-tabcontent">
		<?php
		
		?><form method="post" id="att_setting_frm" name="att_setting_frm" action="options.php">
			<div id="gencontent" class="gde-tab gde-tab-active">
			
			
			   <div class="wrap">
				<div id="ptpdf-options" class="ptpdf-option wrap">
					
				<?php
				settings_fields ( ATTACHMENT_PREFIX .'_options' );
				$att_Options = get_option ( ATTACHMENT_PREFIX );
				//echo "<pre>";print_r($ptpdfoptions);
				?>
				
	<!-- display general options -->
			
			<!-- section-1 Mail server Credentials -->
			
			<h3 class="1sth3"><?php _e('Mail server', 'wp-advanced-pdf')?></h3>
			<div class="section_body 1stSection">
				<table>
					
					
					<tr>
						<td class="tr1"><?php _e('Host name', 'email-attachment');?></td>
						<td class="tr2">
						<input type="text" name="<?=ATTACHMENT_PREFIX?>[hostName]"
							id="<?=ATTACHMENT_PREFIX?>[hostName]" class="hostName"
							value="<?= ( isset( $att_Options['hostName'] ) ? $att_Options['hostName'] : ''); ?>" />
							<div class="descr"><?php _e('Enter your host name', 'email-attachment');?>
							<p>Example:</p>
							<p>Gmail: imap.gmail.com:993/imap/ssl</p>
							<p>Yahoo: imap.mail.yahoo.com:993/imap/ssl</p>
							<p>Other: server name:993/imap/ssl</p>
							

							</div>
						</td>
					</tr>
					
					<tr>
						<td class="tr1"><?php _e('Email address', 'email-attachment');?></td>
						<td class="tr2"><input type="text" name="<?=ATTACHMENT_PREFIX?>[userName]" id="<?=ATTACHMENT_PREFIX?>[userName]" class="userName"
							value="<?= ( isset($att_Options['userName'] ) ? $att_Options['userName'] : ''); ?>" />
							<div class="descr"><?php _e('Enter email (ex: xxx@gmail.com).', 'email-attachment');?></div>
						</td>
					</tr>
					
					
					<tr>
						<td class="tr1"><?php _e('Password', 'email-attachment');?></td>
						<td class="tr2"><input type="password" name="<?=ATTACHMENT_PREFIX?>[userPassword]" id="<?=ATTACHMENT_PREFIX?>[userPassword]" class="userPassword"
							value="<?= ( isset($att_Options['userPassword'] ) ? $att_Options['userPassword'] : ''); ?>" />
							<div class="descr"><?php _e('Enter password.', 'email-attachment');?></div>
						</td>
					</tr>
					
					
					
					<tr>
						<td class="tr1"><?php _e('Download from folder', 'email-attachment');?></td>
						<td class="tr2">
						
						<?php $slectFolder = get_option ('email_selectFolder');
						
						
						?>
						
						
						<?php $sel =  ( isset($att_Options['selectFolder'] ) ? $att_Options['selectFolder'] : ''); ?>
						
					      <select id="selectFolder" name="<?=ATTACHMENT_PREFIX?>[selectFolder]">
						   <?php
                            if(!empty($slectFolder)){
								foreach($slectFolder as $key=>$value){
									
								  $sel =  ( $att_Options['selectFolder'] == $key ? 'selected' : '');
									
									echo '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
								}
							}else{
								echo '<option value="0">Select</option>';
							}						   
						   ?>
						  
						  </select>
						  <input type="button" id="importFolder" value="Import folder"/>
						  <div id="loaderBtn" style="display:none"><img src="<?php echo WP_EMAIL_ATT_URL;?>images/page-loader.gif" width="50px;" /></div>
						  
						  
						  <div id="email_result"></div>
						</td>
					</tr>
					
					
					
					
					<tr>
						<td class="tr1"><?php _e('Move emails after download', 'email-attachment');?></td>
						<td class="tr2">
						
						<input name="<?=ATTACHMENT_PREFIX?>[moveEmails]" value="1"
						<?= ( isset( $att_Options['moveEmails'] ) ) ? 'checked="checked"' : ''; ?>
						type="checkbox" /> <span><?php _e('If checked, then move the emails on the server into selected folder after successful download.','email-attachment')?></span>
						</td>
					</tr>
					
					
					
					
					<tr>
						<td class="tr1"><?php _e('Move emails into folder after download', 'email-attachment');?></td>
						<td class="tr2">
						
						<?php $slectFolder = get_option ('email_selectFolder');
						
						?>
						
						
						<?php $sel =  ( isset($att_Options['selectFolder'] ) ? $att_Options['selectFolder'] : ''); ?>
						
					      <select id="selectFolderMove" name="<?=ATTACHMENT_PREFIX?>[selectFolderMove]">
						   <?php
                            if(!empty($slectFolder)){
								foreach($slectFolder as $key=>$value){
									
								  $value1  = explode('}',$value);
								  $value1 = str_replace('[Gmail]/',"",$value1);
								  
								  $sel =  ( $att_Options['selectFolderMove'] == $value1[1] ? 'selected' : '');
								  
								echo '<option value="'.$value1[1].'" '.$sel.' >'.$value1[1].'</option>';
								}
							}else{
								echo '<option value="0">Select</option>';
							}					 	   
						   ?>
						  
						  </select>
						  
						  <div id="email_result"></div>
						</td>
					</tr>
					
                    
				</table>
				
				 
			</div><!--section_body-->
			
			
			
			<!-- section-2 Attachment Settings -->
			
			<h3><?php _e('Attachment settings', 'wp-advanced-pdf')?></h3>
			<div class="section_body">
				<table>
					
					
					<tr>
						<td class="tr1"><?php _e('Max emails', 'email-attachment');?></td>
						<td class="tr2">
						<input type="text" name="<?=ATTACHMENT_PREFIX?>[maxEmails]"
							id="<?=ATTACHMENT_PREFIX?>[maxEmails]"
							class="onlyNumber" value="<?= ( isset( $att_Options['maxEmails'] ) ? $att_Options['maxEmails'] : '50'); ?>" />
							<div class="descr"><?php _e('Enter max emails (default is 50', 'wp-advanced-pdf');?>).</div>
						</td>
					</tr>
					
					<tr>
						<td class="tr1"><?php _e('Sender email Address', 'email-attachment');?></td>
						<td class="tr2">
						
							
						<textarea name="<?=ATTACHMENT_PREFIX?>[fromEmail]"
							id="<?=ATTACHMENT_PREFIX?>[fromEmail]"><?= ( isset( $att_Options['fromEmail'] ) ? $att_Options['fromEmail'] : ''); ?></textarea>	
							
							
							<div class="descr"><?php _e('Enter email address comma separated', 'wp-advanced-pdf');?>
							<p>Ex: example@gmail.com,test@gmail.com</p>
							</div>
						</td>
					</tr>
					
					
					<tr>
						<td class="tr1"><?php _e('Delete emails after download', 'email-attachment');?></td>
						<td class="tr2">
						
						<input name="<?=ATTACHMENT_PREFIX?>[deleteEmails]" value="1"
						<?= ( isset( $att_Options['deleteEmails'] ) ) ? 'checked="checked"' : ''; ?>
						type="checkbox" /> <span><?php _e('If checked, then delete the emails on the server after successful download.','email-attachment')?></span>
						</td>
					</tr>
					
					
					
					<tr>
						<td class="tr1"><?php _e('Allow file types', 'email-attachment');?></td>
						<td class="tr2">
						<input type="text" name="<?=ATTACHMENT_PREFIX?>[fileType]"
							id="<?=ATTACHMENT_PREFIX?>[fileType]"
							value="<?= ( isset( $att_Options['fileType'] ) ? $att_Options['fileType'] : 'ALL'); ?>" />
							<div class="descr"><?php _e('Enter fileType comma separated (default is ALL', 'email-attachment');?>).
							<p class="allowFileType">Ex : txt,pdf,png</p>
							</div>
						</td>
					</tr>
					
					<tr>
						<td class="tr1"><?php _e('Unzip', 'email-attachment');?></td>
						<td class="tr2">
						
						<input name="<?=ATTACHMENT_PREFIX?>[unZip]" value="1"
						<?= ( isset( $att_Options['unZip'] ) ) ? 'checked="checked"' : ''; ?>
						type="checkbox" /> <span><?php _e('Allow unzip', 'email-attachment')?></span>
						</td>
					</tr>
					
					
					
					

				</table>
			</div><!--section_body-->
			
			
			
			<!-- section-3 Interval Cron Settings -->
			
			<h3><?php _e('Interval schedule', 'wp-advanced-pdf')?></h3>
			<div class="section_body">
				<table>
				
				   <tr>
						<td class="tr1"><?php _e('Enable Cronjob', 'email-attachment');?></td>
						<td class="tr2">
						
						<input name="<?=ATTACHMENT_PREFIX?>[allowCronjob]" value="1"
						<?= ( isset( $att_Options['allowCronjob'] ) ) ? 'checked="checked"' : ''; ?>
						type="checkbox" /> <span><?php _e('Allow cronjob interval', 'email-attachment')?></span>
						</td>
					</tr>
					
					
					
					
					<tr>
						<td class="tr1"><?php _e('Schedule', 'email-attachment');?></td>
						<td class="tr2">
						
						<?php 
						 $cronArray = array(
						  "every_one_minutes" => "Every 1 minutes",
						  "every_five_minutes" => "Every 5 minutes",
						  "every_30_minutes" => "Every 30 minutes",
						  "hourly"  =>  "Hourly",						  
						  "after_6_hours"  =>  "After 6 hours",						  
						  "twicedaily"  =>  "Twice daily",
						  "daily"  =>  "Daily",
						  "weekly"  =>  "weekly",
						  "monthly"  =>  "Monthly",
						  );
						?>
						
						<select name="<?=ATTACHMENT_PREFIX?>[cronjobTime]" id="cronJobYime" >
						
						<?php 
						 foreach($cronArray as $key => $value){
							 
							$sel = ( $att_Options['cronjobTime']== $key ) ? 'selected="selected"' : '';
							
							 echo '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
						 }
						?>
						</td>
					</tr>
					
					
					
					<tr>
						<td class="tr1"><?php _e('Enable log', 'email-attachment');?></td>
						<td class="tr2">
						
						<input name="<?=ATTACHMENT_PREFIX?>[allowlog]" value="1"
						<?= ( isset( $att_Options['allowlog'] ) ) ? 'checked="checked"' : ''; ?>
						type="checkbox" /> <span><?php _e('Allow log details', 'email-attachment')?></span>
						</td>
					</tr>
					
					
			   </table>
			</div><!--section_body-->
			
			<div class="submit">
				<input type="submit" class="button-primary" name="<?=ATTACHMENT_PREFIX?>[submit]" value="<?php _e( 'Save Changes', 'email-attachment') ?>" id="att_setting_submit" />
				
				<div id="loader" style="display:none"><img src="<?php echo WP_EMAIL_ATT_URL;?>images/page-loader.gif" width="50px;" /></div> 
			</div>
			
		</div>
	    </div><!--Wrap-->
				
			
			
			
			
			
		    </div>
		</form>

	</div>
</div>