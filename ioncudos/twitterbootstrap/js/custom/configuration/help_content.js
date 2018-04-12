
// Help Content page Script Starts from here.

// tinyMCE window loads from here.
 tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste moxiemanager"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});

		
// tinyMCE ends here.

 $(document).ready(function() {
                    var cloneCntr = 2;
                    $.validator.addMethod("numeric", function(value, element) {
                        var regex = /^[0-9]+$/; //this is for numeric... you can do any regular expression you like...
                        return this.optional(element) || regex.test(value);
                    }, "Field must contain only numbers.");
                    
                    $.validator.addMethod("loginRegex", function(value, element) {
                        return this.optional(element) || /^[a-zA-Z0-9\_\-\s\']+$/i.test(value);
                    }, "Field must contain only letters, numbers, spaces,underscore ' or dashes.");
                   // $("#form1").validate();
                    
                    $("#form1").validate({
                        rules: {
                            page_name: {
                                loginRegex: true,
								required : true
                            },
                            text_content: {
                                loginRegex: true,
                            }
                        },
                        message: {
                            page_name: "This field is required",
                            text_content: "This field is required"
                        },
                        errorClass: "help-inline font_color",
                        errorElement: "span",
                        highlight: function(element, errorClass, validClass) {
                            $(element).parent('.controls').addClass('error font_color');
                        },
                        unhighlight: function(element, errorClass, validClass) {
                            $(element).parent('.controls').removeClass('error');
                            $(element).parent('.controls').addClass('success');
                        }
                    });
                });


                var data_val;
                function gethelpid(id)
                {
                    data_val = id;
                }
                function display_help()
                {
					var update_path = 'help_management/update_content';
                    var data_val = document.getElementById('help').value;
                    var post_data = {
                        'page_name': data_val
                    }
                    $.ajax(
                            {
                                type: "POST",
                                url: update_path,
                                data: post_data,
                                datatype: "JSON",
                                success: function(msg) {
                                    tinyMCE.activeEditor.setContent(msg);
                                }
                            }
                    );
                }
	$('#page_name').change(function(){
$('#entity_name').val($('#page_name').find(':selected').text());
});
                function fun_submit()
                {
                    document.forms["form1"].submit();
                }
				
//	 <!-- /TinyMCE -->
		if (document.location.protocol == 'file:') {
                    alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
                }


// Help Content page Script Ends here.
