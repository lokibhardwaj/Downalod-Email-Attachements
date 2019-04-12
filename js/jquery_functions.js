jQuery(function ($) {

  $('.add_staff_vcc').attr('title','Grant access to the VCC portal for additional staff at your company here.');

  //Order Div show Hide
   // shows on clicking the noted link
   /*jQuery('#grid_data').find('li').click(function() {
        jQuery(this).toggleClass("open");
        jQuery(this).find("div.relate").slideToggle('2000');
        $(".relate").not($(this).find("div.relate")).hide("slow","linear");
        jQuery('#grid_data').find('li').not($(this)).removeClass('open') ;
        return false;
    });*/
	
	 jQuery('#grid_data').find('li').click(function() {
        jQuery(this).toggleClass("open");
        jQuery(this).next("div").slideToggle('5000');
        $(".relate").not($(this).next()).hide("slow","linear");
        jQuery('#grid_data').find('li').not($(this)).removeClass('open') ;
        return false;
    });
	
	
	//Count shipped orders
	
	$('#grid_data').each(function(){
		var phrase = '';
		$(this).find('li').each(function(){
			var current = $(this).attr('id');
			
			var totalOrder = $(this).find('div.fillFilled').attr("data");
			var sipped_row  = $(this).next("div.relate").find('div.sipped_row').text();
			var unsipped_row  = $(this).next("div.relate").find('div.unsipped_row').text();
			var is_open  = $(this).next("div.relate").find('.is_open').val();
			if(is_open == 1){
				if(unsipped_row ==""){
					unsipped_row = 0;
				}
				var shipped = $(this).find('div.fillFilled').attr("shipped");
				var openPluseShipped = parseInt(shipped) + parseInt(totalOrder);
				var dataRow = shipped + ' of ' +openPluseShipped+' ';
				
			}else{
			
			var dataRow = sipped_row + ' of ' +totalOrder+' ';
			
			}
			$(this).find('div.fillFilled').html(dataRow);
			//alert(textComplete);
			
		});
		
	});
	
	//ea_per_page submit
	$( "#ea_per_page" ).change(function() {
		
		if($(this).val() !=''){			
		  this.form.submit();
		}     
     });
	 
	 //ea_account Submit on change
	 $( "#ea_account" ).change(function() {	
	 
		if($(this).val() !=''){			
		  this.form.submit();
		}     
     });
	
	//Filter submit
	$( "#ea_orders" ).change(function() {
		
		if($(this).val() =='all'){
			$("#ea_from,#ea_to").val("");
		}
		 this.form.submit();
     
     });
	 
	 
	 //Date picker filter
	 //var date = new Date();
		$('#ea_from').datepicker({
			
			format: "dd/mm/yyyy",
			minDate: new Date(2010,0,1),
			maxDate: new Date(2030,0,1),
			yearRange: '2010:2030' ,
			changeYear: true,
			changeMonth: true,
			showButtonPanel: true,
			closeText: 'Clear',
			onSelect: function (dateText, inst) {
				 $("#ea_to").attr('disabled',false);
				 
				 var dt = new Date(dateText);
				dt.setDate(dt.getDate() + 1);
				$("#ea_to").datepicker("option", "minDate", dt);
				
			  },
			 onClose: function (dateText, inst) {
               
                 var event = arguments.callee.caller.caller.arguments[0];
                // If "Clear" gets clicked, then really clear it
                if ($(event.delegateTarget).hasClass('ui-datepicker-close')) {
                    $(this).val('');
                    $("#ea_to").val('');
					$("#ea_to").attr('disabled',true);
                }
            }
			
		});
		
		
		
		$('#ea_to').datepicker({
			format: "mm/dd/yyyy",
			minDate: new Date($("#ea_from").val()),
			maxDate: new Date(2030,0,1),
			yearRange: '2010:2030' ,
			changeYear: true,
			changeMonth: true,
			showButtonPanel: true,
			 closeText: 'Clear',
			onSelect: function (dateText, inst) {
				if($("#ea_from").val() ==""){
					$(this).val('');
					alert("Select from date!");
				}else{
					//alert($("#ea_from").val());
				}
				 
			  },
			 onClose: function (dateText, inst) {
               
                var event = arguments.callee.caller.caller.arguments[0];
                // If "Clear" gets clicked, then really clear it
                if ($(event.delegateTarget).hasClass('ui-datepicker-close')) {
                    $(this).val('');
                }
             },
			 
			 beforeShow: function( input ) {
			    
             }
		});
		
		
		
		
		
		//Add tabs
		//$('#tab_container_npi').easytabs();
		$(".dl_content").hide(2000);
		$("#training-Materials").show(2000);
		$( "ul.etabs li" ).first().addClass( "active_tab" );
		
		$( "#tab_container_npi a.dl_tab" ).click(function(event) {
			$(".dl_content").hide();
			$("ul.etabs li").removeClass("active_tab");
			
			$(this).parent().addClass("active_tab");
			
			
			var currentTab = $(this).attr("data");
			$(currentTab).show(500);
			
			event.stopImmediatePropagation();      			
	     
		 });
		 
		 
		 
		 
		 $(".su-tabs-nav span").mouseover(function() {
			 if($(this).text() =="Downloads"){
				 
				// $("*").unbind("click");
				 
			 }else{
				 
				//$("*").bind("click"); 
			 }
			 
		 });
		 
		 
		 
	
    //Load More page result NPI	
	//$('.npi_pages a').click(function (event) {
	$(document).on("click", '.npi_pages a', function(event) { 
	
	   $(".loader_npi").show();
		
		var paged  = $(this).attr('data');
		var per_page  = $("#npi_per_page").val();
			
		
		jQuery.ajax({
			type: 'POST',
			url: $("#ajax_url").val(),
			data: {
				'action'         :   'npiAjax_record',
				'page'           :   paged,
				'per_page'       :   per_page
				
			},
			success: function (data) {
			   $(".loader_npi").hide();
			   
			   $("#npi_inner").html(data);
				
			},
			error: function (errorThrown) {
			}
		   });
		
		 return false;

    }); //Load More page result NPI	
			
		
		
	
	
	

});	// Main Close


