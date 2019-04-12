<?php
// Exit if accessed directly

if (!defined('ABSPATH'))
    exit;

?>	 
	
	<div class="wrap order-shorrcodes">
	    <h2>Shorcode show order listing</h2>
		<div class="csv_dashboards_admin">
		
		 <table class="csv-list-table widefat fixed striped users">
		  <thead> 
		   <tr>
			  <th class="manage-column" scope="row">
			  Shorcodes
			  </th>	   
		      <td  class="manage-column"> Descriptions  </td>
			</tr>
		  </thead>
		 
		  <tbody id="the-list" data-wp-lists="list:user">
		  
		  
		   <!--Total games-->
		   <tr> <th colspan="2"><h1>Orders shortcode</h1></th> </tr>
			<tr>
			  <th class="manage-column" scope="row">
			  [vcc_order_grid]
			  </th>	
              			  
		      <td  class="manage-column"> Show orders 
			  
			  
			  </td>
			</tr>			
			
		  </tbody>
		 </table>
		 
		 <div class="param_list"> 
		    <table class="csv-list-table widefat fixed striped users">
			
			<tr> <th colspan="2"><h2>Parameters</h2></th> </tr>
			  <tr>
			    <td>title = "All orders"</td>
			    <td>Show title </td>
			  </tr>
			  
			  <tr>
			    <td>per_page = "20"</td>
			    <td>Show row per page </td>
			  </tr>
			  
			  
			   <!--tr> <th colspan="2"><h4>Orders shorting</h4></th> </tr>
			  <tr>
			    <td>sortby = "created_on"</td>
			    <td>Record shorting by colmn (created_on)  </td>
			  </tr>
			  
			  <tr>
			    <td>sort = "DESC"</td>
			    <td>DESC, ASC  </td>
			  </tr-->
			  
			  
			   
			  
			  
			 
			  
			  <tr> <th colspan="2"><h4>Line shorting</h4></th> </tr>
			  <tr>
			    <td>sort_line_by = "sales_order_item"</td>
			    <td>Record shorting by colmn (sales_order_item)  </td>
			  </tr>
			  
			  <tr>
			    <td>sort_line = "DESC"</td>
			    <td>DESC, ASC  </td>
			  </tr>
			  
			  
			  
			  
			  
			 </table> 
		    
		 </div>
		
	    </div><!-- .csv_dashboards_admin --> 

   </div>
	 
