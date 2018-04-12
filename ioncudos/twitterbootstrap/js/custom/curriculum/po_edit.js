
$(document).ready(function () {

//$('#bos_comment').replace(/\s+/g,' ').replace(/>(\s)</g,'>\n<');
    $.validator.addMethod("loginRegex", function (value, element) {
        return this.optional(element) || /^[a-zA-Z 0-9\_\-\s\'\/\,\!\#\%\^\&\*\(\)\.\:\;\"\<\>\?]+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, colon, semicolon, round bracket, forward slash, ' or dashes.");
    $.validator.addMethod("loginRegex1", function (value, element) {
        return this.optional(element) || /^[a-zA-Z 0-9\_\-\s]+$/i.test(value);
    }, "Field must contain only letters, numbers, spaces, underscore, ' or dashes.");
    // Form validation rules are defined & checked before form is submitted to controller.		
    $("#add_po").validate({
        rules: {
            'po_statement': {
                loginRegex: true,
            },
            'po_name': {
                loginRegex1: true,
            },
        },
        errorClass: "help-inline font_color",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('success');
            $(element).css({"color": "red", "border-color": "red"});
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parent().parent().removeClass('error');
            $(element).css({"color": "green", "border-color": "green"});
        }
    });
});

// multiselect function
$(function () {

    var $form = $("#add_po");
    var validator = $form.data('validator');
    validator.settings.ignore = ':hidden:not(".ga_data_val"):hidden:not(".po_types")';

    $('.ga_data_val').multiselect({
        includeSelectAllOption: true,
        buttonWidth: '110px',
        nonSelectedText: 'Map GA',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });

    $('.po_types').multiselect({
        //buttonWidth: '150px',
        templates: {
            button: '<button type="button" class="multiselect btn btn-link co_multi dropdown-toggle" data-toggle="dropdown"></button>'
        }
    });



    $('.ga_data_val').click(function () {
        var selected = $('.ga_data_val  option:selected');
        var message = "";
        selected.each(function () {
            message += $(this).text() + " " + $(this).val() + "\n";
        });
        alert(message);

    });
});
//count number of characters entered in the description box
$('#po_statement_1').live('keyup', function () {
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
