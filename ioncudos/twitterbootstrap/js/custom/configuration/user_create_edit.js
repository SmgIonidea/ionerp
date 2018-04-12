
//Create and edit User Page
var base_url = $('#get_base_url').val();
$(document).ready(function () {
    
    $('#usergroup_id').multiselect({
        onChange: function(element, checked) {
	    $('.multiselect').valid();
        },
        buttonWidth: '220px',
        numberDisplayed: 2,
        nSelectedText: 'selected',
        nonSelectedText: 'Select User Group',
        templates: {
            button: '<button id="" type="button" class="multiselect dropdown-toggle btn btn-link multi_select_decoration" data-toggle="dropdown"></button>' 
        }
    });
    $('#usergroup_id').multiselect('rebuild');

    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    var randomstring = '';
    for (var i = 0; i < 8; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum, rnum + 1);
    }
    $('#password').val(randomstring);

    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\-\s\._\']*$/i.test(value);
    }, "Field must contain only letters, spaces, ', numbers, underscore, dashes or dot");
    $.validator.addMethod("customRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\-\s\._\'\,]+$/i.test(value);
    }, "Field must contain only letters, spaces, dots, underscore, comma, apostrophe or dashes.");
    //$.validator.addMethod("loginEmail", function(value, element) { 
    //  return this.optional(element) || /^[a-zA-Z0-9]+(-[a-zA-Z0-9]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/.test(value);
    //}, "Field must contain only letters, spaces, ' or dashes or dot");
    $.validator.addMethod("onlyDigit", function (value, element) {
        return this.optional(element) || /^[0-9\.\_]+$/i.test(value);
    }, "This field must contain only Numbers.");

    $.validator.addMethod('email', function (value, element) {
        return this.optional(element) || /^(^[A-Za-z0-9]+([\._-]?[A-Za-z0-9]+)*@[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*(\.[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*)+)+$/.test(value);
    },
            'Verify you have a valid email address.'
            );

    $.validator.addMethod("needsSelection", function(value, element) {
        return $(element).find('option:selected').length > 0;
    }, 'This field is required.');
			
	
var telInput = $("#mobile-number");
var errorMsg = $("#error-msg");
var validMsg = $("#valid-msg");

var reset = function() {
  telInput.removeClass("error");
  errorMsg.addClass("hide");
  validMsg.addClass("hide");
};
// on blur: validate
 telInput.on('blur change ',function() {
 reset();
  if ($.trim(telInput.val())) {  
    if (telInput.intlTelInput("isValidNumber")) {
      validMsg.removeClass("hide");
	  //	  document.getElementById('mobile-number').style.borderColor='green';
    } else {
      telInput.addClass("error");
      errorMsg.removeClass("hide");
	 // document.getElementById('mobile-number').style.borderColor='#e52213';
    }
  }
});
    
    $("#create_user").validate({
        rules: {
            first_name: {
                customRegex: true,
            },
            last_name: {
                customRegex: true,
            },
            email: {
                email: true,
            },
            qualification: {
                loginRegex: true,
            },
            experience: {
                onlyDigit: true,
                maxlength: 5
            },
            password: {
                required: true,
                minlength: 8
            },
	
            password_confirm: {
                required: true,
                equalTo: "#password"
            },
            "usergroup_id[]": {
                required: true,
                needsSelection: true
            }
        },
        ignore: ':hidden:not("#usergroup_id")',
        errorClass: "help-inline",
        errorElement: "span",
        errorPlacement: function (error, element) {
            if(element.attr("name") == "usergroup_id[]") {
                error.insertAfter(element.siblings(":last"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            if($(element).attr("name") == "usergroup_id[]") {
               $(element).parent().parent().addClass('error');
            } else {
                $(element).parent().parent().addClass('error');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if($(element).attr("name") == "usergroup_id[]") {
                $(element).siblings(":last").removeClass('error');
                $(element).siblings(":last").addClass('success');
            } else {
                $(element).parent().parent().removeClass('error');
                $(element).parent().parent().addClass('success');
            }
        }
    });

    $("#edit_user").validate({
        rules: {
            first_name: {
                customRegex: true,
            },
            last_name: {
                customRegex: true,
            },
            email: {
                email: true,
                required: true
            },
            experience: {
                onlyDigit: true,
                maxlength: 5
            },
            qualification: {
                loginRegex: true,
            },
            password: {
                minlength: 8
            },
            password_confirm: {
                equalTo: "#password"
            },
            "usergroup_id[]": {
                required: true,
                needsSelection: true
            }
        },
        ignore: ':hidden:not("#usergroup_id")',
        errorClass: "help-inline",
        errorElement: "span",
        errorPlacement: function (error, element) {
            if(element.attr("name") == "usergroup_id[]") {
                error.insertAfter(element.siblings(":last"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            if($(element).attr("name") == "usergroup_id[]") {
               $(element).parent().parent().addClass('error');
            } else {
                $(element).parent().parent().addClass('error');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            if($(element).attr("name") == "usergroup_id[]") {
                $(element).siblings(":last").removeClass('error');
                $(element).siblings(":last").addClass('success');
            } else {
                $(element).parent().parent().removeClass('error');
                $(element).parent().parent().addClass('success');
            }
        }
    });

    // Auto generate Password scripts

    $('.randomPassword').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: base_url + 'configuration/users/randomPassword',
            datatype: "JSON",
            success: function (msg) {
                $('#password').val(msg);
            }
        });
    });

    $('.reset_randomPassword').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: base_url + 'configuration/users/randomPassword',
            datatype: "JSON",
            success: function (msg) {
                $('#reset_password').val(msg);
            }
        });
    });
});

//Create User and Edit User Script Ends Here