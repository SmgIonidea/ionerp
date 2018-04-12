	//used to check curriculum component name have only alphabet.
	$.validator.addMethod("loginRegex", function(value, element) {
		return this.optional(element) || /^[a-zA-Z\s]+([-\a-zA-Z\s])*$/i.test(value);
	}, "Field must contain only letters, spaces or dashes.");  

	// Form validation rules are defined & checked before form is submitted to controller.
	$("#add_form_id").validate({
            
		rules:{
			curriculum_component_name: {
				loginRegex: true			
			},
		},
		message:{
	curriculum_component_name:"This field is required"
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
			
	//Function to call the uniqueness of program type name to controller
	$('.add_form_submit').click( function (e){
		e.preventDefault();
		var flag;
		flag=$('#add_form_id').valid();
		var data_val= document.getElementById("curriculum_component_name").value;
		var post_data={
			'curriculum_component_name':data_val
		}
		if(flag)
		{
			
			$.ajax({
				type: "POST",
				url: base_url+'configuration/curriculum_component/add_search_curriculum_component_by_name',
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
                        	
	/* Function is to submit the form by sending all the POST data to controller.
	* @param - POST array
	*/			
	 function submit_form(){
		$.post('curriculum_component_insert_record',$('#add_form_id').serialize(),function(data){
			window.location.href = base_url+'configuration/curriculum_component';
		});
	} 

	//Form validation rules are defined when edited the Curriculum component details & to check the form before it is submitted to controller. 			
	$("#edit_form_id").validate({
		rules:{
			crclm_component_name: {
				loginRegex: true,		
			},
		},
		message:{
		crclm_component_name:"This field is required"
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

	//Function to call the uniqueness of program type name to controller
	$('.edit_form_submit').click( function (e){
		e.preventDefault();
		var flag;
		flag=$('#edit_form_id').valid();
		var data_val= document.getElementById("crclm_component_name").value;
		var data_val1= document.getElementById("cc_id").value;
		var post_data={
			'crclm_component_name':data_val,
			'cc_id':data_val1,
			'crclm_component_desc':document.getElementById("crclm_component_desc").value
		}
		if(flag)
		{
			
			$.ajax({
				type: "POST",
				url: base_url+'configuration/curriculum_component/search_curriculum_component_by_name',
				data: post_data,
				datatype: "JSON",
				success: function(msg){
					if(msg == 1) {
						$.ajax({
							type:"POST",
							url:base_url+'configuration/curriculum_component/curriculum_component_update_record',
							data:post_data,
							datatype:"json",
							success:function(data){
								window.location.href = base_url+'configuration/curriculum_component';
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
	
	/* Function is call when click on the delete to store which curriculum component id should be delete.
	* @param - curriculum component id
        * @returns-
	*/
  	function storeId(id){
		deleteid=id;
	}	
	
	/* Function is call to delete curriculum component id .
	* @param -
        * @returns-
	*/
	function deleteRecord(){
	var post_data={
        	'cc_id':deleteid
     	}
	$.ajax({
		type:"POST",
		url:base_url+'configuration/curriculum_component/curriculum_component_delete_record',
		data:post_data,
		datatype:"json",
		success:function(data){
		window.location.href = base_url+'configuration/curriculum_component';
		}
	});
       	}	
