
// Reset Forgot Password JS scripts

	var base_url = $('#get_base_url').val();
	
	$('#submit_button_id').live('click', function() {
        var flag = 0;
        flag = $('#update_form_id').valid();
		
		if (flag){
			var password = document.getElementById('password').value;
			var confirm_password = document.getElementById('confirm_password').value;
			if(password != confirm_password) {
			$('#mismatch_password_dialog_id').modal('show');
			document.getElementById('password').value = '';
			document.getElementById('confirm_password').value = '';
			} else {
			$('#reset_password_dialog_id').modal('show');
			}
		} 
	});

	function send_request_dialog() {
		
		var user_id = document.getElementById('user_id').value;
		var email_id = document.getElementById('email_id').value;
		var password = document.getElementById('password').value;
        var post_data = {
				'user_id': user_id,
				'email': email_id,
				'password': password
			}
		$('#loading').show();
		$.ajax({
			type: "POST",
			url: base_url + 'login/update_reset_password',
            data: post_data,
            success: function(msg) {
				$('#sent_email_dialog_id').modal('show');
            }
        });
    }
	
//function is to redirect user after successful submit
    function submit_success() {
        $('#update_form_id').submit();
    }
	
	//Form validation 
	$(document).ready(function() {
        
		$("#update_form_id").validate({
            rules: {
				password: {
                    required: true,
                    minlength: 8
                },
				confirm_password: {
                    required: true,
                    minlength: 8
                }
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function(element, errorClass, validClass) {
                $(element).parent().parent().addClass('error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parent().parent().removeClass('error');
                $(element).parent().parent().addClass('success');
            }
        });
    });
	
	