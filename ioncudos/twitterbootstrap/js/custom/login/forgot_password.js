// Forgot Password JS scripts		

var base_url = $('#get_base_url').val();

$('#submit_button_id').live('click', function () {
    var flag = 0;
    $("#loading").show();
    flag = $('#form_id').valid();
    if (flag) {
        $("#loading").hide();
        $('#request_password_dialog_id').modal('show');
    } else {
        $("#loading").hide();
    }
});

function send_request_dialog() {
    var email_id = document.getElementById('email').value;
    $("#loading").show();
    var post_data = {
        'email_id': email_id
    }
    $.ajax({
        type: "POST",
        url: base_url + 'login/genrate_forgot_password_link',
        data: post_data,
        success: function (msg) {
            if (msg == 1) {
                $("#loading").hide();
                $('#sent_email_dialog_id').modal('show');
            } else {
                $("#loading").hide();
                $('#error_in_email_id').modal('show');
            }
        }
    });
}

$('#close').click(function () {
    location.reload();
});

//function is to redirect user after successful submit
function submit_success() {
    $('#form_id').submit();
}

//Form validation 
$(document).ready(function () {

    $.validator.addMethod('email', function (value, element) {
        return this.optional(element) || /^(^[A-Za-z0-9]+([\._]?[A-Za-z0-9]+)*@[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*(\.[A-Za-z0-9]+([-]?[0-9a-zA-Z]+)*)+)+$/.test(value);
    }, 'Plesae enter the valid email address.'
            );

    $("#form_id").validate({
        rules: {
            email: {
                email: true,
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