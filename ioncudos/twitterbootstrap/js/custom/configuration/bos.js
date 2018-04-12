// List view JS functions

var base_url = $('#get_base_url').val();

$("#hint a").tooltip();

var currentID;
var bos_dept_id;
var ele;
function currentIDSt(id, bos)
{
    currentID = id;
    bos_dept_id = bos;
}
/* Function is to send the status value of BOS user to controller to enable user.
 * @param - status value
 */
function enable()
{
    var post_data = {
        'id': currentID,
        'active': '1',
    }
    $.ajax(
            {
                type: "POST",
                url: base_url + 'configuration/bos/bos_status',
                data: post_data,
                datatype: "JSON",
                success: function (msg) {
                    location.reload();
                }
            }
    );
}

function delete_data() {
    var post_data = {
        'id': currentID,
        'bos_dept_id': bos_dept_id,
    }
    $.ajax(
            {
                type: "POST",
                url: base_url + 'configuration/bos/delete_bos',
                data: post_data,
                //datatype: "JSON",
                success: function (msg) {
                    location.reload();
                }
            }
    );

}

/* Function is to send the status value of BOS user to controller to disable user.
 * @param - status value
 */
function disable()
{
    var post_d = {
        'id': currentID,
        'active': '0',
    }
    $.ajax(
            {
                type: "POST",
                url: base_url + 'configuration/bos/bos_status',
                data: post_d,
                datatype: "JSON",
                success: function (msg) {
                    location.reload();
                }
            });
}

// Add new BOS view JS functions
$(document).ready(function () {
    var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
    var randomstring = '';
    for (var i = 0; i < 8; i++) {
        var rnum = Math.floor(Math.random() * chars.length);
        randomstring += chars.substring(rnum, rnum + 1);
    }
    $('#password').val(randomstring);

    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\_\.\-\s\'\,]+$/i.test(value);
    }, "Field must contain only letters, spaces, dots, underscore or dashes.");
    
    $.validator.addMethod("customRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\_\-\s\._\'\,]+$/i.test(value);
    }, "Field must contain only dots, space, letters, underscore or dash.");

    $.validator.addMethod('email', function (value, element) {
        return this.optional(element) || /^(^[A-Za-z0-9]+([\._]?[A-Za-z0-9]+)*@[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*(\.[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*)+)+$/.test(value);
    }, 'Verify you have a valid email address.'
            );
    $("#create_newbos_user").validate({
        rules: {
            first_name: {
                customRegex: true,
            },
            last_name: {
                customRegex: true,
            },
            email: {
                email: true
            },
            experience: {
                digits: true,
                maxlength: 2
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirm: {
                required: true,
                equalTo: "#password"
            },
            organization: {
                loginRegex: true,
                maxlength: 50
            },
            qualification: {
                loginRegex: true,
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).parent().parent().addClass('success');
        }
    });
});


// Add Existing BOS view JS functions

/* Function is to submit the department id to controller for fetching users list of that department
 * @param - department id
 */
var base_url = $('#get_base_url').val();
function callAndUpdateUser(value) {
    $("#username").empty();
    $.get(base_url + 'configuration/bos/users_by_department' + '/' + value,
            null,
            function (data) {
                var arrayData = JSON.parse(data);
                var i = 0;

                if (arrayData != '') {
                    var completeOptions = '<option value="">Select User</option>';
                } else {
                    var completeOptions = '<option value="">No Users in this departmet</option>';
                }

                for (i = 0; i < arrayData.length; i++) {
                    var item = arrayData[i];
                    completeOptions += '<option value="' + item.id + '" title="' + item.email + '">' + item.title + ' ' + item.first_name + ' ' + item.last_name + '</option>';
                }
                $('#username').html(completeOptions);
            },
            "html"
            );
}

/* Function is to initiate callAndUpdateUser function.
 * @param - dept id
 */
$('.target').change(function () {
    var value = $(this).val();
    callAndUpdateUser(value);
});

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\_\-\s\']+$/i.test(value);
}, "Field must contain only letters, numbers, spaces, ' or dashes.");

$("#create_user").validate(
        {
            rules: {
                dept_id: {
                    required: true,
                },
                user_dept_id: {
                    required: true,
                },
                bos_dept_id: {
                    required: true

                },
            },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parent().parent().addClass('error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parent().parent().removeClass('error');
                $(element).parent().parent().addClass('success');
            }
        });

/* Function is to insert BOS user by sending the POST data to controller.
 * @param - POST array
 */
$(function () {
    $("#create_user").submit(function () {
        dataString = $("#create_user").serialize();
        $.ajax({
            type: "POST",
            url: base_url + 'configuration/bos/insert_existing_bos_user',
            //url: "<?php echo base_url(); ?>configuration/bos/insert_existing_bos_user",
            data: dataString,
            success: function (data) {
                if (data == 0)
                {
                    //load modal from controller
                    $('#bos_success').modal('show');
                } else
                {
                    $('#bos_retry').modal('show');
                }
            }
        });
        return false;
    });
});


// Edit view JS functions	

$.validator.addMethod("loginRegex", function (value, element) {
    return this.optional(element) || /^[a-zA-Z\.\_\-\s\']+$/i.test(value);
}, "Field must contain only letters, spaces, points, underscore or dashes.");
$("#edit_bos_user").validate({
    rules: {
        first_name: {
            customRegex: true,
        },
        last_name: {
            customRegex: true,
        },
        experience: {
            digits: true,
            maxlength: 2
        },
        password: {
            minlength: 8
        },
        password_confirm: {
            equalTo: "#password"
        },
        organization: {
            loginRegex: true,
            maxlength: 50
        },
        qualification: {
            loginRegex: true,
        }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function (element, errorClass, validClass) {
        $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).parent().parent().removeClass('error');
        $(element).parent().parent().addClass('success');
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

$('#bos_delete').click(function () {

    var post_data = {
        'id': currentID,
        'bos_dept_id': bos_dept_id,
    }

    $.ajax({
        type: "POST",
        url: base_url + 'configuration/bos/check_delete_bos',
        data: post_data,
        success: function (bos_data) {
            if (bos_data == 0) {
                $.ajax({
                    type: "POST",
                    url: base_url + 'configuration/bos/delete_bos',
                    data: post_data,
                    //datatype: "JSON",
                    success: function (msg) {
                        location.reload();
                    }
                });
            } else {
                $('#cannot_delete').modal('show');
            }
        }
    });
});