var base_url = $('#get_base_url').val();
	
$(document).ready(function(){	

	$.validator.addMethod("sel", function(value, element) {
	    return (value != '0');
	}, "This field is required.");	
		
	$.validator.addMethod("one_required", function(value, element) {
		return $(document).find(".one_required:checked").length > 0;
	}, 'Select at least one role.');
	
	//Function to validate and submit the form if valid
	$(document).on('click','.import_user_form_submit',function(e){		
		$('#loading').show();		
		$("#import_list_form").validate({
			rules: {
				department: {
					sel: true
				},
				user: {
					sel: true
				}				
			},
			errorClass: "help-inline font_color",
			errorElement: "span",
			highlight: function(element, errorClass, validClass) {
				$(element).addClass('error');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).removeClass('error');
				$(element).addClass('success');
			}
		}); 
		$('#loading').hide();
		e.preventDefault();	
		var flag = $('#import_list_form').valid();
		if(flag){
			$('#import_list_form').submit();
		} 
	});
	
	//Function to load the active users of selected department
	$('#import_list_form').on('change','#department',function(){
		var dept_id = $('#department').val();
		$('#roles_outer_div').css({'display':'none'});
		$('#loading').show();
		var post_data = {
			'dept_id' : dept_id
		}
		$.ajax({type:'POST',
				url: base_url+'curriculum/import_user/getActiveUsersOfDepartment',
				data: post_data,
				success: function(user_list) {
					$('#user_email_div').empty();
					$('#user').html(user_list);
					$('#loading').hide();
				}
		});
	});
	
	//Function to get email of user
	$('#import_list_form').on('change','#user',function(){
		var user_id = $('#user').val();
		$('#loading').show();
		var post_data = {
			'user_id' : user_id
		}
		if(user_id!=0){
			$.ajax({type:'POST',
				url: base_url+'curriculum/import_user/getUserInfo',
				data: post_data,
				success: function(user_info) {
					$('#user_email_div').html('Email-Id :'+user_info);
					$('#loading').hide();
				}
			});
		} else {
			$('#user_email_div').html("");
			$('#loading').hide();
		}
	});
	
	//Function to delete the user-roles of a user
	$(document).on('click','.delete_user',function(e){
		$('#loading').show();
		e.preventDefault();
		var user_id = $(this).attr('value');
                var post_data = {
                    'user_id':user_id,
                };
                $.ajax({type:'POST',
				url: base_url+'curriculum/import_user/check_user_alloted_to_course',
				data: post_data,
                                dataType:'html',
				success: function(data) {
					if($.trim(data)==0){
                                            $('#delete_user_id_val').val(user_id);
                                            $('#delete_import_user_div').modal('show');
                                            $('#loading').hide();
                                        }else{
                                            $('#user_cannot_delete_modal').modal('show');
                                            $('#loading').hide();
                                            
                                        }
				}
		});
		
	}); 
	
	//Function to confirm deleting the user-roles of a user
	$(document).on('click','.delete_import_user_btn',function(){
		var uid = $('#delete_user_id_val').val();
		$('#loading').show();
		var user_id = {
					'user_id' : uid
				}
		$.ajax({type: 'POST',
				url: base_url+'curriculum/import_user/deleteUser_Roles',
				data: user_id,
				success: function() {
					window.location.href = base_url+'curriculum/import_user';
					$('#loading').hide();
				}
		});
	});
	
	//Function to display the edit form for editing user role
	$(document).on('click','.edit_user',function(e){
		e.preventDefault();
		var uid = $(this).attr('value');
		var user_id = {
					'user_id' : uid
				}
		$('#edit_user_id_val').val(uid);
		$.ajax({type: 'POST',
				url: base_url+'curriculum/import_user/getUserAssignedRoles',
				data: user_id,
				success: function(role_data) {
					$('#edit_user_data_div').html(role_data);
				}
		});
		$('#edit_import_user_div').modal('show');		
	});
	
	//Function to validate the edit user-role form
	$("#edit_user_data_div_form").validate({
		rules: {
			'updated_roles[]': {
				one_required: true
			},
		},
		errorClass: "help-inline font_color",
		errorElement: "span",
		highlight: function(element, errorClass, validClass) {
			$(element).addClass('error');
		},
		unhighlight: function(element, errorClass, validClass) {
			$(element).removeClass('error');
			$(element).addClass('success');
		},
		errorPlacement: function(error, element) {
			if(element.attr("name") == "updated_roles[]"){
				 error.appendTo(element.closest("td#update_data"));
			}else{
				error.insertAfter(element);
			}
		} 
	}); 	
	
	//Function to confirm the updated roles for a user
	$(document).on('click','.edit_import_user_btn',function(e){
		e.preventDefault();
		var update_form = $("#edit_user_data_div_form").valid();
		if(update_form){
			var selected_roles = [];
			$('.update_role:checked').each(function(){
			  selected_roles.push($(this).val());
			});
			var uid = $('#edit_user_id_val').val();
			var edit_user_role_data = {
									'user_id' : uid,
									'roles' : selected_roles
								}
			$.ajax({type: 'POST',
					url: base_url+'curriculum/import_user/editUserRoles',
					data: edit_user_role_data,
					success: function(role_data) {		
						$('#edit_import_user_div').modal('hide');
					}
			});
		}else{
		}
	});

	$('#reset').click(function(){
		$('#user_email_div').html("");
	});

});
