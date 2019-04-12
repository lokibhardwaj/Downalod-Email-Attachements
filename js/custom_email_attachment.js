jQuery(function ($) {
	
//Tab Accordian
jQuery('div.section_body').hide();

jQuery('div.1stSection').show();
jQuery('h3.1sth3').addClass('open');

    // shows on clicking the noted link
    jQuery('#ptpdf-options').find('h3').click(function() {
        jQuery(this).toggleClass("open");
        jQuery(this).next("div").slideToggle('1000');
        $(".section_body").not($(this).next()).hide("slow","linear");
        jQuery('#ptpdf-options').find('h3').not($(this)).removeClass('open') ;
        return false;
    });
	
	
	//Enter only number
	jQuery(".onlyNumber").keydown(function (e) {
	
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	
	
	//clearLog File via ajax
	
	$('#clearLog').click(function (event) {
		
		if (confirm("Do you want to clear log details?")) {
			
		
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action'            :   'ajax_Att_clearLog',
				'cleaLog'           :   '1'
				
			},
			success: function (data) {
			   
				$("#setting-error-settings_updated").html("<p><strong>Log file cleared successfully.</strong></p>").show();
				
				$("#logFileContent").html("");
			},
			error: function (errorThrown) {
			}
		   });
		 }
		 return false;

    });//Click clear log
	
	
	
	
	//Select Csv Files
	$( "#importedCsv" ).change(function() {
		
		$("#importRunFile").val($(this).val());
     
     });
	 
	 $( "#csvType" ).change(function() {
		var formAction =  $("#slectCsvFrmAction").val();
		
		if ($(this).val() =="sales_backlog"){
		
		  var newAction = formAction+'&action=sales_backlog';
		   $("#slectCsvFrm").attr("action",newAction);
	     }else{
			var newAction = formAction+'&action=oubound_deliveries';
		   $("#slectCsvFrm").attr("action",newAction); 
		 }
     });
	 
	 
	 
	 
	 
	
		
	 
	 //Slect csv
	  jQuery("#slectCsvFrm").validate({
		   rules: {
            csvType: {
                selectcheck: true
            },
			
			importedCsv: {
                selectcheck: true
            },
          }
		});
	 
	 //validation import form
	 
	/* $("#mapCsvFrm").validate({
			rules: {
				"wpcf[]": "required"
			},
			messages: {
				"category[]": "Please select category",
			}
		});*/
		
	  jQuery.validator.addMethod('selectcheck', function (value) {
           return (value != '0');
        }, "Required");
		
	// Get all folder from email	
	$("#att_setting_frm").validate({
		   rules: {               
               "att[selectFolder]": {
                  selectcheck: true
                 },
             "att[selectFolderMove]": {
                  selectcheck: false
                 }, 				 
                 
                },                
             messages: {  
				   
                  },	
			
		  submitHandler: function(form) {
			  form.submit();
			  /*
			// do other things for a valid form
		   $("#loader").show();
		  
		   
			 $.ajax({
            type: 'post',
            url: ajaxurl,
			dataType: "json",
            data: {
				'action'      :   'email_ajax_att_sub_folder',
				
			},
            success: function (data) {
			 $("#loader").hide();	
			
               		 
               		 //data['key']
				if(data['key'] ==0){
					
					 //$("#email_result").html(data['msg']);  
					 form.preventDefault();
					  //form.submit();
					 
				  }else{
					 //alert(data);
					 //var 
					  form.preventDefault();
					   //form.submit();
					 
				  }
				}
			  });
			
			
			*/
		  }
		});//Email att_setting_frm validate
		
		
		
	$('#importFolder').click(function (event) {
		
		 $("#loaderBtn").show();
		   var hostName = $(".hostName").val();
		   var userName = $(".userName").val();
		   var userPassword = $(".userPassword").val();
		   
			 $.ajax({
            type: 'post',
            url: ajaxurl,
			dataType: "json",
            data: {
				'action'      :   'import_ajax_att_sub_folder',
				'hostName'    :   hostName,
				'userName'    :   userName,
				'userPassword':   userPassword,
				
			},
            success: function (data) {
			 $("#loaderBtn").hide();	
			
               		 
               		 //data['key']
				if(data['key'] ==0){	
				      $('#selectFolder')
						.find('option')
						.remove()
						.end()
						.append('<option value="0">Select folder</option>')
						.val('0');
						
					 $('#selectFolderMove')
						.find('option')
						.remove()
						.end()
						.append('<option value="0">Select folder</option>')
						.val('0');	
						
					 $("#email_result").html(data['msg']); 
				  }else{
					 $('#selectFolder').html(''); 
					 $.each(data, function(key, value) {   
						 $('#selectFolder')
							 .append($("<option></option>")
										.attr("value",key)
										.text(value)); 
					  });
					  
					  
					 //selectFolderMove
					 $('#selectFolderMove').html(''); 
					 $.each(data, function(key, value) { 
					 
					 var value1 = value.split('}');
                      value1 = value1[1].replace('[Gmail]/', '');
						 $('#selectFolderMove')
							 .append($("<option></option>")
										.attr("value",value1)
										.text(value1)); 
					  }); 
					 
				  }
				}
			  });
		

    });//import Folder
	
	
	
	//Select shipped Current date
		
		$('#testShippedDate').datepicker({
			
			format: "dd/mm/yyyy",
			minDate: new Date(2010,0,1),
			maxDate: new Date(2030,0,1),
			yearRange: '2010:2030' ,
			changeYear: true,
			changeMonth: true,
			showButtonPanel: true,
			closeText: 'Clear',
			onSelect: function (dateText, inst) {		 
				
			  },
			 onClose: function (dateText, inst) {               
                 var event = arguments.callee.caller.caller.arguments[0];               
                if ($(event.delegateTarget).hasClass('ui-datepicker-close')) {
                    $(this).val('');                    
                }
            }
			
		});
		
		//Send Test email
		
		$('#testSendEmail').click(function (event) {		
		
		   var testShippedDate = $("#testShippedDate").val();
		   var testSapAccounts = $("#testSapAccounts").val();
		   var testEmail = $("#testEmail").val();
		   if( testShippedDate !="" && testSapAccounts !="" && testEmail !="" ){
			   
			  $("#loaderTest").show();  
			 $.ajax({
            type: 'post',
            url: ajaxurl,			
            data: {
				'action'      :   'send_ajax_test_shipped_email',
				'testShippedDate'    :   testShippedDate,
				'testSapAccounts'    :   testSapAccounts,
				'testEmail':   testEmail,
				
			},
            success: function (data) {
			
			  $("#loaderTest").hide();			
				  
				  $("#testMailResult").text(data);
				}
		   });
		   return false;
		 
		   }else{
			   alert("Shipped Date,Sap account, To email required!");
		   }
		

    });//Send Test email
		
	


  

});	// Main Close


