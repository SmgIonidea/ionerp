	
	//Lab Category .js

	//used to check lab category name have only alphabet.
	$.validator.addMethod("loginRegex", function(value, element) {
		return this.optional(element) || /^[a-zA-Z\s]+([-\a-zA-Z\s])*$/i.test(value);
	}, "Field must contain only letters, spaces or dashes.");  

	// Form validation rules are defined & checked before form is submitted to controller.
	$("#add_form_id").validate({
		rules:{
			lab_category_name: {
				loginRegex: true			
			},
		},
		message: {
	lab_category_name:"This field is required"
	},
	errorClass: "help-inline",
	errorElement: "span",
	highlight:function(element, errorClass, validClass) {
		$(element).parent().parent().removeClass('success');
		$(element).parent().parent().addClass('error');
	},
	unhighlight: function(element, errorClass, validClass) {
		$(element).parent().parent().removeClass('error');
		$(element).parent().parent().addClass('success');
	}
	});
			
	//Function to call the uniqueness of lab category name to controller
	$('.add_form_submit').click( function (e) {
		$("#loding").show();
		e.preventDefault();
		var flag;
		flag=$('#add_form_id').valid();
		var data_val= document.getElementById("lab_category_name").value;
		
		var post_data= {
			'lab_category_name':data_val
		}

		if(flag) {
			$.ajax({
				type: "POST",
				url: base_url+'configuration/lab_category/add_search_lab_category_by_name',
				data: post_data,
				datatype: "JSON",
				success: function(msg){
					if($.trim(msg) == 1) {   
						submit_form();
					} else {
						$('#uniqueness_dialog').modal('show');
					}
				}
			});
		}
	});
                        	
	// Function is to submit the form by sending all the POST data to controller.	
	function submit_form() {
		var lab_category_name=$('#lab_category_name').val();
		var lab_category_description=$('#lab_category_description').val();

		$.ajax({
			type: "POST",
			url: base_url+'configuration/lab_category/lab_category_insert_record',
			data: {'lab_category_name':lab_category_name,'lab_category_description':lab_category_description},
			datatype: "JSON",
			success: function(msg){
				window.location.href = base_url+'configuration/lab_category';
			}
		});
	} 

	//Form validation rules are defined when edited the lab category details & to check the form before it is submitted to controller. 			
	$("#edit_form_id").validate({
		rules:{
			lab_category_name: {
				loginRegex: true,		
			},
		},
		message:{
		lab_category_name:"This field is required"
		},
		errorClass: "help-inline",
		errorElement: "span",
		highlight:function(element, errorClass, validClass) {
			$(element).parent().parent().removeClass('success');
			$(element).parent().parent().addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).parent().parent().removeClass('error');
			$(element).parent().parent().addClass('success');
		}
	});

	//Function to call the uniqueness of lab category name to controller
	$('.edit_form_submit').click( function (e) {
		$("#loding").show();
		e.preventDefault();
		var flag;
		flag=$('#edit_form_id').valid();
		var data_val= document.getElementById("lab_category_name").value;
		var data_val1= document.getElementById("lab_category_id").value;

		var post_data= {
			'lab_category_name':data_val,
			'lab_category_id':data_val1,
			'lab_category_desc':document.getElementById("lab_category_desc").value
		}

		if(flag) {
			$.ajax({
				type: "POST",
				url: base_url+'configuration/lab_category/search_lab_category_by_name',
				data: post_data,
				datatype: "JSON",
				success: function(msg){
					if(msg == 0) {
						$.ajax({
							type:"POST",
							url:base_url+'configuration/lab_category/lab_category_update_record',
							data:post_data,
							datatype:"json",
							success:function(data){
								window.location.href = base_url+'configuration/lab_category';
		                                        }
						});
					} else {
						$('#uniqueness_dialog_edit').modal('show');
					}
				}
			});
		}
	});

	//count number of characters entered in the description box
    	$('.char-counter').live('keyup', function () {
        	var max = parseInt($(this).attr('maxlength'));
        	var len = $(this).val().length;
        	var spanId = 'char_span_support';

        	if (len >= max) {
            		$('#' + spanId).css('color', 'red');
            		$('#' + spanId).text(' You have reached the limit.');
        	} else {
            		$('#' + spanId).css('color', '');
            		$('#' + spanId).text(len + ' of ' + max + '.');
        	}
    	});
	
	
	//Function is call when click on the delete to check whether lab category id should be delete or not.
	function deleteRecord(deleteid) {
		var post_data = {
			'lab_category_id': deleteid
	     	}

		$.ajax({
			type:"POST",
			url:base_url+'configuration/lab_category/lab_category_delete_record_check',
			data:post_data,
			datatype:"json",
			success:function(data) {
				if(data == 0) {
					$("#storeId").val(deleteid);
					$('#enable_dialog').modal('show');
				}
				else{
					$('#enable_dialog1').modal('show');
				}
			}
		});
       	}
	
	//Function is call to delete lab category
	function deleteLabCategory() {
		var post_data = {
			'lab_category_id': $("#storeId").val(),
	     	}

		$.ajax({
			type:"POST",
			url:base_url+'configuration/lab_category/lab_category_delete_record',
			data:post_data,
			datatype:"json",
			success:function(data) {
				window.location.href = base_url+'configuration/lab_category';
			}
		});
	}	
