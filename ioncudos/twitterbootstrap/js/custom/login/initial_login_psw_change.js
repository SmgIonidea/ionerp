	
	//initial login password change
	
	var base_url = $('#get_base_url').val();

	$(document).ready(function() {
		$('#initial_login_change_psw').modal({dynamic:true});
	});

	$(function () { $("#new_password").complexify({}, function (valid, complexity) {
			$("#PassValue").val(complexity) ; 
		});
	});

	$('#initial_login_change_psw').on('click','#terms_conditions', function() {
		if($('#terms_conditions').prop('checked') == true) {
			$('#submit_button_id').prop('disabled',false);
		} else {
			$('#submit_button_id').prop('disabled',true);
		}
	});


	$('#initial_login_change_psw').on('click','#submit_button_id', function() {
		var old_password = $('#old_password').val();
		var new_password = $('#new_password').val();
		var re_new_password = $('#re_password').val();
		var user_id = $('#user_id').val();
		
		var post_data = {
			'new_psw' : new_password,
			're_psw' : re_new_password,
			'user_id' : user_id
		};
		
		if(old_password && new_password && re_new_password) {
			if(new_password.length < 8) {
				//display error message - password length should be 8
				var data_options = '{"text":"Password length should be equal or greater than 8 charcters.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
				var options = $.parseJSON(data_options);
				noty(options);
			} else {
				if(old_password != 'password') {
					//display error message - password cannot be default password
					var data_options = '{"text":"Incorrect old password.", "layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
				} else if(new_password == 'password') {
					//display error message - password cannot be default password
					var data_options = '{"text":"Your new password cannot be default password.", "layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
				} else if(old_password == new_password) {
					//display error message - old new password cannot be same
					var data_options = '{"text":"Your old and new password cannot be same.", "layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
				} else if(new_password == re_new_password) {
					$.ajax({type: "POST",
						url: base_url + 'login/update_change_password',
						data: post_data,
						success: function(data){
							if(data){
								var data_options = '{"text":"Your new password has been updated successfully","layout":"center","type":"success","animateOpen": {"opacity": "show"}}';
								var options = $.parseJSON(data_options);
								noty(options);
								setTimeout(function() {
									window.location.href = base_url+'home/'; 
								}, 2000);
							}
						}
					}); 
				} else {
					//display error message - password mismatch
					var data_options = '{"text":"Password mismatch.", "layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
					var options = $.parseJSON(data_options);
					noty(options);
				}
			}
		} else {
			//display error message - password mismatch
			var data_options = '{"text":"All mandatory fields must be filled.","layout":"center","type":"error","animateOpen": {"opacity": "show"}}';
			var options = $.parseJSON(data_options);
			noty(options);
		}
	});
