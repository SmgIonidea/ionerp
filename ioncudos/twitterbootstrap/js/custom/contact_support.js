//This file contains contact support modal validations//
	var base_url = $('#get_base_url').val();
	
	function generate_modal()
	{
	
	 $('#loginForm').each (function(){
        this.reset();
		var validator = $('#loginForm').validate();
		validator.resetForm();
      });
	  
		$("#myModal").modal('show');
		
	}

	
	 $.validator.addMethod("digits", function(value, element) {
            return this.optional(element) || /^\d{10}$/.test(value);
        }, "Field must contain only Ten Numbers");
    $('#loginForm').validate({
		rules: {
					modal_number:{
						digits:true
					}
			 },
       errorClass: "help-inline",
		//	errorElement: "span",
			highlight: function(element, errorClass, validClass) {
				$(element).parent().parent().addClass('');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).parent().parent().removeClass('');
				$(element).parent().parent().addClass('');
			}
			
    });

	//count number of characters entered in the description box
    $('.char-counter').each(function () {
        var len = $(this).val().length;
        var max = parseInt($(this).attr('maxlength'));
        var spanId = 'char_span_support';
        $('#' + spanId).text(len + ' of ' + max + '.');
    });
    $('.char-counter').live('keyup', function () {

        var max = parseInt($(this).attr('maxlength'));
        var len = $(this).val().length;
        var spanId = 'char_span_support';
        console.log(spanId, 'length=', len);
        if (len >= max) {
            $('#' + spanId).css('color', 'red');
            $('#' + spanId).text(' you have reached the limit');
        } else {
            $('#' + spanId).css('color', '');
            $('#' + spanId).text(len + ' of ' + max + '.');
        }
    });
    $('.char-counter').live('blur', function () {
        var max = parseInt($(this).attr('maxlength'));
        var len = $(this).val().length;
        var spanId = 'char_span_support';
        console.log(spanId, 'length=', len);
        if (len >= max) {
            $('#' + spanId).css('color', 'red');
            $('#' + spanId).text(' you have reached the limit');
        } else {
            $('#' + spanId).text(len + ' of ' + max + '.');
            $('#' + spanId).css('color', '');
        }
        
        $(this).text($(this).val()); 
    });
    
	
	$('#modal_contact').click(function()
	{
	 if($('#loginForm').valid()){
	 var subject = document.getElementById('modal_subject').value;
	 var body = document.getElementById('modal_body').value;
	 var number = document.getElementById('modal_number').value;
	 var mail = document.getElementById('modal_mail').value;
	 var post_data = {
				'subject':subject,
				'body':body,
				'number':number,
				'mail':mail
            }
			 $.ajax({
				type: "POST",
				url: base_url + 'login/contact_support',
                data: post_data,
				success:function(data){
				window.location.assign(base_url);
				},
				error:function(data){
					
				}
            });
		
	 }
   });
	
	