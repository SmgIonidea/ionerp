
//Department User List / Add / Edit - JS

var base_url = $('#get_base_url').val();

//this function will activate the deactivated users
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

    // Auto generate Password scripts
    $('.randomPassword').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: base_url + 'curriculum/dept_users/randomPassword',
            datatype: "JSON",
            success: function (msg) {
                $('#password').val(msg);
            }
        });
    });

    $('.reset_randomPassword').on('click', function (e) {
        $.ajax({
            type: "GET",
            url: base_url + 'curriculum/dept_users/randomPassword',
            datatype: "JSON",
            success: function (msg) {
                $('#reset_password').val(msg);
            }
        });
    });

    /* 	
     $('#edit_dept_user').click(function() {
     var dept_user_id = $(this).data('dept_user_id');
     alert(""); 			var post_data = {
     'dept_user_id' : dept_user_id
     }
     
     $.ajax({type: "POST",
     url: base_url + 'curriculum/dept_users/edit_dept_user',
     data: post_data,
     success: function(msg) {
     
     }
     });
     });  */

    var id = 0;
    //function to store user id to active or deactive.
    $(".status").live("click", function () {
        id = $(this).attr('id');
    });

    //this function activates / enables the user
    $('.enable-ok').click(function (e) {
        e.preventDefault();

        var user_id = id;

        $.ajax({type: "POST",
            url: base_url + 'curriculum/dept_users/activate/' + user_id,
            success: function (msg) {
                location.reload();
            }
        });
    });

    //this function will deactivate / disables the active users
    $('.disable-ok').click(function (e) {
        e.preventDefault();
        var user_id = id;

        var post_data = {'user_id': user_id}

        $.ajax({
            type: "POST",
            url: base_url + 'curriculum/dept_users/check_user',
            data: post_data,
            datatype: "json",
            success: function (data_1) {
                if (data_1 == 0) {
                    $.ajax({type: "POST",
                        url: base_url + 'curriculum/dept_users/deactivate/' + user_id,
                        success: function (msg) {
                            location.reload();
                        }

                    });

                } else {
                    $('#cannot_disable').modal('show');
                }
            }
        });

        /*$.ajax({type: "POST",
         url: base_url + 'curriculum/dept_users/deactivate/' + user_id,
         success: function (msg) {
         location.reload();
         }
         });*/
    });
});

//form validation
$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\-\s\._\']+$/i.test(value);
}, "Field must contain only dots, space, letters, underscore or dash.");

$.validator.addMethod("customRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\-\s\._\'\,]+$/i.test(value);
}, "Field must contain only letters, spaces, dots, underscore, comma, apostrophe or dashes.");

$.validator.addMethod("onlyDigit", function (value, element) {
    return this.optional(element) || /^[0-9\.]+$/i.test(value);
}, "This field must contain only dots or number.");

$.validator.addMethod('email', function (value, element) {
    return this.optional(element) || /^(^[A-Za-z0-9]+([\._-]?[A-Za-z0-9]+)*@[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*(\.[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*)+)+$/.test(value);
}, 'Verify you have a valid email address.');

$("#add_dept_user").validate({
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
        "usergroup_id[]": {
            required: true
        }
    },
    ignore: ':hidden:not("#usergroup_id")', 
    errorClass: "help-inline",
    errorElement: "span",
    errorPlacement: function (error, element) {
        if (element.attr('name') == "password") {
            error.appendTo('#error_placeholder');
        } else if (element.hasClass('multiselect')) {
            error.insertAfter(element.next('.btn-group'));
        } else {
            error.insertAfter(element);
        }
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).hasClass('multiselect')) {
               //$(element).next('.btn-group').addClass('error');
               $(element).closest('.controls').addClass('has-error');
            }
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).hasClass('multiselect')) {
                $(element).closest('.controls').removeClass('has-error');
            }
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

$("#edit_dept_user").validate({
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
        qualification:{
            loginRegex: true,
        },
                password: {
                    required: true,
                    minlength: 8
                },
                "usergroup_id[]": {
            required: true
        }
    },
    ignore: ':hidden:not("#usergroup_id")', 
    errorClass: "help-inline",
    errorElement: "span",
    errorPlacement: function (error, element) {
        if (element.attr('name') == "password") {
            error.appendTo('#error_placeholder');
        } else if (element.hasClass('multiselect')) {
            error.insertAfter(element.next('.btn-group'));
        } else {
            error.insertAfter(element);
        }
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).hasClass('multiselect')) {
               //$(element).next('.btn-group').addClass('error');
               $(element).closest('.controls').addClass('has-error');
            }
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).hasClass('multiselect')) {
                $(element).closest('.controls').removeClass('has-error');
            }
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
    }
});

//Function to fetch help content related to Department user list
$('.show_help').on('click', function () {
    $.ajax({
        url: base_url + 'curriculum/dept_users/dept_user_help',
        datatype: "JSON",
        success: function (msg) {
            document.getElementById('help_content').innerHTML = msg;
        }
    });
});
//List User Script Ends Here